<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Slim\Factory\AppFactory;
$app->addBodyParsingMiddleware();


// GET all the notifications 
$app->get('/api/notifications', function(Request $request, Response $response){
  $sql = "SELECT * FROM notifications";
  try{
    $db = new db();
    $db = $db->conectDB();
    $resultado = $db->query($sql);

    if ($resultado->rowCount() > 0){
        $notifications = $resultado->fetchAll(PDO::FETCH_OBJ);
        $response->getBody()->write( json_encode(  array("code" => 200, "message" => $notifications ) )  );
    }else {
        $response->getBody()->write( json_encode(  array("code" => 400, "message" => "notifications not found in DataBase"))  );
    }
    $resultado = null;
    $db = null;
    return $response;
  }catch(PDOException $e){
    $response->getBody()->write( json_encode( array("code" => 500, "message" => $e->getMessage() )) );
    return $response;
  }
}); 

// GET get notifications by ID 
$app->get('/api/notifications/{id}', function(Request $request, Response $response){
  $id = $request->getAttribute('id');
  $sql = "SELECT * FROM notifications WHERE userId = " . $id;
  try{
    $db = new db();
    $db = $db->conectDB();
    $resultado = $db->query($sql);

    if ($resultado->rowCount() > 0){
      $notifications = $resultado->fetchAll(PDO::FETCH_OBJ);
      
      $response->getBody()->write( json_encode( array("code" => 200, "message" => $notifications )) );
      
    }else {
      $response->getBody()->write( json_encode( array("code" => 400, 'message' => "notifications doesn't find with this ID.")) );
    }
    $resultado = null;
    $db = null;
    return $response;

  }catch(PDOException $e){
    $response->getBody()->write( json_encode( array("code" => 500, 'message' => $e->getMessage() ))  );
    return $response;
  }
}); 


// add new notifications 
$app->post('/api/notifications', function(Request $request, Response $response){
  $parsedBody = $request->getParsedBody();

    $color = $parsedBody['color'];
$date = $parsedBody['date'];
$icon = $parsedBody['icon'];
$message = $parsedBody['message'];
$userId = $parsedBody['userId'];
$vigente = $parsedBody['vigente'];

  
    $sql = "INSERT INTO notifications (color,date,icon,message,userId,vigente) VALUES (:color,:date,:icon,:message,:userId,:vigente)";

  try{
    $db = new db();
    $db = $db->conectDB();
    $resultado = $db->prepare($sql);

    $resultado->bindParam(':color', $color);
$resultado->bindParam(':date', $date);
$resultado->bindParam(':icon', $icon);
$resultado->bindParam(':message', $message);
$resultado->bindParam(':userId', $userId);
$resultado->bindParam(':vigente', $vigente);


    $resultado->execute();
    $response->getBody()->write( json_encode( array("code" => 200, "message" => "new notifications saved."))  );
    $resultado = null;
    $db = null;
    return $response;
    
  }catch(PDOException $e){
    $response->getBody()->write( json_encode( array("code" => 500, 'message' => $e->getMessage() ))  );
    return $response;
  }
}); 



// Update notifications 
$app->put('/api/notifications/{id}', function(Request $request, Response $response){
  $parsedBody = $request->getParsedBody();
  $id = $request->getAttribute('id');
    $color = $parsedBody['color'];
$date = $parsedBody['date'];
$icon = $parsedBody['icon'];
$message = $parsedBody['message'];
$userId = $parsedBody['userId'];
$vigente = $parsedBody['vigente'];

  
    $sql = "UPDATE notifications SET color = :color,date = :date,icon = :icon,message = :message,userId = :userId,vigente = :vigente WHERE id = " . $id;
     
  try{
    $db = new db();
    $db = $db->conectDB();
    $resultado = $db->prepare($sql);

    $resultado->bindParam(':color', $color);
$resultado->bindParam(':date', $date);
$resultado->bindParam(':icon', $icon);
$resultado->bindParam(':message', $message);
$resultado->bindParam(':userId', $userId);
$resultado->bindParam(':vigente', $vigente);


    $resultado->execute();
    $response->getBody()->write( json_encode( array("code" => 200, "message" => "notifications modified.")) );
    $resultado = null;
    $db = null;
    return $response;

  }catch(PDOException $e){
    $response->getBody()->write( json_encode( array("code" => 500, 'message' => $e->getMessage() ))  );
    return $response;
  }
}); 


// DELETE notifications 
$app->delete('/api/notifications/{id}', function(Request $request, Response $response){
   $id = $request->getAttribute('id');
   $sql = "DELETE FROM notifications WHERE id = " . $id;
     
  try{
    $db = new db();
    $db = $db->conectDB();
    $resultado = $db->prepare($sql);
    $resultado->execute();

    if ($resultado->rowCount() > 0) {
        $response->getBody()->write( json_encode( array("code" => 200, "message" => "notifications deleted.")) );  
    }else {
        $response->getBody()->write( json_encode( array("code" => 400, "message" => "notifications with this ID doesn't exist.")) );
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
//"color": ""
//"date": ""
//"icon": ""
//"message": ""
//"userId": ""
//"vigente": ""//
//}
