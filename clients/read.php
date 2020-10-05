<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

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
    
    // read clients will be here
    // query client
    $stmt = $client->read();
    $num = $stmt->rowCount();
    
    // check if more than 0 record found
    if($num>0){
    
        // clients array
        $clients_arr=array();
        $clients_arr["records"]=array();
    
        // retrieve our table contents
        // fetch() is faster than fetchAll()
        // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            // extract row
            // this will make $row['name'] to
            // just $name only
            extract($row);
    
            $client_item=array(
                "id" => $id,
                "nom" => $nom,
                "prenom" => html_entity_decode($prenom),
                "telephone" => $telephone,
                "adresse" => $adresse,
                "email" => $email,
                "cin" => $cin,
                "validite_cin" => $validite_cin
            );
    
            array_push($clients_arr["records"], $client_item);
        }
    
        // set response code - 200 OK
        http_response_code(200);
    
        // show clients data in json format
        echo json_encode($clients_arr);
    }
    
    // no products found will be here
    else{
    
        // set response code - 404 Not found
        http_response_code(404);
    
        // tell the user no clients found
        echo json_encode(
            array("message" => "No client found.")
        );
    }
} else{
    // On gère l'erreur
    http_response_code(405);
    echo json_encode(["message" => "La méthode n'est pas autorisée"]);
}