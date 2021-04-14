<?php
include('auth.php');

require 'db.php';
$daten = array();
$numRows = 0;
if($erg = $db->query("SELECT s_name, s_beschreibung, s_besitzDatum, s_datumOnline, s_bild, tbl_benutzer.b_benutzername FROM tbl_sammlerobjekt INNER JOIN tbl_benutzer ON tbl_sammlerobjekt.s_benutzer = tbl_benutzer.b_id ORDER BY s_datumOnline DESC LIMIT 9")){
    if($erg->num_rows){
        while($datensatz = $erg->fetch_object()){
            $daten[] = $datensatz;
        }
        $numRows = $erg->num_rows;
        $erg->free();
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
<main>
    <section class="py-5 text-center album-background-image">
        <div class="row py-lg-5">
            <div class="col-lg-6 col-md-8 mx-auto album-heading">
                <h1>Mittelerde</h1>
                <p class="lead text-muted">Sammlerobjekte aus der Welt von J. R. R. Tolkien</p>
                <p>
                    <a href="addObject.php" class="btn btn-primary my-2">Sammlerobjekt hinzufügen</a>
                </p>
            </div>
        </div>
    </section>

    <div class="container">
        <h2 class="fw-light album-subheading">Zuletzt hinzugefügt:</h2>
    </div>

    <div class="album py-5">
        <div class="container">

            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                <?php
                for($i=0; $i<$numRows;$i++) {
                    echo '<div class="col">';
                    echo '<div class="card shadow-sm">';
                    echo '<img alt="bild" class="card-img-top album-card-img" src = "data:image/png;base64,' . base64_encode($daten[$i]->s_bild) . '" <text x="50%" y="50%" fill="#eceeef" dy=".3em"></text>';
                    echo '<div class="card-body">';
                    echo '<h3 class="album-card-heading">' . $daten[$i]->s_name . '</h3>';
                    echo '<p class="card-text">' . $daten[$i]->s_beschreibung . '</p>';
                    echo '<div class="d-flex justify-content-between align-items-center">';
                    echo '<div class="btn-group">';
                    echo '</div>';
                    echo '<small class="text-muted">von: ' . $daten[$i]->b_benutzername . ' </small>';
                    echo '<small class="text-muted">am: ' . $daten[$i]->s_datumOnline . ' </small>';
                    echo '</div></div></div></div>';
                }
                ?>
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
</body>
</html>

