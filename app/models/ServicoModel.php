<?php
namespace simphplio\app\models;

use simphplio\Model;
use PDO;
use PDOException;

class ServicoModel extends Model
{
    public  function insertServico($servico,$valor,$prazo_estipulado,$prazo_tipo)
    {
       try{
        $sql = "INSERT INTO servicos (servico,valor,prazo_estipulado,prazo_tipo) VALUES (:servico,:valor,:prazo_estipulado,:prazo_tipo)";

        $queryExe = $this->database->prepare($sql);
        
        $queryExe->bindParam(':servico',$servico);
        $queryExe->bindParam(':valor',$valor);
        $queryExe->bindParam(':prazo_estipulado',$prazo_estipulado);
        $queryExe->bindParam(':prazo_tipo',$prazo_tipo);

        
      
         return $queryExe->execute();
       }  catch (PDOException $e) {
        return die(var_dump($e));
    }
    }

}

