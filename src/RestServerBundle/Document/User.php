<?php

namespace RestServerBundle\Document;


/**
* @MongoDB\Document
*/
class User {
    /**
    * @MongoDB\Id
    */
    private $id;
    /**
    * @MongoDB\Field(type="string")
    */
    private $nome;
    /**
    * @MongoDB\Field(type="string")
    */
    private $role;


    /**
     * Get id
     *
     * @return id $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nome
     *
     * @param string $nome
     * @return $this
     */
    public function setNome($nome)
    {
        $this->nome = $nome;
        return $this;
    }

    /**
     * Get nome
     *
     * @return string $nome
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * Set role
     *
     * @param string $role
     * @return $this
     */
    public function setRole($role)
    {
        $this->role = $role;
        return $this;
    }

    /**
     * Get role
     *
     * @return string $role
     */
    public function getRole()
    {
        return $this->role;
    }
}
