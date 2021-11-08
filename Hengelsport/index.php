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
    <title>Home</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">   
    
    <!--NAV & link buttons CSS-->
    <link rel="stylesheet" href="assets/styles/nav/style.css">
    
    <link rel="stylesheet" href="assets/styles/admin/style.css">

    <link rel="stylesheet" href="assets/styles/home/home.css">
</head>
<body>
   <div class="logo">
        <img src="assets/img/logo/logo.png" alt="">
    </div>
    <div id="mySidebar" class="sidebar">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
        <a href="index.php" class="active">Home</a>
        <?php if (isset($user) AND $user['role_id'] == 2):?>
            <a href="views/admin/index.php">Dashboard</a>
            <a href="views/admin/voorraad.php">voorraad</a>
            <a href="views/admin/fabriek.php">fabriek</a>
            <a href="views/admin/locatie.php">locatie</a>
        <?php elseif (isset($user) AND $user['role_id'] == 1):?>
            <a href="views/medewerker/index.php">Dashboard</a>
            <a href="views/medewerker/voorraad.php">voorraad</a>
        <?php endif;?>
        <a href="contact.php">Contact</a>
    </div>
     
    <button class="openbtn" onclick="openNav()">&#9776;</button>

    <?php if(isset($user)): ?>
    <div class="top-right links">
        <a href="auth/logout.php">Uitloggen</a>
    </div>
    <?php else:?>
    <div class="top-right links">
        <a href="auth/login.php">Inloggen</a>
    </div>
    <?php endif; ?>

    <main role="main" class="container" id="main">
        <center>
            <h3>De Hengelsport</h3>
            <hr>
            <div class="home-pic">
                <img src="assets/img/shop.jpg" alt="">
            </div>
        </center>  
    </main>
    <script src="assets/js/nav/script.js"></script>
</body>
</html>