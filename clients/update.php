<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// On vérifie que la méthode utilisée est correcte
if($_SERVER['REQUEST_METHOD'] == 'PUT'){
    // On inclut les fichiers de configuration et d'accès aux données
    include_once '../config/database.php';
    include_once '../objects/clientparticulier.php';
    
    // On instancie la base de données
    $database = new Database();
    $db = $database->getConnection();
    
    // prepare client object
    $client = new clientparticulier($db);
    
    // get id of client to be edited
    $data = json_decode(file_get_contents("php://input"));
    
    // set ID property of client to be edited
    $client->id = $data->id;
    
    // set client property values
    $client->nom = $data->nom;
    $client->prenom = $data->prenom;
    $client->telephone = $data->telephone;
    $client->adresse = $data->adresse;
    $client->email = $data->email;
    $client->cin = $data->cin;
    $client->validite_cin = $data->validite_cin;
    $client->activite = $data->activite;
    
    // update the client
    if($client->update()){
    
        // set response code - 200 ok
        http_response_code(200);
    
        // tell the user
        echo json_encode(array("message" => "client was updated."));
    }
    
    // if unable to update the client, tell the user
    else{
    
        // set response code - 503 service unavailable
        http_response_code(503);
    
        // tell the user
        echo json_encode(array("message" => "Unable to update client."));
    }
} else{
    // On gère l'erreur
    http_response_code(405);
    echo json_encode(["message" => "La méthode n'est pas autorisée"]);
}
?>