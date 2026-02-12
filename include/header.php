<!-- Server connections ARE IN configuration.php -->
<?php require_once 'include/configuration.php'; ?>

<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- link to bootstrap for using CSS features (it is required for buttons design) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header> 
        <div class="otsikko">
            <img src="kuvat/logot/logo_white.png" alt="logo" style="height: 30px">
            <h1>Kinosalonki Sofia</h1>
        </div>
        <a href="login.php" class="btn btn-outline-primary">Kirjaudu sisään</a>

        <!-- DELETE THIS LATER; navigation to all our php files, thought might be useful-->
        <nav>
            <ul>
                <li><a href="addEvent.php">addEvent</a></li>
                <li><a href="bookEvent.php">bookEvent</a></li>
                <li><a href="editEvent.php">editEvent</a></li>
                <li><a href="index.php">index</a></li>
                <li><a href="login.php">login</a></li>
                <li><a href="login.php">login</a></li>
                <li><a href="signup.php">signup</a></li>
                <li><a href="tapahtumat.php">Tapahtumat</a></li>

            </ul>
        </nav> 
            
    </header>