<?php
namespace App\Repository;
include_once '/home2/josephal/public_html/src/Base/RepositoryBase.php';

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use App\Config\DataBase;
use App\Base\RepositoryBase as RepositoryBase;
use \PDO;

class Sent extends RepositoryBase
{
    function __construct() {
        $this->table = 'sent';
        $this->db = new DataBase();
    }

    public function __invoke(Request $request, Response $response)
    {
        $db = new DataBase();
        $data = $request->getParsedBody();

        if($_SERVER['REQUEST_METHOD']!=null && $_SERVER['REQUEST_METHOD'] !=""){
            $id = $request->getAttribute('id');

            if($data != null){

                $this->model = [
                    'userId'     => array_key_exists('userId', $data) ? $data['userId'] : null,
                    'offerId'    => array_key_exists('offerId', $data) ? $data['offerId'] : null,
                    'status'    => array_key_exists('status', $data) ? $data['status'] : null,
                    'vigente'    => array_key_exists('vigente', $data) ? $data['vigente'] : null,
                ];
            }
        
            $tmp = explode("/", strtolower($_SERVER["REQUEST_URI"]));
            $uri = end($tmp);
            $method = strtolower($_SERVER['REQUEST_METHOD']);

            switch ($method) {
                case 'get':
                    if($uri == $this->table){
                        $response->getBody()->write($this->getAll());
                    }
                    else if($uri == 'byuser'){
                        $response->getBody()->write($this->getByUserId($id));
                    }
                    else if($uri == 'byoffer'){
                        $response->getBody()->write($this->getByOfferId($id));
                    }
                    else {
                        $response->getBody()->write($this->getById($id));
                    }
                    return  $response;
                case 'post':
                    $response->getBody()->write($this->add());
                    return  $response;
                case 'put':
                    if($uri == 'updatestatus'){
                        $status = $request->getAttribute('status');
                        $offerId = $request->getAttribute('offerId');
                        $response->getBody()->write($this->updateStatus($id, $status, $offerId));
                    }
                    else{
                       $response->getBody()->write($this->update($id)); 
                    }
                    return  $response;
                    
                case 'delete':
                    $response->getBody()->write($this->delete($id));
                    return  $response;
                case 'options':
                    $response->getBody()->write( json_encode( array("code" => 200, 'message' => 'ok' ))  );
                    return  $response;
                
            }
        }
        
    }
    public function updateStatus($id, $status, $offerId){
        try{
            $sql = $sql = "UPDATE sent SET status = :status WHERE id = " . $id;
            $this->db =  $this->db->conectDB();
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':status', $status);
            $stmt->execute();
            $response = json_encode( array("code" => 200, "message" => "Record updated."));
            if($stmt){
                $sql = "UPDATE sent SET status = 3 WHERE id != " . $id ." and offerId =" . $offerId ;
                $resultado = $this->db->prepare($sql);
                $resultado->execute();
            }

            $stmt = null;
            $this->db = null;
            return $response;

        }catch(PDOException $e){
            $response = json_encode( array("code" => 500, "message" => $e->getMessage() ));
            return $response;
        }
    }

    public function getByUserId($id){
        try{
            $sql = "SELECT 
            t.userId, t.offerId, t.status, t.vigente,o.date, o.duree, o.price, o.recurrent, 
            o.icon, o.iconSecondary, o.title, o.time, 
            u.avatar, firstName, lastName
            FROM ". $this->table . " t inner join offers o on o.id = t.offerId inner join user u on u.id = t.userId WHERE t.userId = " . $id;
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

    public function getByOfferId($id){
        try{
            $sql = "SELECT 
            t.userId, t.offerId, t.status, t.vigente,o.date, o.duree, o.price, o.recurrent, 
            o.icon, o.iconSecondary, o.title, o.time, 
            u.avatar, firstName, lastName, t.id as sentId
            FROM ". $this->table . " t inner join offers o on o.id = t.offerId inner join user u on u.id = t.userId WHERE t.offerId = " . $id;
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
            $sql = "SELECT 
            t.id,
            t.userId, t.offerId, t.status, t.vigente,o.date, o.duree, o.price, o.recurrent, 
            o.icon, o.iconSecondary, o.title, o.time, 
            u.avatar, firstName, lastName, o.userId as offerUserId
            FROM ". $this->table . " t inner join offers o on o.id = t.offerId inner join user u on u.id = t.userId WHERE t.id = " . $id;
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

