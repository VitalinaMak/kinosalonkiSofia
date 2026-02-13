<?php 
    $pageTitle = "Login";
    include 'include/header.php'; 
?>
    <h1>Kirjaudu sisään:</h1>

    
    <form class="login">
        <div>
            <!-- <label for="enter_your_email">Sähköpostiosoite:</label> -->
            <input type="email" id="email-input" name="email" placeholder="Sähköpostiosoite" required>
        </div>
        <div>
            <!-- <label for="salasana">Salasana:</label> -->
            <input type="password" id="password-input" name="password" placeholder="Salasana" required>
        </div>
        <div>
            <!-- <label for="salasana">Salasana:</label> -->
            <input type="password" id="repeat-password-input" name="repeat-password" placeholder="Toista salasana" required>
        </div>
        <button type="submit">Submit</button>
    </form>

    


    
<?php include 'include/footer.php'; ?>
