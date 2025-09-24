<?php

declare(strict_types=1);

if (!function_exists('getForeignIdColumnName')) {
    function getForeignIdColumnName(string $tableName, string $idColumnName = 'id'): string
    {
        $parts = preg_split('/[-\s_]+|(?=[A-Z])/', $tableName, -1, PREG_SPLIT_NO_EMPTY);

        $tableName = strtolower(implode('_', $parts));

        return str_ends_with($tableName, 's')
            ? substr($tableName, 0, -1).'_'.$idColumnName
            : $tableName.'_'.$idColumnName;
    }
}