<?php
include('auth.php');
if($_SERVER['REQUEST_METHOD'] == 'GET') {
    $sId = $_GET['sId'];

    require 'db.php';
    if($erg = $db->query("SELECT s_name, s_beschreibung, s_besitzDatum, s_bild FROM tbl_sammlerobjekt WHERE s_id = '". $sId . "'")){
        if($erg->num_rows){
            $datensatz = $erg->fetch_object();
            $erg->free();
        }
    }
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $sId = $_POST['sId'];

    if((!$_POST['objectName'])||(!$_POST['objectDescription'])||(!$_POST['objectDate'])){
        $emptyErrorMessage = "Bitte fülle alle Eingabefelder aus.";
    }
    else {
        $name = $_POST['objectName'];
        $beschreibung = $_POST['objectDescription'];
        $datum = $_POST['objectDate'];
        $filename = $_FILES['objectImage']['name'];
        $tempname = $_FILES['objectImage']['tmp_name'];
        $folder = "../tmp/" . $filename;
        move_uploaded_file($tempname, $folder);
        $bild = file_get_contents("../tmp/" . $filename);
        unlink("../tmp/" . $filename);

        require 'dbPDO.php';

        $statement = $pdo->prepare("UPDATE tbl_sammlerobjekt SET s_name = ?, s_beschreibung = ?, s_besitzDatum = ?, s_bild = ? WHERE s_id = ?");
        $statement->execute(array($name, $beschreibung, $datum, $bild, $sId));
        $changedFile = true;
    }
    }

?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mittelerde Sammlerobjekte</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/mainStyle.css">

</head>
<body>
<header class="p-3 mb-3 border-bottom">
    <div class="container">
        <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
            <a href="indexLoggedIn.php" class="d-flex align-items-center mb-2 mb-lg-0 text-dark text-decoration-none">
                <img src="../res/logo.png" alt="logo">
            </a>

            <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                <li><a href="indexLoggedIn.php" class="nav-link px-2 link-dark">Home</a></li>
                <li><a href="myObjects.php" class="nav-link px-2 link-dark">Meine Sammlerobjekte</a></li>
            </ul>

            <form class="col-12 col-lg-auto mb-3 mb-lg-0 me-lg-3" method="post" action="index.php">
                <input type="search" class="form-control" placeholder="Search...">
            </form>

            <div class="dropdown text-end">
                <a href="#" class="d-block link-dark text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="../res/profil.jpg" alt="mdo" width="32" height="32" class="rounded-circle">
                </a>
                <ul class="dropdown-menu text-small" aria-labelledby="dropdownUser1">
                    <li><a class="dropdown-item" href="myObjects.php">Meine Sammlerobjekte</a></li>
                    <li><a class="dropdown-item" href="#">Profil</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="logout.php">Abmelden</a></li>
                </ul>
            </div>
        </div>
    </div>
</header>
<main class="text-center">
    <?php if(!isset($changedFile)) : ?>
    <div>
        <h1>Sammlerobjekt "<?php echo $datensatz->s_name; ?>" ändern</h1>
    </div>
        <div class="add-object-form">
            <form id="changeObject" method="post" action="editObject.php" enctype="multipart/form-data">
                <label for="objectName" class="sr-only">Bezeichnung</label>
                <?php
                echo '<input type="text" name="objectName" id="objectName" class="form-control" value="' . $datensatz->s_name . '"><br>';

                echo '<textarea class="form-control" name="objectDescription" form="changeObject">';
                echo $datensatz->s_beschreibung;
                echo '</textarea><br>';

                echo '<label for="objectDate">Im Besitz seit:</label>';
                echo '<input type="date" name="objectDate" id="objectDate" class="form-control" value="' . $datensatz->s_besitzDatum . '"><br>';

                echo '<input type="hidden" id="sId" name="sId" value=' . $sId . '>';

                echo '<label for="objectImage">Bild (PNG max.16MB):</label>';
                echo '<input type="file" name="objectImage" id="objectImage" accept="image/png" class="form-control"><br>';
                ?>
                <input name="changeObjectButton" id="changeObjectButton" class="btn btn-primary mb-4" type="submit" value="Ändern">
            </form>
            <p class="login-card-error-message">
                <?php
                if(isset($emptyErrorMessage)) {
                    echo $emptyErrorMessage;
                }
                ?>
            </p>
        </div>
    <?php else : ?>
        <p>Das Sammlerobjekt wurde erfolgreich geändert.</p>
    <?php endif?>
</main>
</body>
</html>
