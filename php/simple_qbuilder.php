<?php

function update($table, $data, $where_items, $where_implode = 'AND')
{

    if (!$table || !is_array($data) || !$where_items || !in_array(strtoupper($where_implode), ['AND', 'OR'])) {
        return;
    }

    $columns = [];
    $values = [];
    $where_array = [];

    foreach ($data as $col_name => $val) {
        if ($col_name) {
            $columns[] = $col_name;
            $values[] = $val;
        }
    }
    $holders = array_fill(0, count($columns), '?');


    foreach ($where_items as $col => $val) {
        $values[] = $val;
        $where_array[] = $col . ' = ?';
    }

    $where = implode(" $where_implode ", $where_array);
    $sql = "UPDATE {$table} SET ";

    $first = true;
    foreach ($columns as $col_name) {
        $sql .= ($first ? '' : ', ') . $col_name . ' = ?';

        if ($first) {
            $first = false;
        }
    }
    $sql .= ' WHERE ' . $where;
    // return $this->doQuery($sql, $values);
}

function insert($table, $data, $insert_id = FALSE)
{

    if (!$table || !is_array($data)) {
        return;
    }
    $columns = [];
    $values = [];

    foreach ($data as $col_name => $val) {
        if ($col_name) {
            $columns[] = $col_name;
            $values[] = $val;
        }
    }
    $holders = array_fill(0, count($columns), '?');
    $sql = "INSERT INTO {$table} (" . implode(', ', $columns) . ") VALUES (" . implode(', ', $holders) . ")";
    // return $insert_id ? $this->doQueryInsert($sql, $values) : $this->doQuery($sql, $values);
}
