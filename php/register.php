<?php
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        if((!$_POST['firstName'])||(!$_POST['secondName'])||(!$_POST['email'])||(!$_POST['username'])||(!$_POST['password'])){
            $emptyErrorMessage = "Bitte fülle alle Eingabefelder aus.";
        }
        else {
            $vorname = $_POST['firstName'];
            $nachname = $_POST['secondName'];
            $email = $_POST['email'];
            $benutzername = $_POST['username'];
            $passwort = password_hash($_POST['password'], PASSWORD_DEFAULT);

            require 'db.php';
            $sql = "SELECT b_email FROM tbl_benutzer WHERE b_email = ?";
            $statement = $db->prepare($sql);
            $statement->bind_param('s', $email);
            $statement->execute();
            if ($erg = $statement->get_result()) {
                if ($erg->num_rows) {
                    $errorMessageEmail = "E-Mail vergeben!";
                } else {
                    $sql = "SELECT b_benutzername FROM tbl_benutzer WHERE b_benutzername = ?";
                    $statement = $db->prepare($sql);
                    $statement->bind_param('s', $benutzername);
                    $statement->execute();
                    if ($erg = $statement->get_result()) {
                        if ($erg->num_rows) {
                            $errorMessageUsername = "Benutzername vergeben!";
                        } else {
                            $sql = "INSERT INTO tbl_benutzer (b_vorname, b_nachname, b_email, b_benutzername, b_passwort) VALUES (?, ?, ?, ?, ?)";
                            $statement = $db->prepare($sql);
                            $statement->bind_param('sssss', $vorname, $nachname, $email, $benutzername, $passwort);
                            $statement->execute();
                            $registered = true;
                        }
                    }
                }
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Mittelerde Sammlerobjekte</title>

    <link href="https://fonts.googleapis.com/css?family=Karla:400,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.materialdesignicons.com/4.8.95/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/mainStyle.css">
</head>
<body>
<div class="landingPageBackground">
    <main class="d-flex align-items-center min-vh-100 py-3 py-md-0">
        <div class="container">
            <div class="card login-card">
                <div class="row no-gutters">
                    <div class="col-md-5">
                        <img src="../res/loginImage.jpg" alt="login" class="login-card-img">
                    </div>
                    <div class="col-md-7">
                        <div class="card-body">
                            <?php if(!isset($registered)) : ?>
                            <p class="login-card-description">Registrierung</p>
                            <form id="registerForm" method="post" action="register.php">
                                <div class="form-group">
                                    <label for="firstName" class="sr-only">Vorname</label>
                                    <input type="text" name="firstName" id="firstName" class="form-control" placeholder="Vorname" minlength="1" maxlength="32">
                                </div>
                                <div class="form-group">
                                    <label for="secondName" class="sr-only">Nachname</label>
                                    <input type="text" name="secondName" id="secondName" class="form-control" placeholder="Nachname" minlength="1" maxlength="32">
                                </div>
                                <div class="form-group">
                                    <label for="email" class="sr-only">Email</label>
                                    <input type="email" name="email" id="email" class="form-control" placeholder="Email Adresse" minlength="1" maxlength="32">
                                </div>
                                <div class="form-group">
                                    <label for="username" class="sr-only">Benutzername</label>
                                    <input type="text" name="username" id="username" class="form-control" placeholder="Benutzername" minlength="1" maxlength="32">
                                </div>
                                <div class="form-group mb-4">
                                    <label for="password" class="sr-only">Passwort</label>
                                    <input type="password" name="password" id="password" class="form-control" placeholder="Passwort" minlength="1" maxlength="32">
                                </div>
                                <input name="register" id="register" class="btn btn-block login-btn mb-4" type="submit" value="Registrieren">
                            </form>
                            <p class="login-card-error-message">
                                <?php
                                    if(isset($errorMessageEmail)){
                                        echo $errorMessageEmail;
                                    }
                                    if(isset($errorMessageUsername)){
                                        echo $errorMessageUsername;
                                    }
                                    if(isset($emptyErrorMessage)){
                                        echo $emptyErrorMessage;
                                    }
                                ?>
                            </p>
                            <?php else : ?>
                                <p class="login-card-description">Registrierung erfolgreich!</p>
                                <p class="login-card-footer-text"><a href="login.php" class="text-reset">Weiter zur Anmeldung</a></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
</body>
</html>
