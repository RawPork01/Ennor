<?php
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        if((!$_POST['email'])||(!$_POST['password'])){
            $emptyErrorMessage = "Bitte fülle alle Eingabefelder aus.";
        }
        else {
            session_start();

            $email = $_POST['email'];
            $password = $_POST['password'];

            $hostname = $_SERVER['HTTP_HOST'];
            $path = dirname($_SERVER['PHP_SELF']);

            require 'db.php';
            $sql = "SELECT b_id, b_passwort FROM tbl_benutzer WHERE b_email = ?";
            $statement = $db->prepare($sql);
            $statement->bind_param('s', $email);
            $statement->execute();

            if ($erg = $statement->get_result()) {
                if ($erg->num_rows) {
                    $datensatz = $erg->fetch_assoc();
                    if (password_verify($password, $datensatz['b_passwort'])) {
                        $_SESSION['login'] = true;
                        $_SESSION['b_id'] = $datensatz['b_id'];

                        if ($_SERVER['SERVER_PROTOCOL'] == 'HTTP/1.1') {
                            if (php_sapi_name() == 'cgi') {
                                header('Status: 303 See Other');
                            } else {
                                header('HTTP/1.1 303 See Other');
                            }
                        }

                        header('Location: http://' . $hostname . ($path == '/' ? '' : $path) . '/indexLoggedIn.php');
                        exit;
                    } else {
                        $errorMessage = "E-Mail oder Passwort war ungültig!";
                    }
                } else {
                    $errorMessage = "E-Mail oder Passwort war ungültig!";
                }
                $erg->free();
            } else {
                $errorMessage = "E-Mail oder Passwort war ungültig!";
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
                            <h1>Willkommen in Mittelerde!</h1>
                            <p class="login-card-description">Melde dich an</p>
                            <form id="loginForm" method="post" action="login.php">
                                <div class="form-group">
                                    <label for="email" class="sr-only">Email</label>
                                    <input type="email" name="email" id="email" class="form-control" placeholder="Email Adresse">
                                </div>
                                <div class="form-group mb-4">
                                    <label for="password" class="sr-only">Password</label>
                                    <input type="password" name="password" id="password" class="form-control" placeholder="***********">
                                </div>
                                <input name="login" id="login" class="btn btn-block login-btn mb-4" type="submit" value="Anmelden">
                            </form>
                            <p class="login-card-error-message">
                                <?php
                                    if(isset($errorMessage)){
                                        echo $errorMessage;
                                    }
                                    if(isset($emptyErrorMessage)){
                                        echo $emptyErrorMessage;
                                    }
                                ?>
                            </p>
                            <a href="#!" class="forgot-password-link">Passwort vergessen?</a>
                            <p class="login-card-footer-text">Kein Account? <a href="register.php" class="text-reset">Hier registrieren</a></p>
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
