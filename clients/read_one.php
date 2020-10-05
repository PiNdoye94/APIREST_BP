<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

// On vérifie que la méthode utilisée est correcte
if($_SERVER['REQUEST_METHOD'] == 'GET'){
    // On inclut les fichiers de configuration et d'accès aux données
    include_once '../config/database.php';
    include_once '../objects/clientparticulier.php';
 
    // On instancie la base de données
    $database = new Database();
    $db = $database->getConnection();
    
    // On instancie l'objet client
    $client = new clientparticulier($db);
    
    // set ID property of record to read
    $client->id = isset($_GET['id']) ? $_GET['id'] : die();
    
    // read the details of client to be edited
    $client->readOne();
    
    if($client->nom!=null){
        // create array
        $client_arr = array(
            "id" =>  $client->id,
            "nom" => $client->nom,
            "prenom" => $client->prenom,
            "telephone" => $client->telephone,
            "adresse" => $client->adresse,
            "email" => $client->email,
            "cin" => $client->cin,
            "validite_cin" => $client->validite_cin
    
        );
    
        // set response code - 200 OK
        http_response_code(200);
    
        // make it json format
        echo json_encode($client_arr);
    }
    
    else{
        // set response code - 404 Not found
        http_response_code(404);
    
        // tell the user client does not exist
        echo json_encode(array("message" => "Client does not exist."));
    }
} else{
    // On gère l'erreur
    http_response_code(405);
    echo json_encode(["message" => "La méthode n'est pas autorisée"]);
}
?>