<?php 
    $pageTitle = "Account";
    $extraCSS = "CSS/account.css";
    $extraJS = "JavaScript/account.js";
    include 'include/header.php'; 
?>
<main class="account_page">
    
    <h1> Hei, [users_name]</h1>

    <div class="wrapper"></div>

        <div class="user info">

            <form class="profile" id="profile" method="post" action="account.php"  enctype="multipart/form-data">
                <div>
                    <label for="name-input">Nimi</label>
                    <input type="text" id="name-input" name="name" value="[users_name]" readonly required>
                </div>

                <div>
                    <label for="email-input">Sähköposti</label>
                    <input type="email" id="email-input" name="email" value="[users_email]" readonly required>
                </div>

                <div>
                    <label for="password-input">Salasana</label>
                    <input type="password" id="password-input" name="password" value="********" readonly required>
                </div>

                <div class="form-buttons">
                    <button type="button" id="edit-btn" onclick="toggleEdit()">Muokkaa</button>
                    <button type="submit" id="save-btn" style="display: none;">Tallenna</button>
                </div>
            </form>

        </div>

        <div class="varaukset info">

        <p>check out slide 10, here should be all the varaukset that the person has; THIS DIV has to be the second column</p>

        </div>

    </div>



</main>   
<?php include 'include/footer.php'; ?>