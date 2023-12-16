<?php

namespace Core\Traits;

trait Queryable
{
    protected static string|null $tableName = null;
    private static string $query= '';

    protected array$commands = [];

}