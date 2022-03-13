<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Slim\Factory\AppFactory;
$app->addBodyParsingMiddleware();


// GET all the comptes 
$app->get('/api/comptes', function(Request $request, Response $response){
  $sql = "SELECT * FROM comptes";
  try{
    $db = new db();
    $db = $db->conectDB();
    $resultado = $db->query($sql);

    if ($resultado->rowCount() > 0){
        $comptes = $resultado->fetchAll(PDO::FETCH_OBJ);
        $response->getBody()->write( json_encode(  array("code" => 200, "message" => $comptes ) )  );
    }else {
        $response->getBody()->write( json_encode(  array("code" => 400, "message" => "comptes not found in DataBase"))  );
    }
    $resultado = null;
    $db = null;
    return $response;
  }catch(PDOException $e){
    $response->getBody()->write( json_encode( array("code" => 500, "message" => $e->getMessage() )) );
    return $response;
  }
}); 

// GET get comptes by ID 
$app->get('/api/comptes/{id}', function(Request $request, Response $response){
  $id = $request->getAttribute('id');
  $sql = "SELECT * FROM comptes WHERE userId = " . $id;
  try{
    $db = new db();
    $db = $db->conectDB();
    $resultado = $db->query($sql);

    if ($resultado->rowCount() > 0){
      $comptes = $resultado->fetchAll(PDO::FETCH_OBJ);
      
      $response->getBody()->write( json_encode( array("code" => 200, "message" => $comptes )) );
      
    }else {
      $response->getBody()->write( json_encode( array("code" => 400, 'message' => "comptes doesn't find with this ID.")) );
    }
    $resultado = null;
    $db = null;
    return $response;

  }catch(PDOException $e){
    $response->getBody()->write( json_encode( array("code" => 500, 'message' => $e->getMessage() ))  );
    return $response;
  }
}); 


// add new comptes 
$app->post('/api/comptes', function(Request $request, Response $response){
  $parsedBody = $request->getParsedBody();

    $color = $parsedBody['color'];
$date = $parsedBody['date'];
$icon = $parsedBody['icon'];
$month = $parsedBody['month'];
$total = $parsedBody['total'];
$vigente = $parsedBody['vigente'];

  
    $sql = "INSERT INTO comptes (color,date,icon,month,total,vigente) VALUES (:color,:date,:icon,:month,:total,:vigente)";

  try{
    $db = new db();
    $db = $db->conectDB();
    $resultado = $db->prepare($sql);

    $resultado->bindParam(':color', $color);
$resultado->bindParam(':date', $date);
$resultado->bindParam(':icon', $icon);
$resultado->bindParam(':month', $month);
$resultado->bindParam(':total', $total);
$resultado->bindParam(':vigente', $vigente);


    $resultado->execute();
    $response->getBody()->write( json_encode( array("code" => 200, "message" => "new comptes saved."))  );
    $resultado = null;
    $db = null;
    return $response;
    
  }catch(PDOException $e){
    $response->getBody()->write( json_encode( array("code" => 500, 'message' => $e->getMessage() ))  );
    return $response;
  }
}); 



// Update comptes 
$app->put('/api/comptes/{id}', function(Request $request, Response $response){
  $parsedBody = $request->getParsedBody();
  $id = $request->getAttribute('id');
    $color = $parsedBody['color'];
$date = $parsedBody['date'];
$icon = $parsedBody['icon'];
$month = $parsedBody['month'];
$total = $parsedBody['total'];
$vigente = $parsedBody['vigente'];

  
    $sql = "UPDATE comptes SET color = :color,date = :date,icon = :icon,month = :month,total = :total,vigente = :vigente WHERE id = " . $id;
     
  try{
    $db = new db();
    $db = $db->conectDB();
    $resultado = $db->prepare($sql);

    $resultado->bindParam(':color', $color);
$resultado->bindParam(':date', $date);
$resultado->bindParam(':icon', $icon);
$resultado->bindParam(':month', $month);
$resultado->bindParam(':total', $total);
$resultado->bindParam(':vigente', $vigente);


    $resultado->execute();
    $response->getBody()->write( json_encode( array("code" => 200, "message" => "comptes modified.")) );
    $resultado = null;
    $db = null;
    return $response;

  }catch(PDOException $e){
    $response->getBody()->write( json_encode( array("code" => 500, 'message' => $e->getMessage() ))  );
    return $response;
  }
}); 


// DELETE comptes 
$app->delete('/api/comptes/{id}', function(Request $request, Response $response){
   $id = $request->getAttribute('id');
   $sql = "DELETE FROM comptes WHERE id = " . $id;
     
  try{
    $db = new db();
    $db = $db->conectDB();
    $resultado = $db->prepare($sql);
    $resultado->execute();

    if ($resultado->rowCount() > 0) {
        $response->getBody()->write( json_encode( array("code" => 200, "message" => "comptes deleted.")) );  
    }else {
        $response->getBody()->write( json_encode( array("code" => 400, "message" => "comptes with this ID doesn't exist.")) );
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
//"month": ""
//"total": ""
//"vigente": ""//
//}
