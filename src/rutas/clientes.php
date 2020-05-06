<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app = new \Slim\App;

$app->get('/hello/{name}', function ($request, $response, $args) {
    return $response->write("Hello, " . $args['name']);
});


$app->get('/pacientes', function ($request, $response, $args) {
    include("../Policlinico/pacientes.html");
});




// GET Todos los clientes 
$app->get('/api/pacientes', function(Request $request, Response $response){
  $sql = "SELECT * FROM pacientes";
  try{
    $db = new db();
    $db = $db->conectDB();
    $resultado = $db->query($sql);

    if ($resultado->rowCount() > 0){
      $pacientes = $resultado->fetchAll(PDO::FETCH_OBJ);
      echo json_encode($pacientes);
    }else {
      echo json_encode("No existen pacientes en la BBDD.");
    }
    $resultado = null;
    $db = null;
  }catch(PDOException $e){
    echo '{"error" : {"text":'.$e->getMessage().'}';
  }
}); 

// GET Recueperar cliente por ID 
 $app->get('/api/pacientes/{id}', function(Request $request, Response $response){
  $id_pacientes = $request->getAttribute('id');
  $sql = "SELECT * FROM pacientes WHERE id_pacientes = $id_pacientes";
  try{
    $db = new db();
    $db = $db->conectDB();
    $resultado = $db->query($sql);

    if ($resultado->rowCount() > 0){
      $pacientes = $resultado->fetchAll(PDO::FETCH_OBJ);
      echo json_encode($pacientes);
    }else {
      echo json_encode("No existen cliente en la BBDD con este ID.");
    }
    $resultado = null;
    $db = null;
  }catch(PDOException $e){
    echo '{"error" : {"text":'.$e->getMessage().'}';
  }
});


// POST Crear nuevo cliente 
   $app->post('/api/pacientes/nuevo', function(Request $request, Response $response){
   $nombre = $request->getParam('nombre');
   $apellidos = $request->getParam('apellidos');
   $sexo = $request->getParam('sexo');
   $direccion = $request->getParam('direccion');
   $fecha_nacimiento = $request->getParam('fecha_nacimiento');
   $dni = $request->getParam('dni') ;
   $telefono = $request->getParam('telefono');
  
   
  
   $sql = "INSERT INTO pacientes (nombre, apellidos, sexo, direccion, fecha_nacimiento, dni, telefono) VALUES 
       (:nombre, :apellidos, :sexo, :direccion, :fecha_nacimiento, :dni, :telefono)";
  try{
   $db = new db();
    $db = $db->conectDB();
    $resultado = $db->prepare($sql);

    $resultado->bindParam(':nombre', $nombre);
    $resultado->bindParam(':apellidos', $apellidos);
    $resultado->bindParam(':sexo', $sexo);
    $resultado->bindParam(':direccion', $direccion);
    $resultado->bindParam(':fecha_nacimiento', $fecha_nacimiento);
    $resultado->bindParam(':dni', $dni);
    $resultado->bindParam(':telefono', $telefono);
    
    
      
    $resultado->execute();
    echo json_encode("Nuevo pacientes Registrado.");  

    $resultado = null;
    $db = null;
  }catch(PDOException $e){
  echo '{"error" : {"text":'.$e->getMessage().'}';
  }
}); 



// PUT Modificar cliente 
$app->put('/api/pacientes/modificar/{id}', function(Request $request, Response $response){
   $id_pacientes = $request->getAttribute('id');
   $nombre = $request->getParam('nombre');
   $apellidos = $request->getParam('apellidos');
   $sexo = $request->getParam('sexo');
   $direccion = $request->getParam('direccion');
   $fecha_nacimiento = $request->getParam('fecha_nacimiento');
   $dni = $request->getParam('dni') ;
   $telefono = $request->getParam('telefono');

  
  $sql = "UPDATE pacientes SET
          nombre = :nombre,
          apellidos = :apellidos,
          sexo = :sexo,
          direccion = :direccion,
          fecha_nacimiento = :fecha_nacimiento,
          dni = :dni,
          telefono = :telefono
        WHERE id_pacientes = $id_pacientes";
     
  try{
    $db = new db();
    $db = $db->conectDB();
    $resultado = $db->prepare($sql);

    $resultado->bindParam(':nombre', $nombre);
    $resultado->bindParam(':apellidos', $apellidos);
    $resultado->bindParam(':sexo', $sexo);
    $resultado->bindParam(':direccion', $direccion);
    $resultado->bindParam(':fecha_nacimiento', $fecha_nacimiento);
    $resultado->bindParam(':dni', $dni);
    $resultado->bindParam(':telefono', $telefono);


    $resultado->execute();
    echo json_encode("Cliente modificado.");  

    $resultado = null;
    $db = null;
  }catch(PDOException $e){
    echo '{"error" : {"text":'.$e->getMessage().'}';
  }
}); 


// DELETE borar cliente 
$app->delete('/api/pacientes/eliminar/{id}', function(Request $request, Response $response){
   $id_pacientes = $request->getAttribute('id');
   $sql = "DELETE FROM pacientes WHERE id_pacientes = $id_pacientes";
     
  try{
    $db = new db();
    $db = $db->conectDB();
    $resultado = $db->prepare($sql);
     $resultado->execute();

    if ($resultado->rowCount() > 0) {
      echo json_encode("Paciente eliminado.");  
    }else {
      echo json_encode("No existe pacientes con este ID.");
    }

    $resultado = null;
    $db = null;
  }catch(PDOException $e){
    echo '{"error" : {"text":'.$e->getMessage().'}';
  }
}); 







