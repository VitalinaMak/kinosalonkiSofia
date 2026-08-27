<?php require_once __DIR__ . '/configuration.php'; ?>

<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"> -->

    <!-- CSS GLOBAL -->
    <link rel="stylesheet" href="../CSS/global.css">
    <!-- CSS variable for php -->
    <?php if (isset($extraCSS)): ?>
        <link rel="stylesheet" href="<?php echo $extraCSS; ?>">
    <?php endif; ?>

</head>
<body>
    <header> 
        <div class="logo-ksofia">
            <a href="index.php" class="logo-link">
                <img src="../kuvat/logot/logo_black.png" alt="logo" class="logo">
            </a>
            <h2 class="kinosalonkisofia">Kinosalonki Sofia</h2>
        </div>
        <?= !isset($_SESSION['user_id']) ? "<a href='login.php' class='button'>Kirjaudu sisään</a>" : "<a href='account.php' class='button'>Oma tili</a>" ?>

        <!-- DELETE THIS LATER; navigation to all our php files, thought might be useful-->
        <!-- OK. I made them into a drop-down list so they wouldn't interfere with the formatting -->
        <nav class="temporaryNavigation">
            <button class="navButton">List of pages</button>
            <div class="linksToPages">
                <a href="addEvent.php" target="_blank" rel="noopener noreferrer">addEvent</a>
                <a href="bookEvent.php" target="_blank" rel="noopener noreferrer">bookEvent</a>
                <a href="editEvent.php" target="_blank" rel="noopener noreferrer">editEvent</a>
                <a href="http://localhost/kinosalonkisofia/editEvent.php?id=8" target="_blank" rel="noopener noreferrer">editTHEevent</a>
                <a href="index.php" target="_blank" rel="noopener noreferrer">index</a>
                <a href="login.php" target="_blank" rel="noopener noreferrer">login</a>
                <a href="signup.php" target="_blank" rel="noopener noreferrer">signup</a>
                <a href="../account.php" target="_blank" rel="noopener noreferrer"><US_ACCOUNT</a>
                <a href="../ADMIN/ad_account.php" target="_blank" rel="noopener noreferrer"><US_ACCOUNT</a>
                <a href="tapahtumat.php" target="_blank" rel="noopener noreferrer">Tapahtumat</a>
            </div>
        </nav> 
            
    </header>