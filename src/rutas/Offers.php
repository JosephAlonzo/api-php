<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Slim\Factory\AppFactory;
$app->addBodyParsingMiddleware();

// GET all the offers 
$app->get('/api/offers', function(Request $request, Response $response){
  $sql = "SELECT 
  o.`id`, o.`userId`, o.`date`, o.`duree`, o.`price`, o.`recurrent`, o.`icon`, o.`iconSecondary`, o.`title`, o.`time`,
  u.`address`, u.`address2`, u.`birthday`, u.`city`, u.`cp`, u.`email`, u.`firstName`, u.`lastName`, u.`phone`, u.`avatar`
  FROM offers o inner join user u on u.id = o.userId";
  try{
    $db = new db();
    $db = $db->conectDB();
    $resultado = $db->query($sql);

    if ($resultado->rowCount() > 0){
        $offers = $resultado->fetchAll(PDO::FETCH_OBJ);
        $response->getBody()->write( json_encode(  array("code" => 200, "data" => $offers ) )  );
    }else {
        $response->getBody()->write( json_encode(  array("code" => 400, "message" => "offers not found in DataBase"))  );
    }
    $resultado = null;
    $db = null;
    return $response;
  }catch(PDOException $e){
    $response->getBody()->write( json_encode( array("code" => 500, "message" => $e->getMessage() )) );
    return $response;
  }
}); 

// GET get offers by ID 
$app->get('/api/offers/{id}', function(Request $request, Response $response){
  $id = $request->getAttribute('id');
  $sql = "SELECT * FROM offers WHERE id = " . $id;
  try{
    $db = new db();
    $db = $db->conectDB();
    $resultado = $db->query($sql);

    if ($resultado->rowCount() > 0){
      $offers = $resultado->fetchAll(PDO::FETCH_OBJ);
      
      $response->getBody()->write( json_encode( array("code" => 200, "message" => $offers )) );
      
    }else {
      $response->getBody()->write( json_encode( array("code" => 400, 'message' => "offers doesn't find with this ID.")) );
    }
    $resultado = null;
    $db = null;
    return $response;

  }catch(PDOException $e){
    $response->getBody()->write( json_encode( array("code" => 500, 'message' => $e->getMessage() ))  );
    return $response;
  }
}); 

// GET get offers by user ID 
$app->get('/api/offers/userId/{id}', function(Request $request, Response $response){
  $id = $request->getAttribute('id');
  $sql = "SELECT * FROM offers WHERE userId = " . $id;
  try{
    $db = new db();
    $db = $db->conectDB();
    $resultado = $db->query($sql);

    if ($resultado->rowCount() > 0){
      $offers = $resultado->fetchAll(PDO::FETCH_OBJ);
      
      $response->getBody()->write( json_encode( array("code" => 200, "data" => $offers )) );
      
    }else {
      $response->getBody()->write( json_encode( array("code" => 400, 'message' => "offers doesn't find with this user ID.")) );
    }
    $resultado = null;
    $db = null;
    return $response;

  }catch(PDOException $e){
    $response->getBody()->write( json_encode( array("code" => 500, 'message' => $e->getMessage() ))  );
    return $response;
  }
}); 


// add new offers 
$app->post('/api/offers', function(Request $request, Response $response){
  $parsedBody = $request->getParsedBody();

  $userId = $parsedBody['userId'];
  $date = $parsedBody['date'];
  $duree = $parsedBody['duree'];
  $price = $parsedBody['price'];
  $recurrent = $parsedBody['recurrent'];
  $icon = $parsedBody['icon'];
  $iconSecondary = $parsedBody['iconSecondary'];
  $title = $parsedBody['title'];
  $time = $parsedBody['time'];
  $vigente = $parsedBody['vigente'];

  
  $sql = "INSERT INTO offers (userId,date,duree,price,recurrent,icon,iconSecondary,title,time,vigente) VALUES (:userId,:date,:duree,:price,:recurrent,:icon,:iconSecondary,:title,:time,:vigente)";

  try{
    $db = new db();
    $db = $db->conectDB();
    $resultado = $db->prepare($sql);

    $resultado->bindParam(':userId', $userId);
    $resultado->bindParam(':date', $date);
    $resultado->bindParam(':duree', $duree);
    $resultado->bindParam(':price', $price);
    $resultado->bindParam(':recurrent', $recurrent);
    $resultado->bindParam(':icon', $icon);
    $resultado->bindParam(':iconSecondary', $iconSecondary);
    $resultado->bindParam(':title', $title);
    $resultado->bindParam(':time', $time);
    $resultado->bindParam(':vigente', $vigente);


    $resultado->execute();
    $response->getBody()->write( json_encode( array("code" => 200, "message" => "new offers saved."))  );
    $resultado = null;
    $db = null;
    return $response;
    
  }catch(PDOException $e){
    $response->getBody()->write( json_encode( array("code" => 500, 'message' => $e->getMessage() ))  );
    return $response;
  }
}); 



// Update offers 
$app->put('/api/offers/{id}', function(Request $request, Response $response){
  $parsedBody = $request->getParsedBody();
  $id = $request->getAttribute('id');
  $date = $parsedBody['date'];
  $duree = $parsedBody['duree'];
  $price = $parsedBody['price'];
  $recurrent = $parsedBody['recurrent'];
  $icon = $parsedBody['icon'];
  $iconSecondary = $parsedBody['iconSecondary'];
  $title = $parsedBody['title'];
  $time = $parsedBody['time'];
  $vigente = $parsedBody['vigente'];

  
  $sql = "UPDATE offers SET date = :date,duree = :duree,price = :price,recurrent = :recurrent,icon = :icon,iconSecondary = :iconSecondary,title = :title,time = :time,vigente = :vigente WHERE id = " . $id;
     
  try{
    $db = new db();
    $db = $db->conectDB();
    $resultado = $db->prepare($sql);

    $resultado->bindParam(':date', $date);
    $resultado->bindParam(':duree', $duree);
    $resultado->bindParam(':price', $price);
    $resultado->bindParam(':recurrent', $recurrent);
    $resultado->bindParam(':icon', $icon);
    $resultado->bindParam(':iconSecondary', $iconSecondary);
    $resultado->bindParam(':title', $title);
    $resultado->bindParam(':time', $time);
    $resultado->bindParam(':vigente', $vigente);


    $resultado->execute();
    $response->getBody()->write( json_encode( array("code" => 200, "message" => "offers modified.")) );
    $resultado = null;
    $db = null;
    return $response;

  }catch(PDOException $e){
    $response->getBody()->write( json_encode( array("code" => 500, 'message' => $e->getMessage() ))  );
    return $response;
  }
}); 


// DELETE offers 
$app->delete('/api/offers/{id}', function(Request $request, Response $response){
   $id = $request->getAttribute('id');
   $sql = "DELETE FROM offers WHERE id = " . $id;
     
  try{
    $db = new db();
    $db = $db->conectDB();
    $resultado = $db->prepare($sql);
    $resultado->execute();

    if ($resultado->rowCount() > 0) {
        $response->getBody()->write( json_encode( array("code" => 200, "message" => "offers deleted.")) );  
    }else {
        $response->getBody()->write( json_encode( array("code" => 400, "message" => "offers with this ID doesn't exist.")) );
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
//"date": ""
//"duree": ""
//"price": ""
//"recurrent": ""
//"icon": ""
//"iconSecondary": ""
//"title": ""
//"time": ""
//"vigente": ""//
//}
