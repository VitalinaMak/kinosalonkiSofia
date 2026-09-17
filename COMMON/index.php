<?php 
    $pageTitle = "Index";
    $extraCSS = "../CSS/index.css";
    include '../INCLUDE/header.php'; 
?>


<main class="index_page"> 
      <div class="about">
          <!-- Pictures and "history" here -->
<!--           
          <div class="photos">
            <img src="KUVAT/interior.jpg" alt="kinosalonki sisällä">
            <img src="KUVAT/building.jpg" alt="piha">
            <img src="KUVAT/posters.webp" alt="kuvat">
          </div> -->
          
          <p>Vuonna 1844 Sofia Lybecker perustui köyhille tytöille ja orpolapsille tarkoitettu Lybeckerin tyttökoulu hänen äitinsä perintörahoilla. Koulu toimi aluksi ilman omaa tilaa, mutta vuonna 1859 Sofian sisko Helene Bergbom ja hänen miehensä Carl Gustaf lahjoittivat koululle omistamansa talon nykyiseltä Reiponkadulta. Koulu toimi tässä talossa seuraavat 125 vuotta. Entistä koulurakennusta kutsutaan nykyään Sofian taloksi ja siinä toimii Kinosalonki Sofia. Me jatkamme Sofian talon kasvatustyötä yhteisöpedagogisissa kulttuurihankkeissamme sekä järjestämällä ja tukemalla elokuva- ja mediakasvatusta.</p>    
      </div>
          
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
        <a href="../USER/tapahtumat.php" class="btn btn-outline-danger">Katso kaikki tapahtumat</a>  
    </main>

<?php include '../INCLUDE/footer.php'; ?>