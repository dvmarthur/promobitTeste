<?php

namespace simphplio\app\models;

use simphplio\Model;
use PDO;
use PDOException;

class ProdutoModel extends Model
{
    public function insertProduto($produto)
    {
        try {
            $sql = "INSERT INTO product (`name`) VALUES (:produto)";

            $queryExe = $this->database->prepare($sql);

            $queryExe->bindParam(':produto', $produto);

            $queryExe->execute();
            $idinserido = $this->database->lastInsertId();
            return $idinserido;
        } catch (PDOException $e) {
            return die(var_dump($e));
        }
    }
    public function updateProduto($name,$id)
    {
        try {
            $sql = "UPDATE product
        SET name = :name
        WHERE id = :id ";

            $queryExe = $this->database->prepare($sql);

            $queryExe->bindParam(':name', $name);
            $queryExe->bindParam(':id', $id);


            $queryExe->execute();
            // $idinserido = $this->database->lastInsertId();
            // return $idinserido;
        } catch (PDOException $e) {
            return die(var_dump($e));
        }
    }
    public function insertTagProduto($id, $tag)
    {
        try {
            $sql = "INSERT INTO product_tag (`product_id`,`tag_id`) VALUES (:id,:tag)";

            $queryExe = $this->database->prepare($sql);

            $queryExe->bindParam(':id', $id);
            $queryExe->bindParam(':tag', $tag);

            $queryExe->execute();
        } catch (PDOException $e) {
            return die(var_dump($e));
        }
    }
    public function listarProdutos()
    {
        try {
            $sql = "SELECT * FROM product";

            $queryExe = $this->database->prepare($sql);


            $queryExe->execute();

            return $queryExe->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return die(var_dump($e));
        }
    }
    public function deleteProduto($id)
    {
        try {
            $sql = "DELETE FROM product WHERE `id` = :id";

            $queryExe = $this->database->prepare($sql);

            $queryExe->bindParam(':id', $id);

            $queryExe->execute();
        } catch (PDOException $e) {
            return die(var_dump($e));
        }
    }
    public function deleteProdutoTag($id)
    {
        try {
            $sql = "DELETE FROM product_tag WHERE `product_id` = :id";

            $queryExe = $this->database->prepare($sql);

            $queryExe->bindParam(':id', $id);

            $queryExe->execute();
        } catch (PDOException $e) {
            return die(var_dump($e));
        }
    }

    public function selectProduto($id)
    {
        try {
            $sql = "SELECT * FROM product WHERE `id` = :id";

            $queryExe = $this->database->prepare($sql);

            $queryExe->bindParam(':id', $id);

            $queryExe->execute();

            return $queryExe->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return die(var_dump($e));
        }
    }
}
