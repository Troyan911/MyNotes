<?php

require_once dirname(__DIR__) . "/core/SourceLoader.php";

class Migration
{
    const SCRIPT_DIR = __DIR__ . "/scripts/";
    const MIGRATIONS_FILE = "0_migrations";

    public function __construct()
    {
        try {
            db()->beginTransaction();

            $this->createMigrationTable();
            $this->runMigrations();

            if (db()->inTransaction()) {
                db()->commit();
            }
        } catch (PDOException $exception) {
            if (db()->inTransaction()) {
                db()->rollBack();
            }
            d($exception->getMessage(), $exception->getTrace());
        }
    }

    private function createMigrationTable(): void
    {
        d('---- Prepare migrations query table ----');

        $query = $this->getQueryFromFile(static::MIGRATIONS_FILE . ".sql");
        $result = match ($query->execute()) {
            true => 'Migration table was created (or already exists )!',
            false => 'Failed to create migration table!'
        };

        d($result, '---- Finished migrations query table ----');
    }

    private function runMigrations()
    {
        d('---- Fetching migrations ----');

        $migrations = scandir(static::SCRIPT_DIR);
        $migrations = array_values(
            array_diff(
                $migrations,
                [".", "..", static::MIGRATIONS_FILE . ".sql"]
            )
        );

        foreach ($migrations as $migration) {
            preg_replace('/[\d]+_/i', '', $migration);

            if (!$this->checkIsMigrationWasRun($migration)) {
                d(" - Run '$migration' ...");
                $query = $this->getQueryFromFile($migration);

                if ($query->execute()) {
                    d(" - '$migration' Done!");
                    $this->logIntoMigrations($migration);
                }
            } else {
                d(" - '$migration' SKIPPED!");

            }

            //check if migration already was run
            // false =>
            // - get file
            // - check if empty
            // - prepare sql
            // - run migration
            // - write current script to migrations table
            // true => skip
        }
        d('---- Migrations done ----');
    }

    protected function logIntoMigrations(string $migration): void
    {
        $query = db()->prepare("INSERT INTO migrations (name) VALUES (:name)");
        $query->bindParam('name', $migration);
        $query->execute();
    }

    public function getQueryFromFile(string $filename): false|PDOStatement
    {
        $sql = file_get_contents(static::SCRIPT_DIR . $filename);
        return db()->prepare($sql);
    }

    public function checkIsMigrationWasRun($migration): bool
    {
        $query = db()->prepare("SELECT id FROM migrations WHERE name = :name");
        $query->bindParam('name', $migration);
        $query->execute();

        return (bool)$query->fetch();
    }
}

new Migration();