<?php

/**
 * Created by PhpStorm.
 * User: Tal
 * Date: 01/11/2015
 * Time: 00:17
 */
class UserMapper extends AbstractMapper
{
    protected $entityTable = 'users';
    protected $entityClass = 'User';

    public function __construct(DataBaseAdapterInterface $adapter){
        parent::__construct($adapter);
    }

    protected function createEntity(array $data)
    {
        $user = new $this->entityClass(array(
            'id' => $data['id'],
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => $data['password']
        ));

        return $user;
    }
}