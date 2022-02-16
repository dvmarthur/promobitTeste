<?php
namespace simphplio;

use PDO;
use PDOException;

abstract class Model
{


    protected PDO $database;
    public string $table;
    public function __construct()
    {
        try {
            $this->database = new PDO(
                DB_DRIVER . ':host=' . DB_HOSTNAME . ';dbname=' . DB_DATABASE,
                DB_USERNAME,
                DB_PASSWORD,
                array(
                    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8, SESSION SQL_BIG_SELECTS=1',
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::MYSQL_ATTR_FOUND_ROWS => true,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                )
            );
        } catch (PDOException $e) {
        // var_dump($e);
            echo "Erro ao conectar no banco de dados. [$e->errorInfo]";
            exit;
        }
    }

    public function insert(array $dados): array
    {

        try {
            //primeira coisa identificar se é array multidimensional
            $dados = json_decode(json_encode($dados), true);
            // Vamos fazer o casting para array de forma recursiva


            $isMulti = $this->is_multidimensional($dados);
            if ($isMulti === true) {
                $fields = implode(", ", array_keys($dados[0]));
                $values = "";
                foreach ($dados as $key => $value) {
                    //TODO PARA USAR PREPARED STATEMENTS DESCOMENTE ABAIXO
                    $placeholderArray = array_map(array($this, "replaceItem"), $value);
                    if ($key == 0) {
                            $values .= "(" . implode(",", array_values($placeholderArray)) . ")";
                    }
                }
            } else {
                $fields = implode(", ", array_keys($dados));
                $placeholderArray = array_map(array($this, "replaceItem"), $dados);
                $values = "(" . implode(",", array_values($placeholderArray)) . ")";
            }

            $queryString = "INSERT INTO `$this->table` ($fields) VALUES $values";
            $queryExe = $this->database->prepare($queryString);
            $executeResult = array();
            if ($isMulti === true) {
//TODO PARA USAR PREPARED STATEMENTS DESENVOLVER LÓGICA DE BINDING ABAIXO.
                foreach ($dados as $key => $value) {
# code...
                    for ($i = 0; $i < count($value); $i++) {
                        $index = $i + 1;
                        $keys = array_keys($value);
                        $queryExe->bindValue($index, $value[$keys[$i]]);
                    }
                    $executeResult[$key][] = $queryExe->execute();
                }
            } else {
                for ($i = 0; $i < count($dados); $i++) {
        // echo "[".($i + 1)."]".$dados[array_keys($dados)[$i]];
                    $index = $i + 1;
                    $keys = array_keys($dados);
                    $queryExe->bindValue($index, $dados[$keys[$i]]);
                }
                $executeResult[] = $queryExe->execute();
            }


            // $queryExe->execute(); //OLD


            // $this->_db->query(" INSERT INTO `{$this->_table}` ({$fields}) VALUES ({$valores}) ");
            return array("error" => false, "content" => $this->database->errorInfo(), "last_id" => $this->database->lastInsertId(), "execute_result" => $executeResult);
        } catch (PDOException $e) {
        // echo "<pre>";
            // var_dump($this->_db->errorInfo());
            // var_dump($e);
            // echo "</pre>";
            // exit;

            return array("error" => true, "content" => $e);
        }
    }

    public function read($fields = "*", $whereArray = null, $limit = null, $offset = null, $orderby = null, $isquery = null)
    {

        if (is_array($fields)) {
            $fields = implode(",", $fields);
        }
        if (is_null($fields)) {
            $fields = "*";
        }

        $whereString = null;
        if (!empty($whereArray)) {
            $whereString = "WHERE " . $this->whereBuilder($whereArray);
        }

        $limit = ($limit != null ? "LIMIT {$limit}" : "");
        $offset = ($offset != null ? "OFFSET {$offset}" : "");
        $orderby = ($orderby != null ? "ORDER BY {$orderby}" : "");
        $queryString = "SELECT $fields FROM $this->table $whereString $orderby $limit $offset";
        $queryExe = $this->database->prepare($queryString);

        if (is_array($whereArray) && !empty($whereArray)) {
            for ($i = 0; $i < count($whereArray); $i++) {
                $index = $i + 1;
                $queryExe->bindValue($index, $whereArray[$i][2]);
            }
        }

        $queryExe->execute();
        if ($isquery == true) {
            return $queryExe;
        } else {
            return $queryExe->fetchAll(PDO::FETCH_ASSOC);
        }
    }

    public function update(array $dados, $where)
    {

        try {
            $placeholderArray = array_map(array($this, "replaceItem"), $dados);
            if (is_array($where)) {
                $where = $this->whereBuilder($where);
            }

            foreach ($placeholderArray as $ind => $val) {
                $campos[] = "{$ind} = {$val}";
            }
            $campos = implode(", ", $campos);
            $queryString = " UPDATE `{$this->table}` SET {$campos} WHERE {$where} ";
            $queryExe = $this->database->prepare($queryString);

            for ($i = 0; $i < count($dados); $i++) {
                $index = $i + 1;
                $key = array_keys($dados);
                $queryExe->bindValue($index, $dados[$key[$i]]);
            }


            $queryExe->execute();
            $result = array("error" => false, "content" => $this->database->errorInfo(), "affected_rows" => $queryExe->rowCount());
        } catch (PDOException $e) {
            $result = array("error" => true, "content" => $e->errorInfo[2]);
        }

        return $result;
    }

    public function delete($whereArray)
    {

        $whereString = $this->whereBuilder($whereArray);
        $queryString = "DELETE FROM `{$this->table}` WHERE {$whereString}";
        $queryExe = $this->database->prepare($queryString);

        for ($i = 0; $i < count($whereArray); $i++) {
            $index = $i + 1;
            $queryExe->bindValue($index, $whereArray[$i][2]);
        }

        return $queryExe->execute();
    }

    protected function replaceItem()
    {
        return "?";
    }
    protected function whereBuilder($array)
    {

        /*
            Exemplo de array recebido:

            $whereArray = array(
                array(null, "cpf","=",$cpf),
                array("OR","email","=",$email)
            );

            TO DO: Avaliar um jeito de montar querys mais complexas como abaixo:
            SELECT * FROM people
            WHERE (first_name = :first_name or :first_name is null)
            AND (last_name = :last_name or :last_name is null)
            AND (age = :age or :age is null)
            AND (sex = :sex or :sex is null)
        */
        if (is_array($array) && !empty($array)) {
            $arrayCount = count($array);
            $line = null;
            //Percorremos o array recebido
            for ($i = 0; $i < $arrayCount; $i++) {
                if (!empty($array[$i][0])) {
                    $line .= trim($array[$i][0]) . " ";
                }
                $line .= trim($array[$i][1]) . " " . trim($array[$i][2]) . " " . $array[$i][3] . " ";
            }
            return " " . $line;
        } else {
            return null;
        }
    }

    public function is_multidimensional($a)
    {
        foreach ($a as $v) {
            if (is_array($v)) {
                return true;
            }
        }
        return false;
    }
    public function query($query, $isquery = false)
    {

        $q = $this->database->query($query);
        if ($isquery == true) {
            return $q;
        } else {
            return $q->fetchAll(PDO::FETCH_ASSOC);
        }
    }

    public static function PDOMessages($pdoObject, bool $returnMessage = true): string
    {
        if (!is_array($pdoObject) && get_class($pdoObject) == "PDOException") {
            $message = match ($pdoObject->getCode()) {
                23000, '23000' => "Registro já cadastrado",
                default => "Erro inesperado.",
            };
        } else {
            $message = "Inserido com sucesso";
        }

        if ($returnMessage) {
            $message .= "<br/><br/>" . $pdoObject->getMessage();
        }

        return $message;
    }
}
