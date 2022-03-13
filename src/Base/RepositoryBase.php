<?php
namespace App\Base;

use \PDO;

class RepositoryBase
{
    public $table;
    public $model;
    public $db;

    public function getAll(){
        try{
            $sql = "SELECT * FROM ".$this->table;
            $this->db =  $this->db->conectDB();
            $stmt =  $this->db->query($sql);
        
            if ($stmt->rowCount() > 0){
                $result = $stmt->fetchAll(PDO::FETCH_OBJ);
                $response = json_encode(array("code" => 200, "message" => $result ));
            }else {
                $response =  json_encode(array("code" => 400, "message" => "Record not found in DataBase"));
            }
            $this->db = null;
            return $response;
        }catch(PDOException $e){
            $response = json_encode( array("code" => 500, "message" => $e->getMessage() ));
            return $response;
        }
    }

    public function getById($id){
        try{
            $sql = "SELECT * FROM ". $this->table ." WHERE id = " . $id;
            $this->db =  $this->db->conectDB();
            $stmt =  $this->db->query($sql);
        
            if ($stmt->rowCount() > 0){
                $result = $stmt->fetchAll(PDO::FETCH_OBJ);
                $response = json_encode(array("code" => 200, "message" => $result ));
            }else {
                $response =  json_encode(array("code" => 400, "message" => "Record not found in DataBase"));
            }
            $this->db = null;
            return $response;
        }catch(PDOException $e){
            $response = json_encode( array("code" => 500, "message" => $e->getMessage() ));
            return $response;
        }
    }

    public function add(){
        try{
            $tmp = array_keys($this->model);
            $cols = implode(",", $tmp);
            $values = implode(",:", $tmp); 

            $sql = "INSERT INTO ".$this->table."(".$cols.") VALUES (:".$values.")";

            $this->db =  $this->db->conectDB();
            $stmt = $this->db->prepare($sql);
            $stmt->execute($this->model);

            $response = json_encode( array("code" => 200, "message" => "Record created"));
            $stmt = null;
            $this->db = null;
            return $response;

        }catch(PDOException $e){
            $response = json_encode( array("code" => 500, "message" => $e->getMessage() ));
            return $response;
        }
    }

    public function update($id){
        try{
            $tmp = array_keys($this->model);
            $cols = "";
            foreach ($arr as $value) {
                $cols .= $value . "=:" . $value . ",";
            }

            $sql = "UPDATE ".$this->table." SET ". $cols ." WHERE id = " . $id;

            $this->db =  $this->db->conectDB();
            $stmt = $this->db->prepare($sql);
            $stmt->execute($this->model);

            $response = json_encode( array("code" => 200, "message" => "Record updated."));
            $stmt = null;
            $this->db = null;
            return $response;

        }catch(PDOException $e){
            $response = json_encode( array("code" => 500, "message" => $e->getMessage() ));
            return $response;
        }
    }

    public function delete($id){
        try{
            $sql = "DELETE FROM user WHERE id = " . $id;
            $this->db =  $this->db->conectDB();
            $stmt =  $this->db->query($sql);
        
            if ($stmt->rowCount() > 0){
                $response = json_encode(array("code" => 200, "message" => "Record deleted" ));
            }else {
                $response =  json_encode(array("code" => 400, "message" => "Error"));
            }
            $this->db = null;
            return $response;
        }catch(PDOException $e){
            $response = json_encode( array("code" => 500, "message" => $e->getMessage() ));
            return $response;
        }
    }

    public function filterBy($id, $condition){
        try{
            $sql = "SELECT * FROM ". $this->table ." WHERE ". $condition ." = " . $id;
            $this->db =  $this->db->conectDB();
            $stmt =  $this->db->query($sql);
        
            if ($stmt->rowCount() > 0){
                $result = $stmt->fetchAll(PDO::FETCH_OBJ);
                $response = json_encode(array("code" => 200, "message" => $result ));
            }else {
                $response =  json_encode(array("code" => 400, "message" => "Record not found in DataBase"));
            }
            $this->db = null;
            return $response;
        }catch(PDOException $e){
            $response = json_encode( array("code" => 500, "message" => $e->getMessage() ));
            return $response;
        }
    }
}