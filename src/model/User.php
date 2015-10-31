<?php

/**
 * Created by PhpStorm.
 * User: Tal
 * Date: 01/11/2015
 * Time: 00:23
 */
class User
{
    public $id;
    public $first_name;
    public $last_name;
    public $email;
    public $password;

    public function __construct($id, $first_name, $last_name, $email, $password){
        $this->id = $id;
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->email = $email;
        $this->password = $password;
    }

}