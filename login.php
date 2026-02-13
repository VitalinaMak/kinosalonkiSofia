<?php 
    $pageTitle = "Login";
    include 'include/header.php'; 
?>
    <h1>Kirjaudu sisään:</h1>

    
    <form>
    <!-- maybe remove the div's ? idk -->
        <div>
            <label for="enter_your_email">Sähköpostiosoite:</label>
            <input type="email" id="enter_your_email" name="enter_your_email" required><br><br>
        </div>
        <div>
            <label for="salasana">Salasana:</label>
            <input type="password" id="salasana" name="salasana" required><br><br>
        </div>
        <button type="submit">Submit</button>
    </form>


    
<?php include 'include/footer.php'; ?>
