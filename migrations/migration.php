<?php

require_once dirname(__DIR__) . "/core/SourceLoader.php";

class Migration
{
    const SCRIPT_DIR = __DIR__ . "/scripts/";
    const MIGRATIONS_FILE = "0_migrations";

    protected PDO $db;

    public function __construct()
    {
        $this->db = db();
        try {
            $this->db->beginTransaction();

            $this->createMigrationTable();
            $this->runMigrations();

            if ($this->db->inTransaction()) {
                $this->db->commit();
            }
        } catch (PDOException $exception) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            d($exception->getMessage(), $exception->getTrace());
        }
    }

    private function createMigrationTable(): void
    {
        d('---- Prepare migrations query table ----');

        $sql = file_get_contents(static::SCRIPT_DIR . static::MIGRATIONS_FILE . ".sql");
        $query = $this->db->prepare($sql);
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
                [".","..", static::MIGRATIONS_FILE . ".sql"]
            )
        );

        foreach ($migrations as $script) {
            $table = preg_replace('/[\d]+_/i', '', $script);
            //check if migration already was run
            // false =>
            // - get file
            // - check if empty
            // - prepare sql
            // - run migration
            // - write current script to migrations table
            // true => skip
        }

        dd($migrations);

        d('---- Migrations done ----');
    }

    public function isMigrationRun($migration)
    {
        $query = db()->prepare("SELECT * FROM migrations WHERE name = :name");
        $query->bindParam('name', $migration);
        $query->execute();
    }
}

new Migration();