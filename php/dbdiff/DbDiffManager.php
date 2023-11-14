<?php

/**
 * Class DbDiffManager
 * @property  \database database
 * @package app\modules\api
 */
class DbDiffManager
{
    private $db_name_main, $db_name_second;
    private $tables_main, $tables_second;
    private $which;

    const TABLE_MAIN = 'main', TABLE_SECOND = 'second';

    private $table_column_name = 'COLUMN_NAME';
    private $table_column_ignore = [
        'TABLE_SCHEMA', 'TABLE_NAME', 'ORDINAL_POSITION',
        'CHARACTER_SET_NAME', 'DATETIME_PRECISION', 'NUMERIC_PRECISION', 'TABLE_CATALOG',
        'PRIVILEGES'
    ];
    private $table_columns = [
        "TABLE_CATALOG",
        "TABLE_SCHEMA",
        "TABLE_NAME",
        "COLUMN_NAME",
        "ORDINAL_POSITION",
        "COLUMN_DEFAULT",
        "IS_NULLABLE",
        "DATA_TYPE",
        "CHARACTER_MAXIMUM_LENGHT",
        "CHARACTER_OCTET_LENGTH",
        "NUMERIC_PRECISION",
        "NUMBER_SCALE",
        "DATETIME_PRECISION",
        "CHARACTER_SET_NAME",
        "COLLATION_NAME",
        "COLUMN_TYPE",
        "COLUMN_KEY",
        "EXTRA",
        "PRIVILEGES",
        "COLUMN_COMMENT",
        "IS_GENERATED",
        "GENERATION_EXPRESSION"
    ];

    function __construct($database)
    {
        $this->database = $database;
    }

    public function db($db_name, $which)
    {
        $this->which($which);
        $db_target = strtolower($which) == 'main' ? 'tables_main' : 'tables_second';

        if ($which == 'main') {
            $this->db_name_main = $db_name;
        }

        if ($which == 'second') {
            $this->db_name_second = $db_name;
        }

        $sql = "SELECT table_name FROM information_schema.tables WHERE table_schema=? ORDER BY table_name ASC";
        $tables = $this->database->doSelect($sql, [$db_name]);

        if (!$tables) {
            throw new \Exception("{$db_name} is empty!");
        }

        $this->{$db_target} = array_map(function ($tbl) {
            return $tbl['table_name'];
        }, $tables);

        return $this;
    }

    public function db_($db_name, $which, $table_name)
    {
        $this->which($which);
        $db_target = strtolower($which) == 'main' ? 'tables_main' : 'tables_second';

        if ($which == 'main') {
            $this->db_name_main = $db_name;
        }
        if ($which == 'second') {
            $this->db_name_second = $db_name;
        }

        $this->{$db_target} = [$table_name];
        return $this;
    }

    public function get_db_name($which)
    {
        if (!$which || !in_array(strtolower($which), [self::TABLE_MAIN, self::TABLE_SECOND])) {
            throw new \Exception('Which is not valid. valid value is "main" "second"');
        }

        return $which == 'main' ? $this->db_name_main : $this->db_name_second;
    }

    public function which($which)
    {
        $which = strtolower($which);
        if (!$which || !in_array($which, ['main', 'second'])) {
            throw new \Exception('Which is not valid. valid value is "main" "second"');
        }
        $this->which = strtolower($which);

        return $this;
    }

    public function get_tables($which = '')
    {
        if ($which) {
            $this->which($which);
        }

        if (!$this->which) {
            throw new \Exception('Which is not valid. valid value is "main" "second"');
        }

        $tbl = "tables_" . strtolower($which);
        return $this->{$tbl};
    }

    public function diff_tables()
    {
        $this->diff_tables = [];
        foreach ($this->tables_main as $tbl) {
            if (!in_array($tbl, $this->tables_second)) {
                $this->diff_tables[] = $tbl;
            }
        }
        return $this->diff_tables;
    }

    public function diff_tables_structures()
    {
        $tables = [];
        if (!$this->tables_main || !$this->tables_second) {
            throw new \Exception("\"main\" OR \"second\" table is empty");
        }

        foreach ($this->tables_main as $tbl) {
            if (in_array($tbl, $this->tables_second)) {
                $tables[] = $tbl;
            }
        }

        if (!$tables) {
            throw new \Exception("'main' AND 'second' haven't any same table");
        }

        $splited = [];
        foreach ($tables as $tbl) {
            $sql = "SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME=? AND TABLE_SCHEMA = '$this->db_name_main'";
            $main_cols = $this->database->doSelect($sql, [$tbl]);

            $sql = "SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME=? AND TABLE_SCHEMA = '$this->db_name_second'";
            $second_cols = $this->database->doSelect($sql, [$tbl]);

            $splited[$tbl] = $this->split_columns($main_cols, $second_cols, $tbl);
        }

        return ['diff' => $splited];
    }

    private function split_columns($main_cols, $second_cols, $table_name)
    {
        $main = [];
        $second = [];

        foreach ($main_cols as $row) {
            $main[$row[$this->table_column_name]] = $row;
        }

        foreach ($second_cols as $row) {
            $second[$row[$this->table_column_name]] = $row;
        }

        $diff = [];

        foreach ($main as $key => $val) {
            $m = $val;
            $s = $second[$key] ?? [];

            $o = [];
            $has_diff = FALSE;
            foreach ($m as $k => $v) {
                if (in_array(strtoupper($k), $this->table_column_ignore)) {
                    continue;
                }
                $sk = $s[$k] ?? '';
                if ($sk && $v) {
                    $o[] = [
                        'attr' => $k,
                        'm' => $v,
                        's' => $sk,
                        'eq' => $sk == $v
                    ];
                } else if (!$sk && $v) {
                    $o[] = [
                        'attr' => $k,
                        'm' => $v,
                        's' => '',
                        'eq' => FALSE
                    ];
                } else if ($sk && !$v) {
                    $o[] = [
                        'attr' => $k,
                        'm' => '',
                        's' => $sk,
                        'eq' => FALSE
                    ];
                }

                if ($sk != $v) {
                    $has_diff = TRUE;
                }

            }
            $diff[$key] = $o;
        }
        return ['diff' => $diff, 'has_diff' => $has_diff ?? FALSE];
    }

}