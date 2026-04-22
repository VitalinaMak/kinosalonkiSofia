<?php 
    $pageTitle = "Index";
    $extraCSS = "CSS/index.css";
    include 'include/header.php'; 
?>


<main class="index_page"> 
      <div class="about">
          <!-- Pictures and "history" here -->
          
          <div class="photos">
            <img src="kuvat/interior.jpg" alt="kinosalonki sisällä">
            <img src="kuvat/building.jpg" alt="piha">
            <img src="kuvat/posters.webp" alt="kuvat">
          </div>
          
          <p>Vuonna 1844 Sofia Lybecker perustui köyhille tytöille ja orpolapsille tarkoitettu Lybeckerin tyttökoulu hänen äitinsä perintörahoilla. Koulu toimi aluksi ilman omaa tilaa, mutta vuonna 1859 Sofian sisko Helene Bergbom ja hänen miehensä Carl Gustaf lahjoittivat koululle omistamansa talon nykyiseltä Reiponkadulta. Koulu toimi tässä talossa seuraavat 125 vuotta. Entistä koulurakennusta kutsutaan nykyään Sofian taloksi ja siinä toimii Kinosalonki Sofia. Me jatkamme Sofian talon kasvatustyötä yhteisöpedagogisissa kulttuurihankkeissamme sekä järjestämällä ja tukemalla elokuva- ja mediakasvatusta.</p>    
      </div>
          
      <?php 
        $date = date('d. F Y');  //gets current date
        echo "<h3> Tapahtumat tänään, $date:</h3>";
        $date = date('Y-m-d');   //different date format for using in sql-query
      ?>
      <div class="upcomingEvents">
          <table class="eventsToday">
              <tbody>
                <?php
                /* printing out the date, time and the name of event (test) */
                  $sql = "SELECT id, EXTRACT(HOUR FROM event_time) AS event_hour, TO_CHAR(event_time, 'MI') AS event_minute, event_name, location, max_visitors FROM events WHERE event_date = '$date';";  //DATE_FORMAT(event_time, '%i') returns minutes in 2-digits format
                  $result = $pdo->query($sql);
                  $rows = $result->fetchAll(PDO::FETCH_ASSOC);

                  if (!empty($rows)):
                    foreach ($rows as $row):
                  ?>
                    <tr onclick="window.location='bookEvent.php?id=<?= $row['id'] ?>'">
                        <td><?= $row['event_hour'] . ":" . $row['event_minute'] ?></td>
                        <td style="font-weight: bold"><?= htmlspecialchars($row['event_name']) ?></td>
                        <td><?= htmlspecialchars($row['location']) ?></td>
                        <td><?= $row['max_visitors'] ?> paikkaa jäljellä</td>
                    </tr>
                  <?php
                    endforeach;
                    else: ?>
                    <p class='nothingFound'>Tapahtumia ei löytynyt</p>
                  <?php endif; ?>
              </tbody>
          </table>
          <a href="tapahtumat.php" class="btn btn-outline-danger">Katso kaikki tapahtumat</a>  
        </div>   
    </main>

<?php include 'include/footer.php'; ?>