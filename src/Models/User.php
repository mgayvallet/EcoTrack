<?php

namespace MVC\Models;

class User
{
    private $id;
    private $name;
    private $email;
    private $password;
    private $created_at;
    private $updated_at;

    public function __construct($name = '', $email = '', $password = '', $id = null)
    {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
    }

    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;
    }

    public function hashPassword()
    {
        $this->password = password_hash($this->password, PASSWORD_BCRYPT, ['cost' => 12]);
    }

    public function verifyPassword($password)
    {
        return password_verify($password, $this->password);
    }
}
