<?php 
    $pageTitle = "Index";
    include 'include/header.php'; 
?>

    <h1>Why do we need an h1 here?</h1>

    <main> 
    <div class="about">
        <!-- Pictures and "history" here -->
        
        <p> Replace this paragraph with img property </p>
        <!-- <img src="url" alt="alternatetext"> -->
        
        <p> Kinosalonki Sofia established 2019, history bla bla bla Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip 
        </p>    
    </div>
        
    <div class="upcomingEvents">
        
        <p> Tapahtumat tänään, 11 helmikuuta 2026:</p>
        <table>
            <tbody>
              <tr>
                <td>10.10</td>
                <td>Elokuva1</td>
                <td>5 paikkaa jäljellä</td>
              </tr>
              <tr>
                <td>12.00</td>
                <td>Elokuva2</td>
                <td>loppuunmyyty</td>
              </tr>
              <tr>
                <td>18.00</td>
                <td>Tapahtuma</td>
                <td>loppuunmyyty</td>
              </tr>
            </tbody>
        </table>

        <!-- Это ведь и будет ссылка на страницу всех tapahtumat? --da
         цвета не окончательные, пока что я просто взяла предустановленные, потом по окружению будет понятно, какие лучше взять -->
        <a href="tapahtumat.php" class="btn btn-outline-danger">интерактивная(?) кнопка</a>  
        
        <!-- твой код с css я пока скопировала в css-файл, но не до ссылок на него пока не сделано -->
    
    </div>    

    </main>

<?php include 'include/footer.php'; ?>