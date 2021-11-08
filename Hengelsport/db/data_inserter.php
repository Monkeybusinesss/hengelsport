<?php
try{
    //Connecting MySQL Database
    $pdo  = new PDO('mysql:host=localhost;dbname=hengelsport', 'root', '', array(
        PDO::ATTR_PERSISTENT => true
    ));
    $pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
     
    
    //Insert the data
    $sql = "INSERT INTO medewerker(ID, voornaam, tussenvoegsel, achternaam, email, gebruikersnaam, wachtwoord, role_id)
    VALUES(NULL, :voornaam, :tussenvoegsel, :achternaam, :email, :gebruikersnaam, :wachtwoord, :role_id);";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        'voornaam' => 'john',
        'tussenvoegsel' => '',
        'achternaam' => 'doe',
        'email' => 'john@gmail.com',
        'gebruikersnaam' => 'johndoe',
        'wachtwoord' => password_hash('123', PASSWORD_DEFAULT),
        'role_id' => 1
    ]);

    $stmt->execute([
        'voornaam' => 'harry',
        'tussenvoegsel' => '',
        'achternaam' => 'doe',
        'email' => 'harry@gmail.com',
        'gebruikersnaam' => 'harry',
        'wachtwoord' => password_hash('123', PASSWORD_DEFAULT),
        'role_id' => 2
    ]);
    echo '<h1>Data inserted, dont refresh again or change the values in <code>db/data_inserter.php</code></h1>';

    
}
catch(Exception $e){
    echo '<pre>';print($e);echo '</pre>';exit;
}
?>