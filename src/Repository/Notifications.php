<?php
namespace App\Repository;
include_once '/home2/josephal/public_html/src/Base/RepositoryBase.php';

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use App\Config\DataBase;
use App\Base\RepositoryBase as RepositoryBase;
use \PDO;

class Notifications extends RepositoryBase
{
    function __construct() {
        $this->table = 'notifications';
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
                    'color'      => array_key_exists('color', $data) ? $data['color'] : null,
                    'date'       => array_key_exists('date', $data) ? $data['date'] : null,
                    'icon'       => array_key_exists('icon', $data) ? $data['icon'] : null,
                    'message'     => array_key_exists('message', $data) ? $data['message'] : null,
                    'vigente'    => array_key_exists('vigente', $data) ? $data['vigente'] : null,
                    'userId'     => array_key_exists('userId', $data) ? $data['userId'] : null,
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
                        $response->getBody()->write($this->filterBy($id,"userId"));
                    }
                    else{
                        $response->getBody()->write($this->getById($id));
                    }
                    return $response;
                case 'post':
                    $response->getBody()->write($this->add());
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

}

