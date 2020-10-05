<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// On vérifie que la méthode utilisée est correcte
if($_SERVER['REQUEST_METHOD'] == 'DELETE'){
    // On inclut les fichiers de configuration et d'accès aux données
    include_once '../config/database.php';
    include_once '../objects/clientparticulier.php';
    
    // On instancie la base de données
    $database = new Database();
    $db = $database->getConnection();
    
    // On instancie objet client
    $client = new clientparticulier($db);
    
    // recupération de l'id du client
    $data = json_decode(file_get_contents("php://input"));
    $client->id = $data->id;
    
    // suppression du client
    if($client->delete()){
    
        // réponse avec le code - 200 ok
        http_response_code(200);
    
        // message de confirmation de la suppression
        echo json_encode(array("message" => "client was deleted."));
    }
    
    else{
    
        // réponse code - 503 service indisponible
        http_response_code(503);
    
        // message du code 503
        echo json_encode(array("message" => "Unable to delete client."));
    }
} else{
    // On gère l'erreur sur la méthode utilisée
    http_response_code(405);
    echo json_encode(["message" => "La méthode n'est pas autorisée"]);
}

?>