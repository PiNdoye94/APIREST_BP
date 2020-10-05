<?php
class compteepargne{
 
    // database connection and table name
    private $conn;
    private $table_name = "compteepargne";
 
    // object properties
    public $id;
    public $num_agence;
    public $duree_epargne;
    public $frais;
    public $solde;
    public $created;
 
    public function __construct($db){
        $this->conn = $db;
    }
 
    // used by select drop-down list
    public function readAll(){
        //select all data
        $query = "SELECT
                    id, num_compte, cle_rib, num_agence, duree_epargne, frais, solde, created
                FROM
                    " . $this->table_name . "
                ORDER BY
                    num_compte";
 
        $stmt = $this->conn->prepare( $query );
        $stmt->execute();
 
        return $stmt;
    }
    // used by select drop-down list
    public function read(){
    
        //select all data
        $query = "SELECT
                    id, id, num_compte, cle_rib, duree_epargne, frais, solde, created
                FROM
                    " . $this->table_name . "
                ORDER BY
                    created";
    
        $stmt = $this->conn->prepare( $query );
        $stmt->execute();
    
        return $stmt;
    }
}
?>