<?php 
    $pageTitle = "Index";
    $extraCSS = "../CSS/index.css";
    include '../INCLUDE/header.php'; 
?>

<main class="index_page"> 
      <div class="about history" style="grid-area: historyarea">
        <p>Vuonna 1844 Sofia Lybecker perustui köyhille tytöille ja orpolapsille tarkoitettu Lybeckerin tyttökoulu hänen äitinsä perintörahoilla. Koulu toimi aluksi ilman omaa tilaa, mutta vuonna 1859 Sofian sisko Helene Bergbom ja hänen miehensä Carl Gustaf lahjoittivat koululle omistamansa talon nykyiseltä Reiponkadulta. Koulu toimi tässä talossa seuraavat 125 vuotta. Entistä koulurakennusta kutsutaan nykyään Sofian taloksi ja siinä toimii Kinosalonki Sofia. Me jatkamme Sofian talon kasvatustyötä yhteisöpedagogisissa kulttuurihankkeissamme sekä järjestämällä ja tukemalla elokuva- ja mediakasvatusta.</p> 
        <a href="../USER/tapahtumat.php" class="button">Katso kaikki tapahtumat</a>  
      </div>

      <div class="about menu" style="grid-area: menuarea">
        <p>Our menu lalala Rich espresso swirled with velvety steamed milk and a touch of house-made vanilla bean syrup. It tastes like a warm blanket on a chilly morning. Dark, decadent chocolate melted into a double shot of espresso, topped with whipped cream and cocoa dust. </p>    
        <a href="menu.php" class="button">Go to menu page</a>  
      </div>

      <div class="about today" style="grid-area: todayarea">
        <?php 
          $date = date('d. F Y');  //gets current date
          echo "<h2> Tapahtumat tänään, $date:</h2>";
          $date = date('Y-m-d');   //different date format for using in sql-query
        ?>
        
        <div class="eventList">
          <?php
          /* printing out the date, time and the name of event (test) */
            
          include '../MODULES/events_query.php';  //base query for events (NOTE: it's not completed here!). It uses variables $sql, $params[]
          
          $sql .= " WHERE events.event_date = :date GROUP BY events.id ORDER BY events.event_time;";  //end of the query
          
          $params[':date'] = $date;
          
          include "../MODULES/events_display.php";  //display events. It uses variables $sql, $params, $hasRows, $row, $bgColor, $typeForColor, $kuvaPath, $placesNumber, $ageLimit, $imageHtml 
          ?>
        </div> 
      </div>
  
    </main>

<?php include '../INCLUDE/footer.php'; ?>