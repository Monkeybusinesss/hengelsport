<?php
include_once '../db/database.php';

if (isset($_POST['submit'])) {
    //Stop de namen van inout fiels in een array
    $fieldnames = ['username', 'password'];

    $error = false;

    //Ik loop door de array en stop de fieldname in de $_POST, mocht er 1 of meer leeg zijn word de var $error true
    foreach ($fieldnames as $fieldname) {
        if (empty($_POST[$fieldname])) {
            $error = true;
        }
    }

    //Als $error false is, gaan we de login functie uitvoeren die in ../db/database.php zit
    if (!$error) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $db = new DB('localhost','root','','hengelsport','utf8mb4');

        $db->login($username, $password);
    }
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

    <link rel="stylesheet" href="../assets/styles/login/style.css">
</head>
<body>
    <main role="main" class="container" id="main" >
    <?php if (isset($db->message)) {
        echo $db->message;
    }?>
        <form action="" method="post" autocomplete="off">
            <div class="form-group" id="input-1">
                <label>Gebruikersnaam</label>
                <input type="text" name="username" class="form-control" autofocus required/>
            </div>
            <div class="form-group" id="input-2">
                <label>wachtwoord</label>
                <input type="password" name="password" class="form-control" required/>
            </div>
            <input type="submit" name="submit" class="btn btn-primary" value="inloggen">
        </form>
    </main>
    
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>