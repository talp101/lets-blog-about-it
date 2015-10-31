<?php

/**
 * Created by PhpStorm.
 * User: Tal
 * Date: 31/10/2015
 * Time: 21:30
 */
interface DataBaseAdapterInterface
{
    function connect();

    function disconnect();

    function query($query);

    function fetch();

    function select($table, $where='', $fields='*', $order='', $limit=null, $offset=null);

    function insert($table, array $data);

    function update($table, array $data, $where);

    function delete($table, $where);

    function getInsertId();

    function countRows();

    function getAffectedRows();
}