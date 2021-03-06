<?php

use Greenter\XMLSecLibs\Certificate\X509Certificate;
use Greenter\XMLSecLibs\Certificate\X509ContentType;
//
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
//
//
require '../../compass/vendor/autoload.php';
include '../config/database.php';
include '../upload/thing.php';

$folder = $_GET['folder'];
$emp_id = $_GET['emp_id'];
$passwordCertificado = $_GET['djqw231asd'];

$database = new Database();
$db = $database->getConnection($folder);
$ti = new Thing($db);

if (isset($_FILES['uploadedFile']) && $_FILES['uploadedFile']['error'] === UPLOAD_ERR_OK) {
    // get details of the uploaded file
    $fileTmpPath = $_FILES['uploadedFile']['tmp_name'];
    $fileName = $_FILES['uploadedFile']['name'];
    $fileSize = $_FILES['uploadedFile']['size'];
    $fileType = $_FILES['uploadedFile']['type'];
    $fileNameCmps = explode(".", $fileName);
    $fileExtension = strtolower(end($fileNameCmps));
    $newFileName = md5(time() . $fileName) . '.' . $fileExtension;


    $allowedfileExtensions = array('pfx', 'p12');
    if (in_array($fileExtension, $allowedfileExtensions)) {

        //verificar si existe el direcctorio de los certificados


        // directory in which the uploaded file will be moved
        $uploadFileDir = './certificados/' . $folder . "/";

        if (file_exists($uploadFileDir)) {
        } else {
            mkdir($uploadFileDir, 0777, TRUE);
        }


        $dest_path = $uploadFileDir . $newFileName;

        $db = $database->getConnection($folder);
        $ti = new Thing($db);





        if (move_uploaded_file($fileTmpPath, $dest_path)) {


            //update ruta del certificado 

            $pfx = file_get_contents($dest_path);
            if ($certificate = new X509Certificate($pfx, $passwordCertificado)) {
                $pem = $certificate->export(X509ContentType::PEM);
                file_put_contents($uploadFileDir . 'certificate.pem', $pem);
                if ($ti->updateUrlCertificado($folder, $uploadFileDir . 'certificate.pem', $emp_id, $passwordCertificado)) {
                    echo json_encode(array("status" => "success", "message" => "Se actualizo el Certificado"));
                } else {
                    echo json_encode(array("status" => "error", "message" => "fallo al actulizacion de la ruta o contaseña"));
                }
            } else {
                echo json_encode(array("status" => "error", "message" => "La Clave del Certificado no es Correcta"));
            }
        } else {
        }


        /*  if (move_uploaded_file($fileTmpPath, $dest_path)) {
                   $message = 'File is successfully uploaded.';
               } else {
                   $message = 'There was some error moving the file to upload directory. Please make sure the upload directory is writable by web server.';
               } */
    } else {
        echo json_encode(array("status" => "error", "message" => "Este Archivo no es Valido por Favor usa un .PFX ó P12"));
    }
} else {
    echo json_encode(array("status" => "error", "message" => "No se pudo leer El certificado Error: 101"));
}
