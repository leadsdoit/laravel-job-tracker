<?php

declare(strict_types=1);

if (!function_exists('getForeignIdColumnName')) {
    function getForeignIdColumnName(string $tableName, string $idColumnName = 'id'): string
    {
        return str_ends_with($tableName, 's')
            ? substr($tableName, 0, -1).'_'.$idColumnName
            : $tableName.'_'.$idColumnName;
    }
}