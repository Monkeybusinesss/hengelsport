<?php
include_once '../../db/database.php';

session_start();

$user = $_SESSION['row'];

//Als de session leeg is word gebruiker geleid naar de home page
if (!isset($_SESSION['row']) OR $user['role_id'] != 1) {
    header('Location: ../../auth/login.php');
}else{
    $voorraad = new DB('localhost','root','','hengelsport','utf8mb4');

    $voorraad->show_voorraad();

    if (isset($_POST['destroy'])) {
        $voorraad->delete_voorraad($_POST['productID'], $_POST['voorraadID']);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voorraad - <?php echo $user['gebruikersnaam'];?></title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">   
      
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.css">

    <link rel="stylesheet" href="../../assets/styles/nav/style.css">
</head>
<body>
     <div id="mySidebar" class="sidebar">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
        <a href="../../index.php">Home</a>
        <a href="index.php">Dashboard</a>
        <a href="voorraad.php" class="active">voorraad</a>
        <a href="../../contact.php">Contact</a>
    </div>
     
    <button class="openbtn" onclick="openNav()">&#9776;</button>

    <div class="top-right links">
        <a href="../../auth/logout.php">Uitloggen</a>
    </div>

    <main role="main" class="container">
        <div class="pull-right">
            <a class="btn btn-success mb-3" href="add_product.php">+ Product Toevoegen</a>
        </div>
        <table class="display table table-striped" id="overzicht">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Product</th>
                    <th scope="col">Type</th>
                    <th scope="col">Locatie</th>
                    <th scope="col">aantal</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody> 
                <?php foreach ($voorraad->rows as $row): ?>
                <tr>
                    <td><?php echo $row['ID'];?></td>
                    <td><?php echo $row['naam'];?></td>
                    <td><?php echo $row['type'];?></td>
                    <td><?php echo $row['locatie'];?></td> 
                    <td><?php echo $row['aantal'];?></td> 
                    <td>
                        <a class="btn btn-primary mr-2 btn-sm" href="edit.php?id=<?php echo $userID; ?>">Edit</a>
                    </td>      
                    <td>
                        <form action="" method="post">
                            <input type="hidden" name="productID" value="<?php echo $row['productID']; ?>">
                            <input type="hidden" name="voorraadID" value="<?php echo $row['ID']; ?>">
                            <input type="submit" name="destroy" class="btn btn-danger mr-2 btn-sm" value="verwijder">
                        </form>
                    </td> 
                </tr> 
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
    <script src="../../assets/js/nav/script.js"></script>
   
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.js"></script>   
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
    <script src="../../assets/js/table.js"></script>
</body>
</html>