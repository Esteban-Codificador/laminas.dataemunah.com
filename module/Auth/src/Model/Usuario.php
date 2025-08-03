<?php

namespace Auth\Model;

class Usuario
{
    protected $id;
    protected $username;
    protected $password;
    protected $useremail;
    protected $usernombres;
    protected $userdocumento;
    protected $role;
    protected $activo;
    protected $fechacreacion;
    protected $fechamodificacion;

    // Getters
    public function getId()
    {
        return $this->id;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getUseremail()
    {
        return $this->useremail;
    }

    public function getUsernombres()
    {
        return $this->usernombres;
    }

    public function getUserdocumento()
    {
        return $this->userdocumento;
    }

    public function getRole()
    {
        return $this->role;
    }

    public function getActivo()
    {
        return $this->activo;
    }

    public function getFechacreacion()
    {
        return $this->fechacreacion;
    }

    public function getFechamodificacion()
    {
        return $this->fechamodificacion;
    }

    // Setters
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    public function setUseremail($useremail)
    {
        $this->useremail = $useremail;
        return $this;
    }

    public function setUsernombres($usernombres)
    {
        $this->usernombres = $usernombres;
        return $this;
    }

    public function setUserdocumento($userdocumento)
    {
        $this->userdocumento = $userdocumento;
        return $this;
    }

    public function setRole($role)
    {
        $this->role = $role;
        return $this;
    }

    public function setActivo($activo)
    {
        $this->activo = $activo;
        return $this;
    }

    public function setFechacreacion($fechacreacion)
    {
        $this->fechacreacion = $fechacreacion;
        return $this;
    }

    public function setFechamodificacion($fechamodificacion)
    {
        $this->fechamodificacion = $fechamodificacion;
        return $this;
    }

    /**
     * Populate object from array (similar to ZF1 populate)
     */
    public function populate(array $data)
    {
        if (isset($data['id'])) {
            $this->setId($data['id']);
        }
        if (isset($data['username'])) {
            $this->setUsername($data['username']);
        }
        if (isset($data['password'])) {
            $this->setPassword($data['password']);
        }
        if (isset($data['useremail'])) {
            $this->setUseremail($data['useremail']);
        }
        if (isset($data['name'])) {
            $this->setUsernombres($data['name']);
        }
        if (isset($data['userdocumento'])) {
            $this->setUserdocumento($data['userdocumento']);
        }
        if (isset($data['role'])) {
            $this->setRole($data['role']);
        }
        return $this;
    }

    /**
     * Convert object to array (similar to ZF1 toArray)
     */
    public function toArray()
    {
        return [
            'id' => $this->getId(),
            'username' => $this->getUsername(),
            'password' => $this->getPassword(),
            'email' => $this->getUseremail(),
            'name' => $this->getUsernombres(),
            'lastname' => $this->getUsernombres(),
            'role' => $this->getRole()
        ];
    }

    /**
     * Verify password (MD5)
     */
    public function verifyPassword($password)
    {
        return $this->password === md5($password);
    }
}