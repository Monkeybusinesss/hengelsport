<?php
session_start();

if(isset($_SESSION['row'])){
    $user = $_SESSION['row'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">   
    
    <!--NAV & link buttons CSS-->
    <link rel="stylesheet" href="assets/styles/nav/style.css">
    
    <link rel="stylesheet" href="assets/styles/admin/style.css">
</head>
<body>
   <div class="logo">
        <img src="assets/img/logo/logo.png" alt="">
    </div>
    <div id="mySidebar" class="sidebar">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
        <a href="index.php">Home</a>
        <?php if (isset($user) AND $user['role_id'] == 2):?>
            <a href="views/admin/index.php">Dashboard</a>
            <a href="views/admin/voorraad.php">voorraad</a>
            <a href="views/admin/fabriek.php">fabriek</a>
            <a href="views/admin/locatie.php">locatie</a>
        <?php elseif (isset($user) AND $user['role_id'] == 1):?>
            <a href="views/medewerker/index.php">Dashboard</a>
            <a href="views/medewerker/voorraad.php">voorraad</a>
        <?php endif;?>
        <a href="contact.php" class="active">Contact</a>
    </div>
     
    <button class="openbtn" onclick="openNav()">&#9776;</button>
  
    <?php if(isset($_SESSION['row'])): ?>
    <div class="top-right links">
        <a href="auth/logout.php">Uitloggen</a>
    </div>
    <?php else:?>
    <div class="top-right links">
        <a href="auth/login.php">Inloggen</a>
    </div>
    <?php endif; ?>

    <main role="main" class="container" id="main">
        <div class="card">
            <h5 class="card-header">Rotterdam</h5>
            <div class="card-body">
                <p class="card-text"><b>Adres: </b>Librijesteeg 209 3011HN Rotterdam</p>
                <p class="card-text"><b>E-mail: </b>hengelsport@rotterdam.nl</p>
                <p class="card-text"><b>Telefoon: </b>020 548 785 14</p>
            </div>
        </div>

        <hr>

        <div class="card">
            <h5 class="card-header">Zoetermeer</h5>
            <div class="card-body">
                <p class="card-text"><b>Adres: </b>Lijnbaan 7 2728AA Zoetermeer</p>
                <p class="card-text"><b>E-mail: </b>hengelsport@zoetermeer.nl</p>
                <p class="card-text"><b>Telefoon: </b>020 548 785 14</p>
            </div>
        </div>

        <hr>

        <div class="card">
            <h5 class="card-header">Amsterdam</h5>
            <div class="card-body">
                <p class="card-text"><b>Adres: </b>Lange Vonder 297 1035KX Amsterdam</p>
                <p class="card-text"><b>E-mail: </b>hengelsport@amsterdam.nl</p>
                <p class="card-text"><b>Telefoon: </b>020 852 586 22</p>
            </div>
        </div>

        <hr>

        
    </main>
    <script src="assets/js/nav/script.js"></script>
</body>
</html>