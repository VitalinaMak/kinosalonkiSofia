<?php 
    $pageTitle = "Index";
    $extraCSS = "../CSS/index.css";
    include '../INCLUDE/header.php'; 
?>

<main class="index_page"> 
  <div class="about menu" style="grid-area: menuarea">
    <p>Our menu lalala Rich espresso swirled with velvety steamed milk and a touch of house-made vanilla bean syrup. It tastes like a warm blanket on a chilly morning. Dark, decadent chocolate melted into a double shot of espresso, topped with whipped cream and cocoa dust. </p>    
    <a href="menu.php" class="button">Go to menu page</a>  
  </div>

  <div class="about today" style="grid-area: todayarea">
    <!-- PREVIOUS php: -->
    <div class="day">
      <?php 
        $date = date('d. F Y');  //gets current date
        echo "<h2> Tapahtumat tänään, $date</h2>";
        $date = date('Y-m-d');   //different date format for using in sql-query
      ?>
      <!-- <span><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M400-80 0-480l400-400 71 71-329 329 329 329-71 71Z"/></svg></span> -->
      <!-- <h2>11. Lokakuuta 2026</h2> -->
      <!-- <span><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="m321-80-71-71 329-329-329-329 71-71 400 400L321-80Z"/></svg></span> -->

    </div>
    
    <div class="eventList">
      <?php
      /* printing out the date, time and the name of event (test) */
        
      include '../MODULES/events_query.php';  //base query for events (NOTE: it's not completed here!). It uses variables $sql, $params[]
      
      $sql .= " WHERE events.event_date = :date GROUP BY events.id ORDER BY events.event_time;";  //end of the query
      
      $params[':date'] = $date;
      
      include "../MODULES/events_display.php";  //display events. It uses variables $sql, $params, $hasRows, $row, $bgColor, $typeForColor, $kuvaPath, $placesNumber, $ageLimit, $imageHtml 
      ?>

      <a href="../USER/tapahtumat.php" class="button">Katso kaikki tapahtumat</a>  
      
    </div> 
  </div>

  <div class="about history" style="grid-area: historyarea">
    <p>Vuonna 1844 Sofia Lybecker perustui köyhille tytöille ja orpolapsille tarkoitettu Lybeckerin tyttökoulu hänen äitinsä perintörahoilla. Koulu toimi aluksi ilman omaa tilaa, mutta vuonna 1859 Sofian sisko Helene Bergbom ja hänen miehensä Carl Gustaf lahjoittivat koululle omistamansa talon nykyiseltä Reiponkadulta. Koulu toimi tässä talossa seuraavat 125 vuotta. Entistä koulurakennusta kutsutaan nykyään Sofian taloksi ja siinä toimii Kinosalonki Sofia. Me jatkamme Sofian talon kasvatustyötä yhteisöpedagogisissa kulttuurihankkeissamme sekä järjestämällä ja tukemalla elokuva- ja mediakasvatusta.</p> 
  </div>

  
</main>

<?php include '../INCLUDE/footer.php'; ?>