<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
// include database and object files
include_once '../../config/core.php';
include_once '../../config/database.php';
include_once '../mantenimiento/mantenimiento.php';
include '../mantenimiento/fields.php';
 
// instantiate database and mantenimiento object
$database = new Database();
$getdb=$_GET["getdb"];//nombre de la base de datos donde se van a buscar los items
$tbnom=$_GET["tbnom"];//nombre de la tabla 
$keywords=$_GET["s"];// palabra clave que se buscara
$key=$_GET["key"];// la columna  de que se esta buscando el $keyword 
$where=$_GET["where"];// la palabra clave de que se esta buscando 
$igual=$_GET["igual"];// la palabra clave de que se esta buscando 
$where2=$_GET["where2"];// la palabra clave de que se esta buscando 
$igual2=$_GET["igual2"];// la palabra clave de que se esta buscando 
$db = $database->getConnection($getdb);
 
// initialize object

$field = new Fields($db,$tbnom);
$mantenimiento = new Mantenimiento($db);
 

// get keywords
 
// query mantenimiento
$field = $field->fields($getdb,$tbnom);
$numfield = $field->rowCount();
$stmt = $mantenimiento->search3($keywords,$key,$getdb,$tbnom,$where,$igual,$where2,$igual2);
$num = $stmt->rowCount();
$arr_field = array();
while ($row = $field->fetch(PDO::FETCH_ASSOC)) {
    array_push($arr_field,$row['Field']);
}

// check if more than 0 record found
if($num>0){
 
    // empresa array
    $mantenimento_arr=array();
    
    $mantenimento_arr['fields']=array();

    array_push($mantenimento_arr['fields'],$arr_field);

    $mantenimento_arr['data']=array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        
        array_push($mantenimento_arr['data'],$row);
    }
 
    // set response code - 200 OK
    http_response_code(200);
 
    // show empresa data in json format
    echo json_encode($mantenimento_arr);
}
 
else{
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user no mantenimiento found
    echo json_encode(
        array("message" => "No mantenimiento found.")
    );
}
?>