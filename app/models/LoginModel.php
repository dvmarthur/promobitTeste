<?php
namespace simphplio\app\models;

use simphplio\Model;
use PDO;
use PDOException;

class LoginModel extends Model
{
    public  function verificaUser($user,$pass)
    {
       try{
        $sql = "SELECT * FROM usuarios WHERE usuario = :user AND senha = :pass";

        $queryExe = $this->database->prepare($sql);

        $queryExe->bindParam(':user',$user);
        $queryExe->bindParam(':pass',$pass);
        $queryExe->execute();
      
         return $queryExe->fetch(PDO::FETCH_ASSOC);
       }  catch (PDOException $e) {
        return die(var_dump("Erro SQLloginmodel"));
    }
    }

}

