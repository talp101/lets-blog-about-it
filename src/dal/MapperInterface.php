<?php

/**
 * Created by PhpStorm.
 * User: Tal
 * Date: 31/10/2015
 * Time: 23:49
 */
interface MapperInterface
{
    public function findById($id);

    public function find($conditions='');

    public function insert($entity);

    public function update($entity);

    public function delete($id, $col='id');
}