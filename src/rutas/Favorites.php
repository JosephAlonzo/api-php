<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Slim\Factory\AppFactory;
$app->addBodyParsingMiddleware();

// GET all the favorites 
$app->get('/api/favorites', function(Request $request, Response $response){
  $sql = "SELECT * FROM favorites";
  try{
    $db = new db();
    $db = $db->conectDB();
    $resultado = $db->query($sql);

    if ($resultado->rowCount() > 0){
        $favorites = $resultado->fetchAll(PDO::FETCH_OBJ);
        $response->getBody()->write( json_encode(  array("code" => 200, "message" => $favorites ) )  );
    }else {
        $response->getBody()->write( json_encode(  array("code" => 400, "message" => "favorites not found in DataBase"))  );
    }
    $resultado = null;
    $db = null;
    return $response;
  }catch(PDOException $e){
    $response->getBody()->write( json_encode( array("code" => 500, "message" => $e->getMessage() )) );
    return $response;
  }
}); 

// GET get favorites by ID 
$app->get('/api/favorites/userId/{id}', function(Request $request, Response $response){
  $id = $request->getAttribute('id');
  $sql = "SELECT * FROM favorites WHERE userId = " . $id;
  try{
    $db = new db();
    $db = $db->conectDB();
    $resultado = $db->query($sql);

    if ($resultado->rowCount() > 0){
      $favorites = $resultado->fetchAll(PDO::FETCH_OBJ);
      
      $response->getBody()->write( json_encode( array("code" => 200, "message" => $favorites )) );
      
    }else {
      $response->getBody()->write( json_encode( array("code" => 400, 'message' => "favorites doesn't find with this ID.")) );
    }
    $resultado = null;
    $db = null;
    return $response;

  }catch(PDOException $e){
    $response->getBody()->write( json_encode( array("code" => 500, 'message' => $e->getMessage() ))  );
    return $response;
  }
}); 


// add new favorites 
$app->post('/api/favorites', function(Request $request, Response $response){
  $parsedBody = $request->getParsedBody();

  $userId = $parsedBody['userId'];
  $offerId = $parsedBody['offerId'];
  $vigente = $parsedBody['vigente'];

  
  $sql = "INSERT INTO favorites (userId,offerId,vigente) VALUES (:userId,:offerId,:vigente)";

  try{
    $db = new db();
    $db = $db->conectDB();
    $resultado = $db->prepare($sql);

    $resultado->bindParam(':userId', $userId);
    $resultado->bindParam(':offerId', $offerId);
    $resultado->bindParam(':vigente', $vigente);


    $resultado->execute();
    $result = $db->lastInsertId();

    $response->getBody()->write( json_encode( array("code" => 200, "message" => "new favorites saved.", "data" => $result))  );
    $resultado = null;
    $db = null;
    return $response;
    
  }catch(PDOException $e){
    $response->getBody()->write( json_encode( array("code" => 500, 'message' => $e->getMessage() ))  );
    return $response;
  }
}); 



// Update favorites 
$app->put('/api/favorites/{id}', function(Request $request, Response $response){
  $parsedBody = $request->getParsedBody();
  $id = $request->getAttribute('id');
  $userId = $parsedBody['userId'];
  $offerId = $parsedBody['offerId'];
  $vigente = $parsedBody['vigente'];

  
  $sql = "UPDATE favorites SET userId = :userId,offerId = :offerId,vigente = :vigente WHERE id = " . $id;
     
  try{
    $db = new db();
    $db = $db->conectDB();
    $resultado = $db->prepare($sql);

    $resultado->bindParam(':userId', $userId);
    $resultado->bindParam(':offerId', $offerId);
    $resultado->bindParam(':vigente', $vigente);


    $resultado->execute();
    $response->getBody()->write( json_encode( array("code" => 200, "message" => "favorites modified.")) );
    $resultado = null;
    $db = null;
    return $response;

  }catch(PDOException $e){
    $response->getBody()->write( json_encode( array("code" => 500, 'message' => $e->getMessage() ))  );
    return $response;
  }
}); 


// DELETE favorites 
$app->delete('/api/favorites/{id}', function(Request $request, Response $response){
   $id = $request->getAttribute('id');
   $sql = "DELETE FROM favorites WHERE id = " . $id;
     
  try{
    $db = new db();
    $db = $db->conectDB();
    $resultado = $db->prepare($sql);
    $resultado->execute();

    if ($resultado->rowCount() > 0) {
        $response->getBody()->write( json_encode( array("code" => 200, "message" => "favorites deleted.")) );  
    }else {
        $response->getBody()->write( json_encode( array("code" => 400, "message" => "favorites with this ID doesn't exist.")) );
    }

    $resultado = null;
    $db = null;
    return $response;
  }catch(PDOException $e){
    $response->getBody()->write( json_encode( array("code" => 500, "message" => $e->getMessage())) );  
    return $response;
  }
}); 

//{
//"userId": ""
//"offerId": ""
//"vigente": ""//
//}
