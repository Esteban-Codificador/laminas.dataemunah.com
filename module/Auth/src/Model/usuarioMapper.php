<?php

namespace Auth\Model;

use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\Sql\Sql;
use Laminas\Db\Sql\Select;

class UsuarioMapper
{
    protected $dbAdapter;
    protected $sql;
    protected $tableName = 'dat_usuarios';
    
    public function __construct(AdapterInterface $dbAdapter)
    {
        $this->dbAdapter = $dbAdapter;
        $this->sql = new Sql($dbAdapter);
    }

    /**
     * Find user by username
     */
    public function findByUsername($username)
    {
        $select = $this->sql->select();
        $select->from($this->tableName)
               ->where(['username' => $username]);

        $statement = $this->sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();

        if (!$result->isQueryResult() || !$result->getAffectedRows()) {
            return null;
        }

        $data = $result->current();
        if (!$data) {
            return null;
        }

        $usuario = new Usuario();
        $usuario->populate($data);
        
        return $usuario;
    }

    /**
     * Find user by property (similar to ZF1 getByPropiedad)
     */
    public function getByPropiedad($property, $value)
    {
        $select = $this->sql->select();
        $select->from($this->tableName)
               ->where([$property => $value]);

        $statement = $this->sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();

        if (!$result->isQueryResult() || !$result->getAffectedRows()) {
            // Return empty user object (similar to ZF1 behavior)
            return new Usuario();
        }

        $data = $result->current();
        if (!$data) {
            return new Usuario();
        }

        $usuario = new Usuario();
        $usuario->populate($data);
        
        return $usuario;
    }

    /**
     * Find user by ID
     */
    public function findById($id)
    {
        return $this->getByPropiedad('id', $id);
    }

    /**
     * Get all users
     */
    public function findAll()
    {
        $select = $this->sql->select();
        $select->from($this->tableName)
               ->order('usernombres ASC');

        $statement = $this->sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();

        if (!$result->isQueryResult()) {
            return [];
        }

        $usuarios = [];
        foreach ($result as $row) {
            $usuario = new Usuario();
            $usuario->populate($row);
            $usuarios[] = $usuario;
        }

        return $usuarios;
    }

    /**
     * Save user (insert or update)
     */
    public function save(Usuario $usuario)
    {
        $data = [
            'username' => $usuario->getUsername(),
            'password' => $usuario->getPassword(),
            'useremail' => $usuario->getUseremail(),
            'usernombres' => $usuario->getUsernombres(),
            'userdocumento' => $usuario->getUserdocumento(),
            'role' => $usuario->getRole(),
            'activo' => $usuario->getActivo(),
            'fechamodificacion' => date('Y-m-d H:i:s')
        ];

        if ($usuario->getId()) {
            // Update
            $update = $this->sql->update();
            $update->table($this->tableName)
                   ->set($data)
                   ->where(['id' => $usuario->getId()]);

            $statement = $this->sql->prepareStatementForSqlObject($update);
            $result = $statement->execute();

            return $result->getAffectedRows();
        } else {
            // Insert
            $data['fechacreacion'] = date('Y-m-d H:i:s');
            
            $insert = $this->sql->insert();
            $insert->into($this->tableName)
                   ->values($data);

            $statement = $this->sql->prepareStatementForSqlObject($insert);
            $result = $statement->execute();

            if ($result->getAffectedRows()) {
                $usuario->setId($result->getGeneratedValue());
                return $result->getGeneratedValue();
            }
        }

        return false;
    }

    /**
     * Delete user
     */
    public function delete($id)
    {
        $delete = $this->sql->delete();
        $delete->from($this->tableName)
               ->where(['id' => $id]);

        $statement = $this->sql->prepareStatementForSqlObject($delete);
        $result = $statement->execute();

        return $result->getAffectedRows();
    }

    /**
     * Authenticate user
     */
    public function authenticate($username, $password)
    {
        $usuario = $this->findByUsername($username);
        if (!$usuario || !$usuario->getId()) {
            return false;
        }

        if (!$usuario->verifyPassword($password)) {
            return false;
        }
        return $usuario;
    }

    /**
     * Get active users count
     */
    public function getActiveUsersCount()
    {
        $select = $this->sql->select();
        $select->from($this->tableName)
               ->columns(['count' => new \Laminas\Db\Sql\Expression('COUNT(*)')])
               ->where(['activo' => 1]);

        $statement = $this->sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();

        $row = $result->current();
        return $row ? $row['count'] : 0;
    }
}