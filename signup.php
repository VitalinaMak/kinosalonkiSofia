<?php 
    $pageTitle = "SignUp";
    include 'include/header.php'; 
?>
    <h1></h1>
    <div class="main">

        <form>
            <div>
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required minlength="2">
            </div>
            <div>
                <label for="email">Sähköpostiosoite:</label>
                <input type="email" id="email" name="enter_your_email" required>
            </div>
            <div>
                <label for="password">Salasana:</label>
                <input type="password" id="password" name="password" required minlength="4">
            </div>
            <button type="submit">Submit</button>
        </form>

    </div>
    
<?php include 'include/footer.php'; ?>

