<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Slim\Factory\AppFactory;

$app->addBodyParsingMiddleware();

// GET all the advisor 
$app->get('/api/advisor', function(Request $request, Response $response){
  $sql = "SELECT * FROM advisor";
  try{
    $db = new db();
    $db = $db->conectDB();
    $resultado = $db->query($sql);

    if ($resultado->rowCount() > 0){
      $advisor = $resultado->fetchAll(PDO::FETCH_OBJ);
      $response->getBody()->write( json_encode(  array("code" => 200, "message" => $advisor ) )  );
    }else {
      $response->getBody()->write( json_encode(  array("code" => 400, "message" => "advisor not found in DataBase"))  );
    }
    $resultado = null;
    $db = null;
    return $response;
  }catch(PDOException $e){
    $response->getBody()->write( json_encode( array("code" => 500, "message" => $e->getMessage() )) );
    return $response;
  }
}); 

// GET get advisor by ID 
$app->get('/api/advisor/userId/{id}', function(Request $request, Response $response){
  $id = $request->getAttribute('id');
  $sql = "SELECT * FROM advisor WHERE userId = " . $id;
  try{
    $db = new db();
    $db = $db->conectDB();
    $resultado = $db->query($sql);

    if ($resultado->rowCount() > 0){
      $advisor = $resultado->fetchAll(PDO::FETCH_OBJ);
      
      $response->getBody()->write( json_encode( array("code" => 200, "data" => $advisor )) );
      
    }else {
      $response->getBody()->write( json_encode( array("code" => 400, 'message' => "advisor doesn't find with this ID.")) );
    }
    $resultado = null;
    $db = null;
    return $response;

  }catch(PDOException $e){
    $response->getBody()->write( json_encode( array("code" => 500, 'message' => $e->getMessage() ))  );
    return $response;
  }
}); 


// add new advisor 
$app->post('/api/advisor', function(Request $request, Response $response){
  $parsedBody = $request->getParsedBody();

    $userIdAdvisor = $parsedBody['userIdAdvisor'];
    $comment = $parsedBody['comment'];
    $date = $parsedBody['date'];
    $rating = $parsedBody['rating'];
    $userId = $parsedBody['userId'];
    $vigente = $parsedBody['vigente'];

  
    $sql = "INSERT INTO advisor (comment,date,rating,userId,userIdAdvisor,vigente) VALUES (:comment,:date,:rating,:userId,:userIdAdvisor,:vigente)";

  try{
    $db = new db();
    $db = $db->conectDB();
    $resultado = $db->prepare($sql);

    $resultado->bindParam(':userIdAdvisor', $userIdAdvisor);
    $resultado->bindParam(':comment', $comment);
    $resultado->bindParam(':date', $date);
    $resultado->bindParam(':rating', $rating);
    $resultado->bindParam(':userId', $userId);
    $resultado->bindParam(':vigente', $vigente);


    $resultado->execute();
    $response->getBody()->write( json_encode( array("code" => 200, "message" => "new advisor saved."))  );
    $resultado = null;
    $db = null;
    return $response;
    
  }catch(PDOException $e){
    $response->getBody()->write( json_encode( array("code" => 500, 'message' => $e->getMessage() ))  );
    return $response;
  }
}); 



// Update advisor 
$app->put('/api/advisor/{id}', function(Request $request, Response $response){
  $parsedBody = $request->getParsedBody();
  $id = $request->getAttribute('id');
    $comment = $parsedBody['comment'];
$date = $parsedBody['date'];
$rating = $parsedBody['rating'];
$userId = $parsedBody['userId'];
$vigente = $parsedBody['vigente'];

  
    $sql = "UPDATE advisor SET comment = :comment,date = :date,rating = :rating,userId = :userId,vigente = :vigente WHERE id = " . $id;
     
  try{
    $db = new db();
    $db = $db->conectDB();
    $resultado = $db->prepare($sql);

    $resultado->bindParam(':comment', $comment);
$resultado->bindParam(':date', $date);
$resultado->bindParam(':rating', $rating);
$resultado->bindParam(':userId', $userId);
$resultado->bindParam(':vigente', $vigente);


    $resultado->execute();
    $response->getBody()->write( json_encode( array("code" => 200, "message" => "advisor modified.")) );
    $resultado = null;
    $db = null;
    return $response;

  }catch(PDOException $e){
    $response->getBody()->write( json_encode( array("code" => 500, 'message' => $e->getMessage() ))  );
    return $response;
  }
}); 


// DELETE advisor 
$app->delete('/api/advisor/{id}', function(Request $request, Response $response){
   $id = $request->getAttribute('id');
   $sql = "DELETE FROM advisor WHERE id = " . $id;
     
  try{
    $db = new db();
    $db = $db->conectDB();
    $resultado = $db->prepare($sql);
    $resultado->execute();

    if ($resultado->rowCount() > 0) {
        $response->getBody()->write( json_encode( array("code" => 200, "message" => "advisor deleted.")) );  
    }else {
        $response->getBody()->write( json_encode( array("code" => 400, "message" => "advisor with this ID doesn't exist.")) );
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
//"comment": ""
//"date": ""
//"rating": ""
//"userId": ""
//"vigente": ""//
//}
