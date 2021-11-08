<?php
include_once '../../db/database.php';

session_start();

$user = $_SESSION['row'];

//Als de session leeg is word gebruiker geleid naar de home page
if (!isset($_SESSION['row']) OR $user['role_id'] != 2) {
    header('Location: ../../auth/login.php');
}else{
    $db = new DB('localhost','root','','hengelsport','utf8mb4');

        if (isset($_POST['submit'])) {
            //Stop de namen van inout fiels in een array
        $fieldnames = ['naam'];

        $error = false;

        //Ik loop door de array en stop de fieldname in de $_POST, mocht er 1 of meer leeg zijn word de var $error true
        foreach ($fieldnames as $fieldname) {
            if (empty($_POST[$fieldname])) {
                $error = true;
            }
        }

        //Als $error false is, gaan we de add_product functie uitvoeren
        if (!$error) {
            $db->add_locatie($_POST['naam']);
        }else{
            echo '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'.'Alle Velden moeten worden ingevuld' .'</div>';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Locatie - <?php echo $user['gebruikersnaam'];?></title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">   
        
    <link rel="stylesheet" href="../../assets/styles/nav/style.css">
    
    <link rel="stylesheet" href="../../assets/styles/admin/style.css">
</head>
<body>
   <div class="logo">
        <img src="../../assets/img/logo/logo.png" alt="">
    </div>
     <div id="mySidebar" class="sidebar">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
        <a href="../../index.php">Home</a>
        <a href="index.php">Dashboard</a>
        <a href="voorraad.php">voorraad</a>
        <a href="fabriek.php">fabriek</a>
        <a href="locatie.php">locatie</a>
        <a href="medewerkers.php">medewerkers</a>
        <a href="../../contact.php">Contact</a>
    </div>
     
    <button class="openbtn" onclick="openNav()">&#9776;</button>

    <div class="top-right links">
        <a href="../../auth/logout.php">Uitloggen</a>
    </div>

    <main role="main" class="container">
        <div class="pull-right">
            <a class="btn btn-info mb-3" href="locatie.php">Terug</a>
        </div>
        <h3>Locatie Toevoegen</h3>
        <hr>
            <form action="" method="post" autocomplete="off">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Naam</label>
                    <input type="text" name="naam" class="form-control" required/>
                </div>
            </div>
         
            <hr>
            <input type="submit" class="btn btn-success" name="submit" value="toevoegen">
        </form>
    </main>
    <script src="../../assets/js/nav/script.js"></script>
</body>
</html>