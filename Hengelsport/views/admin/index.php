<?php
include_once '../../db/database.php';

session_start();

$user = $_SESSION['row'];

//Als de session leeg is word gebruiker geleid naar de home page
if (!isset($_SESSION['row']) OR $user['role_id'] != 2) {
    header('Location: ../../auth/login.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - <?php echo $user['gebruikersnaam'];?></title>
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
        <a href="index.php" class="active">Dashboard</a>
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

    <center>
        <h3><?php echo 'Welkom - '. $user['gebruikersnaam']; ?></h3>
        <hr>
    </center>

 



    <script src="../../assets/js/nav/script.js"></script>
</body>
</html>