<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class DbHelpersTest extends TestCase
{
    public function test_table_name_plural(): void
    {
        $result = getForeignIdColumnName('groups');

        $this->assertEquals('group_id', $result, "Foreign id for table with name 'groups' should be group_id");
    }

    public function test_table_name_singular(): void
    {
        $result = getForeignIdColumnName('group');

        $this->assertEquals('group_id', $result, "Foreign id for table with name 'group' should be group_id");
    }

    public function test_table_name_snake_case(): void
    {
        $result = getForeignIdColumnName('my_groups');

        $this->assertEquals('my_group_id', $result, "Foreign id for table with name 'my_groups' should be my_group_id");
    }

    public function test_table_name_in_camel_case(): void
    {
        $result = getForeignIdColumnName('myGroups');

        $this->assertEquals('my_group_id', $result, "Foreign id for table with name 'myGroups' should be my_group_id");
    }

    public function test_table_name_in_kebab_case(): void
    {
        $result = getForeignIdColumnName('my-groups');

        $this->assertEquals('my_group_id', $result, "Foreign id for table with name 'my-groups' should be my_group_id");
    }

    public function test_table_name_with_spaces(): void
    {
        $result = getForeignIdColumnName('my    Groups');

        $this->assertEquals('my_group_id', $result, "Foreign id for table with name 'my Groups' should be my_group_id");
    }

    public function test_table_name_mixed_cases(): void
    {
        $result = getForeignIdColumnName('my extra _mixed-cases TableGroups');

        $this->assertEquals(
            'my_extra_mixed_cases_table_group_id',
            $result,
            "Foreign id for table with name 'my extra _mixed-cases TableGroups' should be my_extra_mixed_cases_table_group_id"
        );
    }
}