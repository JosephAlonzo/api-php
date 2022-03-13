<?php
namespace App\Repository;
include_once  '/home2/josephal/public_html/src/Base/RepositoryBase.php';

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use App\Config\DataBase;
use App\Base\RepositoryBase as RepositoryBase;
use \PDO;

class User extends RepositoryBase
{

    function __construct() {
        $this->table = 'user';
        $this->db = new DataBase();
    }

    public function __invoke(Request $request, Response $response)
    {
        $db = new DataBase();
        $user = $request->getParsedBody();

        if($_SERVER['REQUEST_METHOD']!=null && $_SERVER['REQUEST_METHOD'] !=""){
            $id = $request->getAttribute('id');

            if($user != null){
                $this->model = [
                    'address'    =>  array_key_exists('address', $user) ? $user['address'] : null,
                    'address2'    =>  array_key_exists('address2', $user)? $user['address2'] : null,
                    'birthday'    =>   array_key_exists('birthday', $user)? $user['birthday'] : null,
                    'city'    =>  array_key_exists('city', $user)? $user['city'] : null,
                    'cp'    =>  array_key_exists('cp', $user)? $user['cp'] : null,
                    'email'    =>  array_key_exists('email', $user)? $user['email'] : null,
                    'firstName'    =>  array_key_exists('firstName', $user)? $user['firstName'] : null,
                    'lastName'    =>  array_key_exists('lastName', $user)? $user['lastName'] : null,
                    'phone'    =>  array_key_exists('phone', $user)? $user['phone'] : null,
                    'avatar'    =>  array_key_exists('avatar', $user)? $user['avatar'] : null,
                    'sexe'    =>  array_key_exists('sexe', $user)? $user['sexe'] : null,
                    'type'    =>  array_key_exists('type', $user)? $user['type'] : null,
                    'latitude'    =>  array_key_exists('latitude', $user)? $user['latitude'] : null,
                    'longitude'    =>  array_key_exists('longitude', $user)? $user['longitude'] : null,
                    'password'    =>  array_key_exists('password', $user)? $user['password'] : null,
                    'vigente'    =>  array_key_exists('vigente', $user)? $user['vigente'] : null
                ];
            }
        
            $tmp = explode("/", strtolower($_SERVER["REQUEST_URI"]));
            $uri = end($tmp);
            $method = strtolower($_SERVER['REQUEST_METHOD']);

            switch ($method) {
                case 'get':
                    if($uri == 'user'){
                        $response->getBody()->write($this->getAll());
                    }
                    if($uri == 'logout'){
                        $response->getBody()->write($this->logout());
                    }
                    else{
                        $response->getBody()->write($this->getById($id));
                    }
                    return $response;
                case 'post':
                    if($uri == 'login'){
                        return $this->login($db, $this->model, $response);
                    }
                    else {
                        $response->getBody()->write($this->add());
                    }
                    return $response;
                case 'put':
                    $response->getBody()->write($this->update($id));
                    return $response;
                case 'delete':
                    $response->getBody()->write($this->delete($id));
                    return $response;
                case 'options':
                    $response->getBody()->write( json_encode( array("code" => 200, 'message' => 'ok' ))  );
                    return $response;
            }
        }
        
    }

    public function login($db, $data, $response){

        $email = $data['email'];
        $password = $data['password'];

        $sql = "SELECT * FROM user WHERE email = \"$email\" and password = \"$password\" ";
        try{
            $db = $db->conectDB();  
            $resultado = $db->query($sql);

            if ($resultado->rowCount() > 0){
                $user = $resultado->fetchAll(PDO::FETCH_OBJ);
                
                $_SESSION['logged'] = "LOGGED";
                $response->getBody()->write( json_encode( array("code" => 200, "data" => $user )) );
                
            }else {
                $response->getBody()->write( json_encode( array("code" => 400, 'message' => "user doesn't find with this ID.")) );
            }
            $resultado = null;
            $db = null;
            return $response;

        }catch(PDOException $e){
            $response->getBody()->write( json_encode( array("code" => 500, 'message' => $e->getMessage() ))  );
            return $response;
        }
    }

    public function logout(){
        try{
            session_destroy();
            $response->getBody()->write( json_encode( array("code" => 200, 'message' => "logout.")) );
            return $response;
        }catch(PDOException $e){
            $response->getBody()->write( json_encode( array("code" => 500, 'message' => $e->getMessage() ))  );
            return $response;
        }
    }
    
}

