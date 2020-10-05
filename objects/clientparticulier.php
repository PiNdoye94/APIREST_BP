<?php
class clientparticulier{
  
    // database connection and table name
    private $conn;
    private $table_name = "clientparticulier";
  
    // object properties
    public $id;
    public $nom;
    public $prenom;
    public $telephone;
    public $adresse;
    public $email;
    public $cin;
    public $validite_cin;
    public $activite;
    public $created;
  
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }
    // create product
    function create(){
    
        // query to insert record
        $query = "INSERT INTO
                    " . $this->table_name . "
                SET
                    nom=:nom, prenom=:prenom, telephone=:telephone, adresse=:adresse, email=:email, cin=:cin, validite_cin=:validite_cin, activite=:activite, created=:created";
        
        // prepare query
        $stmt = $this->conn->prepare($query);
        
        // sanitize
        $this->nom=htmlspecialchars(strip_tags($this->nom));
        $this->prenom=htmlspecialchars(strip_tags($this->prenom));
        $this->telephone=htmlspecialchars(strip_tags($this->telephone));
        $this->adresse=htmlspecialchars(strip_tags($this->adresse));
        $this->email=htmlspecialchars(strip_tags($this->email));
        $this->cin=htmlspecialchars(strip_tags($this->cin));
        $this->validite_cin=htmlspecialchars(strip_tags($this->validite_cin));
        $this->activite=htmlspecialchars(strip_tags($this->activite));
        $this->created=htmlspecialchars(strip_tags($this->created));
    
        // bind values
        $stmt->bindParam(":nom", $this->nom);
        $stmt->bindParam(":prenom", $this->prenom);
        $stmt->bindParam(":telephone", $this->telephone);
        $stmt->bindParam(":adresse", $this->adresse);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":cin", $this->cin);
        $stmt->bindParam(":validite_cin", $this->validite_cin);
        $stmt->bindParam(":activite", $this->activite);
        $stmt->bindParam(":created", $this->created);
        
        // execute query
        if($stmt->execute()){
            return true;
        }
    
        return false;
        
    }
    // read client
    function read(){
    
        // select all query
        $query = "SELECT
                    c.id, c.nom, c.prenom, c.telephone, c.adresse, c.email, c.cin, c.validite_cin, c.activite, c.created
                FROM
                    " . $this->table_name . " c
                ORDER BY
                    c.created DESC";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }
    // used when filling up the update product form
    function readOne(){
    
        // query to read single record
        $query = "SELECT
                    c.id, c.nom, c.prenom, c.telephone, c.adresse, c.email, c.cin, c.validite_cin, c.activite, c.created
                FROM
                    " . $this->table_name . " c
                WHERE
                    c.id = ?
                LIMIT
                    0,1";
    
        // prepare query statement
        $stmt = $this->conn->prepare( $query );
    
        // bind id of client to be updated
        $stmt->bindParam(1, $this->id);
    
        // execute query
        $stmt->execute();
    
        // get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
        // set values to object properties
        $this->nom = $row['nom'];
        $this->prenom = $row['prenom'];
        $this->telephone = $row['telephone'];
        $this->adresse = $row['adresse'];
        $this->email = $row['email'];
        $this->cin = $row['cin'];
        $this->validite_cin = $row['validite_cin'];
        $this->activite = $row['activite'];
    }
    // update the product
    function update(){
    
        // update query
        $query = "UPDATE
                    " . $this->table_name . "
                SET
                    nom = :nom,
                    prenom = :prenom,
                    telephone = :telephone,
                    adresse = :adresse,
                    email = :email,
                    cin = :cin,
                    validite_cin = :validite_cin,
                    activite = :activite
                WHERE
                    id = :id";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->nom=htmlspecialchars(strip_tags($this->nom));
        $this->prenom=htmlspecialchars(strip_tags($this->prenom));
        $this->telephone=htmlspecialchars(strip_tags($this->telephone));
        $this->adresse=htmlspecialchars(strip_tags($this->adresse));
        $this->email=htmlspecialchars(strip_tags($this->email));
        $this->cin=htmlspecialchars(strip_tags($this->cin));
        $this->validite_cin=htmlspecialchars(strip_tags($this->validite_cin));
        $this->activite=htmlspecialchars(strip_tags($this->activite));
        $this->id=htmlspecialchars(strip_tags($this->id));
    
        // bind new values
        $stmt->bindParam(':nom', $this->nom);
        $stmt->bindParam(':prenom', $this->prenom);
        $stmt->bindParam(':telephone', $this->telephone);
        $stmt->bindParam(':adresse', $this->adresse);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':cin', $this->cin);
        $stmt->bindParam(':validite_cin', $this->validite_cin);
        $stmt->bindParam(':activite', $this->activite);
        $stmt->bindParam(':id', $this->id);
    
        // execute the query
        if($stmt->execute()){
            return true;
        }
    
        return false;
    }
    // delete the product
    function delete(){
    
        // delete query
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
    
        // prepare query
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->id=htmlspecialchars(strip_tags($this->id));
    
        // bind id of record to delete
        $stmt->bindParam(1, $this->id);
    
        // execute query
        if($stmt->execute()){
            return true;
        }
    
        return false;
    }
    // search products
    function search($keywords){
    
        // select all query
        $query = "SELECT
                    tc.type_compte as compte_type, c.id, c.nom, c.prenom, c.telephone, c.adresse, c.created
                FROM
                    " . $this->table_name . " c
                    LEFT JOIN
                         compte tc
                            ON tp.compte_id = c.id
                WHERE
                    c.nom LIKE ? OR c.prenom LIKE ? OR tc.tpy_compte LIKE ?
                ORDER BY
                    c.created DESC";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $keywords=htmlspecialchars(strip_tags($keywords));
        $keywords = "%{$keywords}%";
    
        // bind
        $stmt->bindParam(1, $keywords);
        $stmt->bindParam(2, $keywords);
        $stmt->bindParam(3, $keywords);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }
    // read products with pagination
    public function readPaging($from_record_num, $records_per_page){
    
        // select query
        $query = "SELECT
                    tc.type_compte as compte_type, c.id, c.nom, c.prenom, c.telephone, c.adresse, c.created
                FROM
                    " . $this->table_name . " c
                    LEFT JOIN
                        compte tc
                            ON tp.compte_id = c.id
                ORDER BY c.created DESC
                LIMIT ?, ?";
    
        // prepare query statement
        $stmt = $this->conn->prepare( $query );
    
        // bind variable values
        $stmt->bindParam(1, $from_record_num, PDO::PARAM_INT);
        $stmt->bindParam(2, $records_per_page, PDO::PARAM_INT);
    
        // execute query
        $stmt->execute();
    
        // return values from database
        return $stmt;
    }
    // used for paging products
    public function count(){
        $query = "SELECT COUNT(*) as total_rows FROM " . $this->table_name . "";
    
        $stmt = $this->conn->prepare( $query );
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
        return $row['total_rows'];
    }

}
?>