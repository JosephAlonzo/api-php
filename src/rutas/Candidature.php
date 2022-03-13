<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Slim\Factory\AppFactory;
$app->addBodyParsingMiddleware();

// GET all the candidature 
$app->get('/api/candidature', function(Request $request, Response $response){
  $sql = "SELECT * FROM candidature";
  try{
    $db = new db();
    $db = $db->conectDB();
    $resultado = $db->query($sql);

    if ($resultado->rowCount() > 0){
        $candidature = $resultado->fetchAll(PDO::FETCH_OBJ);
        $response->getBody()->write( json_encode(  array("code" => 200, "message" => $candidature ) )  );
    }else {
        $response->getBody()->write( json_encode(  array("code" => 400, "message" => "candidature not found in DataBase"))  );
    }
    $resultado = null;
    $db = null;
    return $response;
  }catch(PDOException $e){
    $response->getBody()->write( json_encode( array("code" => 500, "message" => $e->getMessage() )) );
    return $response;
  }
}); 

// GET get candidature by ID 
$app->get('/api/candidature/{id}', function(Request $request, Response $response){
  $id = $request->getAttribute('id');
  $sql = "SELECT * FROM candidature WHERE id = " . $id;
  try{
    $db = new db();
    $db = $db->conectDB();
    $resultado = $db->query($sql);

    if ($resultado->rowCount() > 0){
      $candidature = $resultado->fetchAll(PDO::FETCH_OBJ);
      
      $response->getBody()->write( json_encode( array("code" => 200, "message" => $candidature )) );
      
    }else {
      $response->getBody()->write( json_encode( array("code" => 400, 'message' => "candidature doesn't find with this ID.")) );
    }
    $resultado = null;
    $db = null;
    return $response;

  }catch(PDOException $e){
    $response->getBody()->write( json_encode( array("code" => 500, 'message' => $e->getMessage() ))  );
    return $response;
  }
}); 


// add new candidature 
$app->post('/api/candidature', function(Request $request, Response $response){
  $parsedBody = $request->getParsedBody();

    $idUser = $parsedBody['idUser'];
$idOffer = $parsedBody['idOffer'];
$vigente = $parsedBody['vigente'];

  
    $sql = "INSERT INTO candidature (idUser,idOffer,vigente) VALUES (:idUser,:idOffer,:vigente)";

  try{
    $db = new db();
    $db = $db->conectDB();
    $resultado = $db->prepare($sql);

    $resultado->bindParam(':idUser', $idUser);
$resultado->bindParam(':idOffer', $idOffer);
$resultado->bindParam(':vigente', $vigente);


    $resultado->execute();
    $response->getBody()->write( json_encode( array("code" => 200, "message" => "new candidature saved."))  );
    $resultado = null;
    $db = null;
    return $response;
    
  }catch(PDOException $e){
    $response->getBody()->write( json_encode( array("code" => 500, 'message' => $e->getMessage() ))  );
    return $response;
  }
}); 



// Update candidature 
$app->put('/api/candidature/{id}', function(Request $request, Response $response){
  $parsedBody = $request->getParsedBody();
  $id = $request->getAttribute('id');
    $idUser = $parsedBody['idUser'];
$idOffer = $parsedBody['idOffer'];
$vigente = $parsedBody['vigente'];

  
    $sql = "UPDATE candidature SET idUser = :idUser,idOffer = :idOffer,vigente = :vigente WHERE id = " . $id;
     
  try{
    $db = new db();
    $db = $db->conectDB();
    $resultado = $db->prepare($sql);

    $resultado->bindParam(':idUser', $idUser);
$resultado->bindParam(':idOffer', $idOffer);
$resultado->bindParam(':vigente', $vigente);


    $resultado->execute();
    $response->getBody()->write( json_encode( array("code" => 200, "message" => "candidature modified.")) );
    $resultado = null;
    $db = null;
    return $response;

  }catch(PDOException $e){
    $response->getBody()->write( json_encode( array("code" => 500, 'message' => $e->getMessage() ))  );
    return $response;
  }
}); 


// DELETE candidature 
$app->delete('/api/candidature/{id}', function(Request $request, Response $response){
   $id = $request->getAttribute('id');
   $sql = "DELETE FROM candidature WHERE id = " . $id;
     
  try{
    $db = new db();
    $db = $db->conectDB();
    $resultado = $db->prepare($sql);
    $resultado->execute();

    if ($resultado->rowCount() > 0) {
        $response->getBody()->write( json_encode( array("code" => 200, "message" => "candidature deleted.")) );  
    }else {
        $response->getBody()->write( json_encode( array("code" => 400, "message" => "candidature with this ID doesn't exist.")) );
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
//"idUser": ""
//"idOffer": ""
//"vigente": ""//
//}
