<?php
namespace simphplio\app\models;

use simphplio\Model;
use PDO;
use PDOException;

class TagModel extends Model
{
    public function insertTag($tag)
    {
       try{
        $sql = "INSERT INTO tag (`name`) VALUES (:tag)";

        $queryExe = $this->database->prepare($sql);
        
        $queryExe->bindParam(':tag',$tag);
      

        
      
         return $queryExe->execute();
       }  catch (PDOException $e) {
        return die(var_dump($e));
    }
    }
    public function selectTags()
    {
       try{
        $sql = "SELECT * FROM tag ";

        $queryExe = $this->database->prepare($sql);
        
        $queryExe->execute();
        
         return $queryExe->fetchAll(PDO::FETCH_ASSOC);
       }  catch (PDOException $e) {
        return die(var_dump($e));
    }
    }

    public function sumPROD($id)
    {
       try{
        $sql = "SELECT COUNT(tag_id) FROM product_tag WHERE tag_id =:id";

        $queryExe = $this->database->prepare($sql);

        $queryExe->bindParam(':id',$id);

        $queryExe->execute();
        
         return $queryExe->fetch(PDO::FETCH_ASSOC);
       }  catch (PDOException $e) {
        return die(var_dump($e));
    }
    }

}

