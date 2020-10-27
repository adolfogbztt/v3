<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// include database and object files
include '../../config/database.php';
include '../tipo_documento/tipo_documento.php';
 
// get database connection
$database = new Database();
$getdb=$_GET['getdb'];
$db = $database->getConnection($getdb);
  
// prepare tipo_documento object
$tipo_documento = new Tipodocumento($db);
 
// get tipo_documento id
$data = json_decode(file_get_contents("php://input"));

// set tipo_documento id to be deleted
$tipo_documento->td_id = $data->td_id;
 
// delete the tipo_documento
if($tipo_documento->delete($getdb)){
 
    // set response code - 200 ok
    http_response_code(200);
 
    // tell the user
    echo json_encode(array("message" => "tipo_documento was deleted."));
}
 
// if unable to delete the tipo_documento
else{
 
    // set response code - 503 service unavailable
    http_response_code(503);
 
    // tell the user
    echo json_encode(array("message" => "Unable to delete tipo_documento."));
}
?>