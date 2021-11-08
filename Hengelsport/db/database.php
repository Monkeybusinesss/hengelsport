  
<?php
class DB{
    private $host;
    private $user;
    private $pass;
    private $db;
    private $charset;
    private $pdo;

    public function __construct($host, $user, $pass, $db, $charset){
        $this->host = $host;
        $this->user = $user;
        $this->pass = $pass;
        $this->db = $db;
        $this->charset = $charset; 
        
        try{
            $dsn = 'mysql:host='. $this->host.';dbname='.$this->db.';charset='.$this->charset;
            $this->pdo = new PDO($dsn, $this->user, $this->pass);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $this->pdo;
        }
        catch(\PDOException $e){
            echo "Connection Failed: ".$e->getMessage();
        }
      
    }

    public function login($username, $password){
        try {
            $sql = "SELECT * FROM medewerker WHERE gebruikersnaam = :gebruikersnaam;";

            $query = $this->pdo->prepare($sql);

            $query->execute([
                'gebruikersnaam' => $username
            ]);

            $row = $query->fetch(PDO::FETCH_ASSOC);

            if ($row > 0) {
                $verify = password_verify($password, $row['wachtwoord']);

                if ($verify) {
                    session_start();
                    
                    $_SESSION['row'] = $row;
                    //Als de role_id gelijk  is aan 1  is , is de gebruiker een medewerker.
                    if ($row['role_id'] == 1) {
                        header('Location: ../views/medewerker/');
                    }else{
                        header('Location: ../views/admin/');
                    }

                }
                //Bij verkeerde logins worden de error messages getoond
                else{
                    $message = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'.'Invalid Gebruikersnaam of Wachtwoord' .'</div>';
                }
            }
            else{
                $message = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'.'Invalid Gebruikersnaam of Wachtwoord' .'</div>';
            }

            return $this->message = $message;
        }    
        catch (PDOException $e) {
            /* Recognize mistake and roll back changes */
            $this->pdo->rollback();
            
            throw $e;
        }
    }

    //functie die word gebruikt voor de overzicht voorrraad
    public function show_voorraad(){
        try {
            $sql = "SELECT voorraad.id AS ID, product.id AS productID,  locatie.id as locatieID,
                    aantal, locatie, product.naam AS naam, leverancier.naam AS leverancierNaam, 
                    leverancier.id AS leverancierID, type, inkoopprijs, verkoopprijs 
                    FROM voorraad 
                    INNER JOIN locatie ON locatie_id = locatie.ID 
                    INNER JOIN product ON product_id = product.ID
                    INNER JOIN leverancier ON product.leverancier_id = leverancier.ID
                    ORDER BY ID;";
                
            $query = $this->pdo->prepare($sql);

            $query->execute();

            $rows = $query->fetchAll(PDO::FETCH_ASSOC);

            $this->rows = $rows;
        } 
        catch (PDOException $e) {
            /* Recognize mistake and roll back changes */
            $this->pdo->rollback();
            
            throw $e;
        }
    }

    //functie die word gebruikt voor de overzicht locatie
    public function show_locatieAll(){
        try {
            $sql = "SELECT * FROM locatie;";
                
            $query = $this->pdo->prepare($sql);

            $query->execute();

            $rows = $query->fetchAll(PDO::FETCH_ASSOC);

            return $this->rows = $rows;
        } 
        catch (PDOException $e) {
            /* Recognize mistake and roll back changes */
            $this->pdo->rollback();
            
            throw $e;
        }
    }

    //functie die word gebruikt voor de overzicht fabriek/leverancier
    public function show_leverancierAll(){
        try {
            $sql = "SELECT * FROM leverancier;";
                
            $query = $this->pdo->prepare($sql);

            $query->execute();

            $rows = $query->fetchAll(PDO::FETCH_ASSOC);

            return $this->rows = $rows;
        } 
        catch (PDOException $e) {
            /* Recognize mistake and roll back changes */
            $this->pdo->rollback();
            
            throw $e;
        }
    }

    //functie die word gebruikt om een product toe te voegen aan voorraad
    public function add_product($leverancierID, $locatie, $product, $type, $inkoopprijs, $verkoopprijs, $aantal){
        try {
            /* Begin a transaction, turning off autocommit */
            $this->pdo->beginTransaction(); 

            $sql2 = "INSERT INTO product(ID, naam, type, leverancier_id, inkoopprijs, verkoopprijs)
                     VALUES(NULL, :naam, :type, :leverancier_id, :inkoopprijs, :verkoopprijs);";

            $query2 = $this->pdo->prepare($sql2);

            $query2->execute([
                'naam' => $product,
                'type' => $type,
                'leverancier_id' => $leverancierID,
                'inkoopprijs' => $inkoopprijs,
                'verkoopprijs' => $verkoopprijs
            ]);

            $productID = $this->pdo->lastInsertId();

            $sql3 = "INSERT INTO voorraad(ID, locatie_id, product_id, aantal)
                     VALUES(NULL, :locatie_id, :product_id, :aantal);";

            $query3 = $this->pdo->prepare($sql3);

            $query3->execute([
                'locatie_id' => $locatie,
                'product_id' => $productID,
                'aantal' => $aantal
            ]);
            
            /* Commit the changes */
            $this->pdo->commit();

            /* Prevents that data is always added to the table during refresh */
            header("Location: voorraad.php");

            exit;
        } 
        catch (PDOException $e) {
            /* Recognize mistake and roll back changes */
            $this->pdo->rollback();
            
            throw $e;
        }
    }

    //functie die word gebruikt om een product in voorraad te verwijderen
    public function delete_voorraad($productID, $voorraadID){
        try{
            /* Begin a transaction, turning off autocommit */
            $this->pdo->beginTransaction(); 
            
            $sql1 = "DELETE FROM product WHERE ID = :id;";

            $query1 = $this->pdo->prepare($sql1);

            $query1->execute([
                'id' => $productID
            ]);

            $sql2 = "DELETE FROM voorraad WHERE ID = :id;";

            $query2 = $this->pdo->prepare($sql2);

            $query2->execute([
                'id' => $voorraadID
            ]);
            
            /* Commit the changes */
            $this->pdo->commit();

            /* Prevents that data is always added to the table during refresh */
            header("Location: voorraad.php");

            exit;
        }
        catch (PDOException $e) {
            /* Recognize mistake and roll back changes */
            $this->pdo->rollback();
            
            throw $e;
        }
    }

    //functie die word gebruikt om fabriek/leverancier te verwijderen
    public function delete_leverancier($id){
        try{
            $sqlcheck = "SELECT leverancier_id FROM product;";

            $querycheck = $this->pdo->prepare($sqlcheck);

            $querycheck->execute();

            $rowscheck = $querycheck->fetchAll(PDO::FETCH_ASSOC);

            $id_checker = [];

            foreach ($rowscheck as $row) {
                array_push($id_checker, $row['leverancier_id']);
            }

            if (in_array($id, $id_checker)) {
                $message = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'.'Deze fabriek bestaat in producten' .'</div>';
                return $this->message = $message;
            }
            /* Begin a transaction, turning off autocommit */
            $this->pdo->beginTransaction(); 
            
            $sql1 = "DELETE FROM leverancier WHERE ID = :id;";

            $query1 = $this->pdo->prepare($sql1);

            $query1->execute([
                'id' => $id
            ]);
   
            /* Commit the changes */
            $this->pdo->commit();

            /* Prevents that data is always added to the table during refresh */
            header("Location: fabriek.php");

            exit;
        }
        catch (PDOException $e) {
            /* Recognize mistake and roll back changes */
            $this->pdo->rollback();
            
            throw $e;
        }
    }

     //functie die word gebruikt om een fabriek/leverancier toe te voegen
    public function add_fabriek($naam, $telefoon){
        try {
            /* Begin a transaction, turning off autocommit */
            $this->pdo->beginTransaction(); 
           
            $sql = "INSERT INTO leverancier(ID, naam, telefoon)
                    VALUES(NULL, :naam, :telefoon);";

            $query = $this->pdo->prepare($sql);

            $query->execute([
                'naam' => $naam,
                'telefoon' => $telefoon
            ]);
           
            /* Commit the changes */
            $this->pdo->commit();

            /* Prevents that data is always added to the table during refresh */
            header("Location: fabriek.php");

            exit;
        }
        catch (PDOException $e) {
            /* Recognize mistake and roll back changes */
            $this->pdo->rollback();
            
            throw $e;
        }

    }

    //functie die word gebruikt in de edit page om een fabriek/leverancier te laten zien
    public function show_detailFabriek($id){
        try {
            $sql = "SELECT * FROM leverancier WHERE ID = :id;";
                
            $query = $this->pdo->prepare($sql);

            $query->execute([
                'id' => $id
            ]);

            $rows = $query->fetchAll(PDO::FETCH_ASSOC);

            return $this->rows = $rows;
        }  
        catch (PDOException $e) {
            /* Recognize mistake and roll back changes */
            $this->pdo->rollback();
            
            throw $e;
        }
    }

    //functie die word gebruikt om een  fabriek/leverancier te wijzigen 
    public function update_fabriek($naam, $telefoon, $id){
        try {
            /* Begin a transaction, turning off autocommit */
            $this->pdo->beginTransaction(); 
           
            $sql = "UPDATE leverancier
                    SET naam = :naam, telefoon = :telefoon
                    WHERE ID = :id;";

            $query = $this->pdo->prepare($sql);

            $query->execute([
                'naam' => $naam,
                'telefoon' => $telefoon,
                'id' => $id
            ]);
           
            /* Commit the changes */
            $this->pdo->commit();

            /* Prevents that data is always added to the table during refresh */
            header("Location: fabriek.php");

            exit;
        }
        catch (PDOException $e) {
            /* Recognize mistake and roll back changes */
            $this->pdo->rollback();
            
            throw $e;
        }

    }

    //functie die word gebruikt om een locatie toe te voegen
    public function add_locatie($naam){
        try {
            /* Begin a transaction, turning off autocommit */
            $this->pdo->beginTransaction(); 
           
            $sql = "INSERT INTO locatie(ID, locatie)
                    VALUES(NULL, :locatie);";

            $query = $this->pdo->prepare($sql);

            $query->execute([
                'locatie' => $naam
            ]);
           
            /* Commit the changes */
            $this->pdo->commit();

            /* Prevents that data is always added to the table during refresh */
            header("Location: locatie.php");

            exit;
        }
        catch (PDOException $e) {
            /* Recognize mistake and roll back changes */
            $this->pdo->rollback();
            
            throw $e;
        }

    }

    //functie die word gebruikt om de gekozen locatie te laten zien in de edit page
    public function show_detailLocatie($id){
        try {
            $sql = "SELECT * FROM locatie WHERE ID = :id;";
                
            $query = $this->pdo->prepare($sql);

            $query->execute([
                'id' => $id
            ]);

            $rows = $query->fetchAll(PDO::FETCH_ASSOC);

            return $this->rows = $rows;
        }  
        catch (PDOException $e) {
            /* Recognize mistake and roll back changes */
            $this->pdo->rollback();
            
            throw $e;
        }
    }

    //functie die word gebruikt om locatie te wijzigen
    public function update_locatie($naam, $id){
        try {
            /* Begin a transaction, turning off autocommit */
            $this->pdo->beginTransaction(); 
           
            $sql = "UPDATE locatie
                    SET locatie = :locatie
                    WHERE ID = :id;";

            $query = $this->pdo->prepare($sql);

            $query->execute([
                'locatie' => $naam,
                'id' => $id
            ]);
           
            /* Commit the changes */
            $this->pdo->commit();

            /* Prevents that data is always added to the table during refresh */
            header("Location: locatie.php");

            exit;
        }
        catch (PDOException $e) {
            /* Recognize mistake and roll back changes */
            $this->pdo->rollback();
            
            throw $e;
        }

    }

     //functie die word gebruikt om locatie te verwijderen
    public function destroy_locatie($id){
        try {
            $sqlcheck = "SELECT locatie_id FROM voorraad;";

            $querycheck = $this->pdo->prepare($sqlcheck);

            $querycheck->execute();

            $rowscheck = $querycheck->fetchAll(PDO::FETCH_ASSOC);

            $id_checker = [];

            foreach ($rowscheck as $row) {
                array_push($id_checker, $row['locatie_id']);
            }

            if (in_array($id, $id_checker)) {
                $message = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'.'Deze Locatie bestaat in Voorraad' .'</div>';
                return $this->message = $message;
            }

            /* Begin a transaction, turning off autocommit */
            $this->pdo->beginTransaction(); 
           
            $sql = "DELETE FROM locatie
                    WHERE ID = :id;";

            $query = $this->pdo->prepare($sql);

            $query->execute([
                'id' => $id
            ]);
           
            /* Commit the changes */
            $this->pdo->commit();
            
            /* Prevents that data is always added to the table during refresh */
            header("Location: locatie.php");

            exit;
        }
        catch (PDOException $e) {
            /* Recognize mistake and roll back changes */
            $this->pdo->rollback();
            
            throw $e;
        }

    }

    //functie die word gebruikt voor de overzicht medewerkers
    public function show_medewerkers(){
        try {
            $sql = "SELECT * FROM medewerker;";
                
            $query = $this->pdo->prepare($sql);

            $query->execute();

            $rows = $query->fetchAll(PDO::FETCH_ASSOC);

            return $this->rows = $rows;
        } 
        catch (PDOException $e) {
            /* Recognize mistake and roll back changes */
            $this->pdo->rollback();
            
            throw $e;
        }
    }

    //functie die word gebruikt om een medewerker toe te voegen
    public function add_medewerker($firstname, $middlename, $lastname, $email, $username, $password, $rol){
        try {
            //Hier fetch ik email + gebruikersnaam omdat ik wil checken of de inegevulde username of email al bestaan
            $sqlcheck = "SELECT email, gebruikersnaam FROM medewerker;";

            $querycheck = $this->pdo->prepare($sqlcheck);

            $querycheck->execute();

            $rowscheck = $querycheck->fetchAll(PDO::FETCH_ASSOC);

            $checker = [];
           
            foreach ($rowscheck as $rows) {
              array_push($checker, $rows['gebruikersnaam']);    
              array_push($checker, $rows['email']); 
            }

            /* Check if Email and Username already exists in database */
            if (in_array($email, $checker) AND in_array($username, $checker)) {
                echo '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'.'Email & Username bestaan al' .'</div>';    
            }
            /* Check if Email already exists in database */
            elseif(in_array($email, $checker)) {
                echo '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'.'Email bestaat al' .'</div>';    
            }
            /* Check if Username already exists in database */
            elseif(in_array($username, $checker)){
                echo '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'.'Username bestaat al' .'</div>';    
            }else{
                /* Begin a transaction, turning off autocommit */
                $this->pdo->beginTransaction();  
                
                $sql = "INSERT INTO medewerker(ID, voornaam, tussenvoegsel, achternaam, email, gebruikersnaam, wachtwoord, role_id)
                        VALUES(NULL, :voornaam, :tussenvoegsel, :achternaam, :email, :gebruikersnaam, :wachtwoord, :role_id);";

                $query = $this->pdo->prepare($sql);

                $query->execute([
                    'voornaam' => $firstname,
                    'tussenvoegsel' => $middlename,
                    'achternaam' => $lastname,
                    'email' => $email,
                    'gebruikersnaam' => $username,
                    'wachtwoord' => password_hash($password, PASSWORD_DEFAULT),
                    'role_id' => $rol
                ]); 

                /* Commit the changes */
                $this->pdo->commit();

                /* Prevents that data is always added to the table during refresh */
                header("Location: medewerkers.php");

                exit;
            }
        
        }  
        catch (PDOException $e) {
            /* Recognize mistake and roll back changes */
            $this->pdo->rollback();
            
            throw $e;
        }
    
    }

    //functie die word gebruikt om een medewerker te laten zien voor de edit page
    public function show_medewerkerDetail($id){
        try {
            $sql = "SELECT * FROM medewerker WHERE id = :id;";

            $query = $this->pdo->prepare($sql);

            $query->execute([
                'id' => $id
            ]);

            $row = $query->fetch(PDO::FETCH_ASSOC);

            return $this->row = $row;
        }      
        catch (PDOException $e) {
            /* Recognize mistake and roll back changes */
            $this->pdo->rollback();
            
            throw $e;
        }
    }

    //functie die word gebruikt om een medewerker te wijzigen
    public function update_medewerker($firstname, $middlename, $lastname, $email, $username, $rol, $id){
        try {
            /* Begin a transaction, turning off autocommit */
            $this->pdo->beginTransaction();  
            
            $sql = "UPDATE medewerker
                    SET 
                    voornaam = :voornaam,
                    tussenvoegsel = :tussenvoegsel,
                    achternaam  = :achternaam,
                    email  = :email,
                    gebruikersnaam = :gebruikersnaam,
                    role_id = :rol
                    WHERE ID = :id;";

            $query = $this->pdo->prepare($sql);

            $query->execute([
                'voornaam' => $firstname,
                'tussenvoegsel' => $middlename,
                'achternaam' => $lastname,
                'email' => $email,
                'gebruikersnaam' => $username,
                'rol' => $rol,
                'id' => $id
            ]); 

            /* Commit the changes */
            $this->pdo->commit();

            /* Prevents that data is always added to the table during refresh */
            header("Location: medewerkers.php");

            exit;
            
        
        }  
        catch (PDOException $e) {
            /* Recognize mistake and roll back changes */
            $this->pdo->rollback();
            
            throw $e;
        }

    }

    //functie die word gebruikt om een medewerker te verwijderen
    public function destroy_medewerker($id){
        try {
           /* Begin a transaction, turning off autocommit */
           $this->pdo->beginTransaction();  
           
           $sql = "DELETE FROM medewerker WHERE ID = :id;";

           $query = $this->pdo->prepare($sql);

           $query->execute([
               'id' => $id
           ]);

            /* Commit the changes */
            $this->pdo->commit();

            /* Prevents that data is always added to the table during refresh */
            header("Location: medewerkers.php");

            exit;
        }   
        catch (PDOException $e) {
            /* Recognize mistake and roll back changes */
            $this->pdo->rollback();
            
            throw $e;
        }
    }

    //functie die word gebruikt om een product te laten zien in de edit page
    public function show_voorraadDetail($id){
        try {
            $sql = "SELECT voorraad.id AS ID, product.id AS productID,  locatie.id as locatieID,
                    aantal, locatie, product.naam AS naam, leverancier.naam AS leverancierNaam, 
                    leverancier.id AS leverancierID, type, inkoopprijs, verkoopprijs 
                    FROM voorraad 
                    INNER JOIN locatie ON locatie_id = locatie.ID 
                    INNER JOIN product ON product_id = product.ID
                    INNER JOIN leverancier ON product.leverancier_id = leverancier.ID
                    WHERE voorraad.id = :id;";
            
            $query = $this->pdo->prepare($sql);

            $query->execute([
                'id' => $id
            ]);

            $row = $query->fetch(PDO::FETCH_ASSOC);

            return $this->row = $row;
        }  
        catch (PDOException $e) {
            /* Recognize mistake and roll back changes */
            $this->pdo->rollback();
            
            throw $e;
        }
    }

    //functie die word gebruikt om een product te wijzigen
    public function update_voorraad($leverancierID, $locatie, $product, $type, $inkoopprijs, $verkoopprijs, $aantal, $id, $productID){
        try {
            /* Begin a transaction, turning off autocommit */
            $this->pdo->beginTransaction(); 

            $sql = "UPDATE product
                    SET
                    naam = :naam,
                    type = :type,
                    leverancier_id = :leverancier_id,
                    inkoopprijs = :inkoopprijs,
                    verkoopprijs = :verkoopprijs
                    WHERE id = :id;";

            $query = $this->pdo->prepare($sql);

            $query->execute([
                'naam' => $product,
                'type' => $type,
                'leverancier_id' => $leverancierID,
                'inkoopprijs' => $inkoopprijs,
                'verkoopprijs' => $verkoopprijs,
                'id' => $productID
            ]);


            $sql2 = "UPDATE voorraad
                     SET
                     locatie_id = :locatie_id,
                     product_id = :product_id,
                     aantal = :aantal
                     WHERE ID = :id;";

            $query2 = $this->pdo->prepare($sql2);

            $query2->execute([
                'locatie_id' => $locatie,
                'product_id' => $productID,
                'aantal' => $aantal,
                'id' => $id
            ]);
            
            /* Commit the changes */
            $this->pdo->commit();

            /* Prevents that data is always added to the table during refresh */
            header("Location: voorraad.php");

            exit;
        }   
        catch (PDOException $e) {
            /* Recognize mistake and roll back changes */
            $this->pdo->rollback();
            
            throw $e;
        }
    }
}
?>