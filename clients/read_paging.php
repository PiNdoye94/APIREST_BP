<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
// include database and object files
include_once '../config/core.php';
include_once '../shared/utilities.php';
include_once '../config/database.php';
include_once '../objects/product.php';
 
// utilities
$utilities = new Utilities();
 
// instantiate database and client object
$database = new Database();
$db = $database->getConnection();
 
// initialize client
$client = new clientparticulier($db);
 
// query clients
$stmt = $client->readPaging($from_record_num, $records_per_page);
$num = $stmt->rowCount();
 
// check if more than 0 record found
if($num>0){
 
    // clients array
    $clients_arr=array();
    $clients_arr["records"]=array();
    $clients_arr["paging"]=array();
 
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
            "prenom" => $prenom,
            "telephone" => $telephone,
            "adresse" => $adresse,
            "email" => $email
        );
 
        array_push($clients_arr["records"], $client_item);
    }
 
 
    // include paging
    $total_rows=$client->count();
    $page_url="{$home_url}product/read_paging.php?";
    $paging=$utilities->getPaging($page, $total_rows, $records_per_page, $page_url);
    $clients_arr["paging"]=$paging;
 
    // set response code - 200 OK
    http_response_code(200);
 
    // make it json format
    echo json_encode($clients_arr);
}
 
else{
 
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user clients does not exist
    echo json_encode(
        array("message" => "No clients found.")
    );
}
?>