<?php
use simphplio\Model;

class AcessoModel extends Model
{

    public function select_user($username)
    {
        $whereArray = array(
            array(null, "apelido", "=", "'$username'"),
        );
        $this->table = "usuarios";
        return $this->read("*", $whereArray, null, null, null, true);
    }

}