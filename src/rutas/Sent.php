<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Slim\Factory\AppFactory;
$app->addBodyParsingMiddleware();


// GET all the sent 
$app->get('/api/sent', function(Request $request, Response $response){
  $sql = "SELECT * FROM sent s inner join offers o on s.offerId = o.id";
  try{
    $db = new db();
    $db = $db->conectDB();
    $resultado = $db->query($sql);

    if ($resultado->rowCount() > 0){
        $sent = $resultado->fetchAll(PDO::FETCH_OBJ);
        $response->getBody()->write( json_encode(  array("code" => 200, "message" => $sent ) )  );
    }else {
        $response->getBody()->write( json_encode(  array("code" => 400, "message" => "sent not found in DataBase"))  );
    }
    $resultado = null;
    $db = null;
    return $response;
  }catch(PDOException $e){
    $response->getBody()->write( json_encode( array("code" => 500, "message" => $e->getMessage() )) );
    return $response;
  }
}); 

// GET get sent by ID 
$app->get('/api/sent/userId/{id}', function(Request $request, Response $response){
  $id = $request->getAttribute('id');
  $sql = "SELECT s.`id` as sentId , s.`userId` as userSend, s.`offerId`, s.`status`, 
  o.`userId` as userOffer, o.`date`, o.`duree`, o.`price`, o.`recurrent`, o.`icon`, o.`iconSecondary`, o.`title`, o.`time`,
  u.`address`, u.`address2`, u.`birthday`, u.`city`, u.`cp`, u.`email`, u.`firstName`, u.`lastName`, u.`avatar`
  FROM sent s inner join offers o on s.offerId = o.id 
  inner join user u on o.userId = u.id 
  WHERE s.userId = " . $id;
  try{
    $db = new db();
    $db = $db->conectDB();
    $resultado = $db->query($sql);

    if ($resultado->rowCount() > 0){
      $sent = $resultado->fetchAll(PDO::FETCH_OBJ);
      
      $response->getBody()->write( json_encode( array("code" => 200, "message" => $sent )) );
      
    }else {
      $response->getBody()->write( json_encode( array("code" => 400, 'message' => "sent doesn't find with this ID.")) );
    }
    $resultado = null;
    $db = null;
    return $response;

  }catch(PDOException $e){
    $response->getBody()->write( json_encode( array("code" => 500, 'message' => $e->getMessage() ))  );
    return $response;
  }
}); 

// GET get sent by ID 
$app->get('/api/sent/{id}', function(Request $request, Response $response){
  $id = $request->getAttribute('id');
  $sql = "SELECT s.`id` as sentId , s.`userId` as userSend, s.`offerId`, s.`status`, 
  o.`userId` as userOffer, o.`date`, o.`duree`, o.`price`, o.`recurrent`, o.`icon`, o.`iconSecondary`, o.`title`, o.`time`,
  u.`address`, u.`address2`, u.`birthday`, u.`city`, u.`cp`, u.`email`, u.`firstName`, u.`lastName`, u.`avatar`
  ,u2.`firstName` as firstNameSent, u2.`lastName` as lastNameSent
  FROM sent s inner join offers o on s.offerId = o.id 
  inner join user u on o.userId = u.id inner join user u2 on u2.id  = s.userId
  WHERE s.id = " . $id;
  try{
    $db = new db();
    $db = $db->conectDB();
    $resultado = $db->query($sql);

    if ($resultado->rowCount() > 0){
      $sent = $resultado->fetchAll(PDO::FETCH_OBJ);
      
      $response->getBody()->write( json_encode( array("code" => 200, "message" => $sent )) );
      
    }else {
      $response->getBody()->write( json_encode( array("code" => 400, 'message' => "sent doesn't find with this ID.")) );
    }
    $resultado = null;
    $db = null;
    return $response;

  }catch(PDOException $e){
    $response->getBody()->write( json_encode( array("code" => 500, 'message' => $e->getMessage() ))  );
    return $response;
  }
}); 

// GET get sent by ID 
$app->get('/api/sent/offerId/{id}', function(Request $request, Response $response){
  $id = $request->getAttribute('id');
  $sql = "SELECT s.`id` as sentId , s.`userId` as userSend, s.`offerId`, s.`status`, 
  u.`address`, u.`address2`, u.`birthday`, u.`city`, u.`cp`, u.`email`, u.`firstName`, u.`lastName`, u.`avatar`
  FROM sent s inner join offers o on s.offerId = o.id 
  inner join user u on s.userId = u.id 
  WHERE o.id = " . $id;
  try{
    $db = new db();
    $db = $db->conectDB();
    $resultado = $db->query($sql);

    if ($resultado->rowCount() > 0){
      $sent = $resultado->fetchAll(PDO::FETCH_OBJ);
      
      $response->getBody()->write( json_encode( array("code" => 200, "message" => $sent )) );
      
    }else {
      $response->getBody()->write( json_encode( array("code" => 400, 'message' => "sent doesn't find with this ID.")) );
    }
    $resultado = null;
    $db = null;
    return $response;

  }catch(PDOException $e){
    $response->getBody()->write( json_encode( array("code" => 500, 'message' => $e->getMessage() ))  );
    return $response;
  }
}); 

// add new sent 
$app->post('/api/sent', function(Request $request, Response $response){
  $parsedBody = $request->getParsedBody();

  $userId = $parsedBody['userId'];
  $offerId = $parsedBody['offerId'];
  $vigente = $parsedBody['vigente'];

  
  $sql = "INSERT INTO sent (userId,offerId,vigente) VALUES (:userId,:offerId,:vigente)";

  try{
    $db = new db();
    $db = $db->conectDB();
    $resultado = $db->prepare($sql);

    $resultado->bindParam(':userId', $userId);
    $resultado->bindParam(':offerId', $offerId);
    $resultado->bindParam(':vigente', $vigente);


    $resultado->execute();

    $result = $db->lastInsertId();

    $response->getBody()->write( json_encode( array("code" => 200, "message" => "new sent saved.", "data" => $result))  );
    $resultado = null;
    $db = null;
    return $response;
    
  }catch(PDOException $e){
    $response->getBody()->write( json_encode( array("code" => 500, 'message' => $e->getMessage() ))  );
    return $response;
  }
}); 



// Update sent 
$app->put('/api/sent/{id}', function(Request $request, Response $response){
  $parsedBody = $request->getParsedBody();
  $id = $request->getAttribute('id');
  $userId = $parsedBody['userId'];
  $offerId = $parsedBody['offerId'];
  $vigente = $parsedBody['vigente'];

  
  $sql = "UPDATE sent SET userId = :userId,offerId = :offerId,vigente = :vigente WHERE id = " . $id;
     
  try{
    $db = new db();
    $db = $db->conectDB();
    $resultado = $db->prepare($sql);

    $resultado->bindParam(':userId', $userId);
    $resultado->bindParam(':offerId', $offerId);
    $resultado->bindParam(':vigente', $vigente);


    $resultado->execute();
    $response->getBody()->write( json_encode( array("code" => 200, "message" => "sent modified.")) );
    $resultado = null;
    $db = null;
    return $response;

  }catch(PDOException $e){
    $response->getBody()->write( json_encode( array("code" => 500, 'message' => $e->getMessage() ))  );
    return $response;
  }
}); 

// Update status 
$app->put('/api/sent/{id}/{status}/{offerId}', function(Request $request, Response $response){
  $parsedBody = $request->getParsedBody();
  $id = $request->getAttribute('id');
  $status = $request->getAttribute('status');
  $offerId = $request->getAttribute('offerId');
  
  $sql = "UPDATE sent SET status = :status WHERE id = " . $id;
     
  try{
    $db = new db();
    $db = $db->conectDB();
    $resultado = $db->prepare($sql);

    $resultado->bindParam(':status', $status);

    $resultado->execute();
    $response->getBody()->write( json_encode( array("code" => 200, "message" => "sent modified.")) );
    if($resultado){
      $sql = "UPDATE sent SET status = 3 WHERE id != " . $id ." and offerId =" . $offerId ;
      $resultado = $db->prepare($sql);
      $resultado->execute();
    }
    $resultado = null;
    $db = null;
    return $response;

  }catch(PDOException $e){
    $response->getBody()->write( json_encode( array("code" => 500, 'message' => $e->getMessage() ))  );
    return $response;
  }
}); 

// DELETE sent 
$app->delete('/api/sent/{id}', function(Request $request, Response $response){
   $id = $request->getAttribute('id');
   $sql = "DELETE FROM sent WHERE id = " . $id;
     
  try{
    $db = new db();
    $db = $db->conectDB();
    $resultado = $db->prepare($sql);
    $resultado->execute();

    if ($resultado->rowCount() > 0) {
        $response->getBody()->write( json_encode( array("code" => 200, "message" => "sent deleted.")) );  
    }else {
        $response->getBody()->write( json_encode( array("code" => 400, "message" => "sent with this ID doesn't exist.")) );
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
