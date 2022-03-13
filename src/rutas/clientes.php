<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Slim\Factory\AppFactory;
$app->addBodyParsingMiddleware();


// GET all the user 
$app->get('/api/user', function(Request $request, Response $response){
  $sql = "SELECT * FROM user";
  try{
    $db = new db();
    $db = $db->conectDB();
    $resultado = $db->query($sql);

    if ($resultado->rowCount() > 0){
        $user = $resultado->fetchAll(PDO::FETCH_OBJ);
        $response->getBody()->write( json_encode(  array("code" => 200, "message" => $user ) )  );
    }else {
        $response->getBody()->write( json_encode(  array("code" => 400, "message" => "user not found in DataBase"))  );
    }
    $resultado = null;
    $db = null;
    return $response
    ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
  }catch(PDOException $e){
    $response->getBody()->write( json_encode( array("code" => 500, "message" => $e->getMessage() )) );
    return $response;
  }
}); 

// GET get user by ID 
$app->get('/api/user/{id}', function(Request $request, Response $response){
  $id = $request->getAttribute('id');
  $sql = "SELECT * FROM user WHERE id = " . $id;
  try{
    $db = new db();
    $db = $db->conectDB();
    $resultado = $db->query($sql);

    if ($resultado->rowCount() > 0){
      $user = $resultado->fetchAll(PDO::FETCH_OBJ);
      
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
}); 

$app->post('/api/user/login', function(Request $request, Response $response){
  $parsedBody = $request->getParsedBody();
  $email = $parsedBody['email'];
  $password = $parsedBody['password'];

  $sql = "SELECT * FROM user WHERE email = \"$email\" and password = \"$password\" ";
  try{
    $db = new db();
    $db = $db->conectDB();
    $resultado = $db->query($sql);

    if ($resultado->rowCount() > 0){
      $user = $resultado->fetchAll(PDO::FETCH_OBJ);
      
      $response->getBody()->write( json_encode( array("code" => 200, "data" => $user )) );
      
    }else {
      $response->getBody()->write( json_encode( array("code" => 400, 'message' => "user doesn't find with this ID.")) );
    }
    $resultado = null;
    $db = null;
    return $response->withHeader('Access-Control-Allow-Origin', '*')
    ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
    ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');

  }catch(PDOException $e){
    $response->getBody()->write( json_encode( array("code" => 500, 'message' => $e->getMessage() ))  );
    return $response;
  }
}); 

// add new user 
$app->post('/api/user', function(Request $request, Response $response){
  $parsedBody = $request->getParsedBody();

  $address = $parsedBody['address'];
  $address2 = $parsedBody['address2'];
  $birthday = $parsedBody['birthday'];
  $city = $parsedBody['city'];
  $cp = $parsedBody['cp'];
  $email = $parsedBody['email'];
  $firstName = $parsedBody['firstName'];
  $lastName = $parsedBody['lastName'];
  $phone = $parsedBody['phone'];
  $avatar = $parsedBody['avatar'];
  $sexe = $parsedBody['sexe'];
  $type = $parsedBody['type'];
  $latitude = $parsedBody['latitude'];
  $longitude = $parsedBody['longitude'];
  $password = $parsedBody['password'];
  $vigente = $parsedBody['vigente'];
  
  $sql = "INSERT INTO user (address,address2,birthday,city,cp,email,firstName,lastName,phone,avatar,sexe,type,latitude,longitude,password,vigente) VALUES (:address,:address2,:birthday,:city,:cp,:email,:firstName,:lastName,:phone,:avatar,:sexe,:type,:latitude,:longitude,:password,:vigente)";

  try{
    $db = new db();
    $db = $db->conectDB();
    $resultado = $db->prepare($sql);

    $resultado->bindParam(':address', $address);
    $resultado->bindParam(':address2', $address2);
    $resultado->bindParam(':birthday', $birthday);
    $resultado->bindParam(':city', $city);
    $resultado->bindParam(':cp', $cp);
    $resultado->bindParam(':email', $email);
    $resultado->bindParam(':firstName', $firstName);
    $resultado->bindParam(':lastName', $lastName);
    $resultado->bindParam(':phone', $phone);
    $resultado->bindParam(':avatar', $avatar);
    $resultado->bindParam(':sexe', $sexe);
    $resultado->bindParam(':type', $type);
    $resultado->bindParam(':latitude', $latitude);
    $resultado->bindParam(':longitude', $longitude);
    $resultado->bindParam(':password', $password);
    $resultado->bindParam(':vigente', $vigente);


    $resultado->execute();
    $response->getBody()->write( json_encode( array("code" => 200, "message" => "new user saved."))  );
    $resultado = null;
    $db = null;
    return $response;
    
  }catch(PDOException $e){
    $response->getBody()->write( json_encode( array("code" => 500, 'message' => $e->getMessage() ))  );
    return $response;
  }
}); 



// Update user 
$app->put('/api/user/{id}', function(Request $request, Response $response){
  $parsedBody = $request->getParsedBody();
  $id = $request->getAttribute('id');
  $address = $parsedBody['address'];
  $address2 = $parsedBody['address2'];
  $birthday = $parsedBody['birthday'];
  $city = $parsedBody['city'];
  $cp = $parsedBody['cp'];
  $email = $parsedBody['email'];
  $firstName = $parsedBody['firstName'];
  $lastName = $parsedBody['lastName'];
  $phone = $parsedBody['phone'];
  $avatar = $parsedBody['avatar'];
  $sexe = $parsedBody['sexe'];
  $type = $parsedBody['type'];
  $latitude = $parsedBody['latitude'];
  $longitude = $parsedBody['longitude'];
  $password = $parsedBody['password'];
  $vigente = $parsedBody['vigente'];
  $sql = "UPDATE user SET address = :address,address2 = :address2,birthday = :birthday,city = :city,cp = :cp,email = :email,firstName = :firstName,lastName = :lastName,phone = :phone,avatar = :avatar,sexe = :sexe,type = :type,latitude = :latitude,longitude = :longitude,password = :password,vigente = :vigente WHERE id = " . $id;
     
  try{
    $db = new db();
    $db = $db->conectDB();
    $resultado = $db->prepare($sql);

    $resultado->bindParam(':address', $address);
    $resultado->bindParam(':address2', $address2);
    $resultado->bindParam(':birthday', $birthday);
    $resultado->bindParam(':city', $city);
    $resultado->bindParam(':cp', $cp);
    $resultado->bindParam(':email', $email);
    $resultado->bindParam(':firstName', $firstName);
    $resultado->bindParam(':lastName', $lastName);
    $resultado->bindParam(':phone', $phone);
    $resultado->bindParam(':avatar', $avatar);
    $resultado->bindParam(':sexe', $sexe);
    $resultado->bindParam(':type', $type);
    $resultado->bindParam(':latitude', $latitude);
    $resultado->bindParam(':longitude', $longitude);
    $resultado->bindParam(':password', $password);
    $resultado->bindParam(':vigente', $vigente);


    $resultado->execute();
    $response->getBody()->write( json_encode( array("code" => 200, "message" => "user modified.")) );
    $resultado = null;
    $db = null;
    return $response;

  }catch(PDOException $e){
    $response->getBody()->write( json_encode( array("code" => 500, 'message' => $e->getMessage() ))  );
    return $response;
  }
}); 


// DELETE user 
$app->delete('/api/user/{id}', function(Request $request, Response $response){
   $id = $request->getAttribute('id');
   $sql = "DELETE FROM user WHERE id = " . $id;
     
  try{
    $db = new db();
    $db = $db->conectDB();
    $resultado = $db->prepare($sql);
    $resultado->execute();

    if ($resultado->rowCount() > 0) {
        $response->getBody()->write( json_encode( array("code" => 200, "message" => "user deleted.")) );  
    }else {
        $response->getBody()->write( json_encode( array("code" => 400, "message" => "user with this ID doesn't exist.")) );
    }

    $resultado = null;
    $db = null;
    return $response;
  }catch(PDOException $e){
    $response->getBody()->write( json_encode( array("code" => 500, "message" => $e->getMessage())) );  
    return $response;
  }
}); 




