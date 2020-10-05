<?php
// required header
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
// include database and object files
include_once '../config/database.php';
include_once '../objects/compteepargne.php';
 
// instantiate database and category object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$compte = new compteepargne($db);
 
// query compte
$stmt = $compte->read();
$num = $stmt->rowCount();
 
// check if more than 0 record found
if($num>0){
 
    // compte s array
    $comptes_arr=array();
    $comptes_arr["records"]=array();
 
    // retrieve our table contents
    // fetch() is faster than fetchAll()
    // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
 
        $compte_item=array(
            "id" => $id,
            "num_compte" => $num_compte,
            "cle_rib" => $cle_rib,
            "duree_epargne" => $duree_epargne,
            "frais" => $frais,
            "solde" => $solde
        );
 
        array_push($comptes_arr["records"], $compte_item);
    }
 
    // set response code - 200 OK
    http_response_code(200);
 
    // show comptes data in json format
    echo json_encode($comptes_arr);
}
 
else{
 
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user no comptes found
    echo json_encode(
        array("message" => "No comptes found.")
    );
}
?>