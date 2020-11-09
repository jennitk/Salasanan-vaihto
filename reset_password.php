<?php

session_start();
 
// Tarkistetaan onko käyttäjä kirjautunut
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
 
// lisätään config
require_once "config.php";
 
// määritellään muuttujat
$new_password = $confirm_password = "";
$new_password_err = $confirm_password_err = "";
 
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validoi uusi salasana
    if(empty(trim($_POST["new_password"]))){
        $new_password_err = "Syötä uusi salasana";     
    } elseif(strlen(trim($_POST["new_password"])) < 6){
        $new_password_err = "Salasanan täytyy sisältää vähintään kuusi (6) merkkiä";
    } else{
        $new_password = trim($_POST["new_password"]);
    }
    
    // Validoi toistettu salasana
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Vahvista salasana";
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($new_password_err) && ($new_password != $confirm_password)){
            $confirm_password_err = "Salasanat eivät täsmää";
        }
    }
        
    // tarkistetaan että errorit tyhjiä
    if(empty($new_password_err) && empty($confirm_password_err)){
        $sql = "UPDATE users SET password = ? WHERE id = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "si", $param_password, $param_id);
            
            // parametrit
            $param_password = password_hash($new_password, PASSWORD_DEFAULT);
            $param_id = $_SESSION["id"];
            
            if(mysqli_stmt_execute($stmt)){
                session_destroy();
                header("location: kirjaudu.php");
                exit();
            } else{
                echo "Jotain meni vikaan, kokeile myöhemmin uudestaan";
            }

            // sulje statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // sulje tietokantayhteys
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Vaihda salasana</title>

    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<!--navbar-->
<nav class="navbar navbar-expand-md navbar-dark" style="background-color: #5A9089;">
  <div class="nav-item">
    <a class="navbar-brand">
    <img src="Pictures\whitelogo_Larate.png" style=" height: 30px; width: 100 px; display: inline-block;">
    </a>
  </div>

</nav>
    <body>
        <div class="container" id="containerKirjautuminen">
            <div class="divKirjautuminen1">
                <h2>Vaihda salasana</h2>
                <p>Täytä alla olevat tiedot vaihtaaksesi tilisi salasanan.</p>
                <div class="divKirjautuminen2">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"> 
                    <div class="form-group <?php echo (!empty($new_password_err)) ? 'has-error' : ''; ?>">
                        <label>Uusi salasana</label>
                        <input type="password" name="new_password" class="form-control" value="<?php echo $new_password; ?>">
                        <span class="help-block"><?php echo $new_password_err; ?></span>
                    </div>
                    <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                        <label>Toista uusi salasana</label>
                        <input type="password" name="confirm_password" class="form-control">
                        <span class="help-block"><?php echo $confirm_password_err; ?></span>
                    </div>
                    <div class="form-group">
                        <input type="submit" class="btn btn-info" value="Tallenna">
                        <a class="btn btn-link" href="terve_terapeutti.php">Peruuta</a>
                    </div>
                    </div>
                </form>
            </div>
        </div>
        <footer class="footerRTP2">
        <div class="container" style="margin-top:60px">
            <div class="row">
            <div class="col-md-4" style="text-align: center;">
                <img src="Pictures\Black logo - no background.png" style=" height: 60px; width: 200 px; display: inline-block;">
            </div>
            <div class="col-md-4" style="text-align: center">
            </div>
            <div class="col-md-4" style="text-align: center;">
            </div>
            <div class="col-md-12" style="text-align: center; margin-top: 50px; margin-bottom: 20px;">
                <p>&copy; Ryhmä-R 2020</p>
            </div>

            </div>
        </div>
        </footer>   
    </body>
</html>