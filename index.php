<?php 
    $pageTitle = "Index";
    include 'include/header.php'; 
?>


<main class="index_page"> 
      <h1>Why do we need an h1 here?</h1>
      <div class="about">
          <!-- Pictures and "history" here -->
          
          <p> Replace this paragraph with img property </p>
          <!-- <img src="url" alt="alternatetext"> -->
          
          <p> Kinosalonki Sofia established 2019, history bla bla bla Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip 
          </p>    
      </div>
          
      <div class="upcomingEvents">
          <?php 
            $date = date('d. F Y');  //gets current date
            echo "<h3> Tapahtumat tänään, $date:</h3>";
            $date = date('Y-m-d');   //different date format for using in sql-query
          ?>
          <table class="eventsToday">
              <tbody>
                <?php
                /* printing out the date, time and the name of event (test) */
                  $sql = "SELECT id, HOUR(event_time) AS event_hour, DATE_FORMAT(event_time, '%i') AS event_minute, event_name, location, max_visitors FROM events WHERE event_date = '$date';";  //DATE_FORMAT(event_time, '%i') returns minutes in 2-digits format
                  $result = $conn->query($sql);
                  if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {  //$row here is an associative array that contains one row of the table at a time
                      echo "<tr onclick=\"window.location='bookEvent.php?id=" . $row['id'] . "'\"><td>".$row['event_hour'].":".$row['event_minute']."</td><td style='font-weight: bold'>".$row['event_name']."</td><td>".$row['location']."</td><td>".$row['max_visitors']." paikkaa jäljellä</td></tr>";  //now it gets only max number of places for the event
                    }
                  }
                ?>
              </tbody>
          </table>
          <!-- цвета не окончательные, пока что я просто взяла предустановленные, потом по окружению будет понятно, какие лучше взять -->
          <a href="tapahtumat.php" class="btn btn-outline-danger">Katso kaikki tapahtumat</a>  
        </div>   
    </main>

<?php include 'include/footer.php'; ?>