<?php

    require_once 'include/configuration.php';  //connection to database and session start

    $error = '';  //variable for error display

    /* form handling */
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        /* check if the entered passwords match */
        $password = $_POST['password'];
        $passwordRepeat = $_POST['repeat-password'];
        if ($password !== $passwordRepeat) {
            echo "Salasanat eivät täsmää!!";
            exit;
        }

        $username = trim($_POST['name']);
        $email = trim($_POST['email']);
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Check if email already exists
        $checkEmailStmt = $pdo->prepare("SELECT email FROM users WHERE email = ?");
        $checkEmailStmt->execute([$email]);

        /* save the result into variable to check if there any users with the same email */
        $existingUser = $checkEmailStmt->fetch(PDO::FETCH_ASSOC);

        if ($existingUser) {
            echo "Sähköposti on jo käytössä.";
        } else {
            /* if there's no such email, insert new user */
            $stmt = $pdo->prepare("INSERT INTO users (email, username, password_hash) VALUES (?, ?, ?);");

            if ($stmt->execute([$email, $username, $hashedPassword])) {
                header("Location: account.php");
                exit;
            } else {
                echo "Error creating account.";
            }
        }

        /* $checkEmailStmt->close(); */
    }

    $pageTitle = "SignUp";
    $extraCSS = "CSS/SignUp_LogIn.css";
    include 'include/header.php'; 
?>
<main class="signup_page">

    <div class="wrapper">

        <form class="signup" method="POST" action="">

            <h1>Rekisteröidy</h1>

            <div>
                <label for="name-input">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#F8F7F3ff"><path d="M367-527q-47-47-47-113t47-113q47-47 113-47t113 47q47 47 47 113t-47 113q-47 47-113 47t-113-47ZM160-160v-112q0-34 17.5-62.5T224-378q62-31 126-46.5T480-440q66 0 130 15.5T736-378q29 15 46.5 43.5T800-272v112H160Z"/></svg>
                </label>
                <input type="text" id="name-input" name="name" placeholder="Nimi" minlength="2" required>
            </div>
            <div>
                <label for="email-input">
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#F8F7F3ff"><path d="M480-80q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480v58q0 59-40.5 100.5T740-280q-35 0-66-15t-52-43q-29 29-65.5 43.5T480-280q-83 0-141.5-58.5T280-480q0-83 58.5-141.5T480-680q83 0 141.5 58.5T680-480v58q0 26 17 44t43 18q26 0 43-18t17-44v-58q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93h200v80H480Zm85-315q35-35 35-85t-35-85q-35-35-85-35t-85 35q-35 35-35 85t35 85q35 35 85 35t85-35Z"/></svg>
                </label>
                <input type="email" id="email-input" name="email" placeholder="Sähköpostiosoite" required>
            </div>
            <div>
                <label for="password-input">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#F8F7F3ff"><path d="M240-80q-33 0-56.5-23.5T160-160v-400q0-33 23.5-56.5T240-640h40v-80q0-83 58.5-141.5T480-920q83 0 141.5 58.5T680-720v80h40q33 0 56.5 23.5T800-560v400q0 33-23.5 56.5T720-80H240Zm296.5-223.5Q560-327 560-360t-23.5-56.5Q513-440 480-440t-56.5 23.5Q400-393 400-360t23.5 56.5Q447-280 480-280t56.5-23.5ZM360-640h240v-80q0-50-35-85t-85-35q-50 0-85 35t-35 85v80Z"/></svg>
                </label>
                <input type="password" id="password-input" name="password" placeholder="Salasana" required>
            </div>
            <div>
                <label for="repeat-password-input">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#F8F7F3ff"><path d="M240-80q-33 0-56.5-23.5T160-160v-400q0-33 23.5-56.5T240-640h40v-80q0-83 58.5-141.5T480-920q83 0 141.5 58.5T680-720v80h40q33 0 56.5 23.5T800-560v400q0 33-23.5 56.5T720-80H240Zm296.5-223.5Q560-327 560-360t-23.5-56.5Q513-440 480-440t-56.5 23.5Q400-393 400-360t23.5 56.5Q447-280 480-280t56.5-23.5ZM360-640h240v-80q0-50-35-85t-85-35q-50 0-85 35t-35 85v80Z"/></svg>
                </label>
                <input type="password" id="repeat-password-input" name="repeat-password" placeholder="Toista salasana" required>
            </div>
            <button type="submit" class="">Submit</button>

            <p>Aready have an account? <a href="login.php">Log in</a> </p>
        </form>

        <?php 
            
        ?>

        <?php if ($error): ?>
            <p style="color:red"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

    </div>

</main>   
<?php include 'include/footer.php'; ?>

