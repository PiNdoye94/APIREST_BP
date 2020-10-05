<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 

// On vérifie que la méthode utilisée est correcte
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    // On inclut les fichiers de configuration et d'accès aux données
    include_once '../config/database.php';
    include_once '../objects/clientparticulier.php';
    
    // On instancie la base de données
    $database = new Database();
    $db = $database->getConnection();
    
    $client = new clientparticulier($db);
    
    // recupération des données
    $data = json_decode(file_get_contents("php://input"));
    
    // vérifier que les données ne sont pas vide
    if(
        !empty($data->nom) &&
        !empty($data->prenom) &&
        !empty($data->telephone) &&
        !empty($data->adresse) &&
        !empty($data->email) &&
        !empty($data->cin) &&
        !empty($data->validite_cin) &&
        !empty($data->activite)
    ){
    
        // On modifie la valeur des proprièté
        $client->nom = $data->nom;
        $client->prenom = $data->prenom;
        $client->telephone = $data->telephone;
        $client->adresse = $data->adresse;
        $client->email = $data->email;
        $client->cin = $data->cin;
        $client->validite_cin = $data->validite_cin;
        $client->activite = $data->activite;
        $client->created = date('Y-m-d H:i:s');
        // var_dump($client);
        // die;
        // create the client
        if($client->create()){
            
            // réponse 201 si le client a été créé
            http_response_code(201);
    
            // message de confirmation de l'ajout
            echo json_encode(array("message" => "client was created."));
        }
    
        // if unable to create the client, tell the user
        else{
    
            //réponse code 503 s'il n'y a pas ajout
            http_response_code(503);
    
            // message du réponse 503
            echo json_encode(array("message" => "Unable to create client."));
        }
    }
    
    // tell the user data is incomplete
    else{
    
        // code 400 pour erreur de requete
        http_response_code(400);
    
        // message erreur 400
        echo json_encode(array("message" => "Données incomplètes."));
    }
} else{
    // On gère l'erreur sur la méthode utilisée
    http_response_code(405);
    echo json_encode(["message" => "La méthode n'est pas autorisée"]);
}
?>