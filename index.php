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
        
        <?php 
          $date = date('d.m.Y');  //gets current date
          echo "<p> Tapahtumat tänään, $date:</p>"
        ?>
        <table>
            <tbody>
              <?php
              /* printing out the date, time and the name of event (test) */
                $sql = "SELECT event_date, event_name FROM events;";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                  while ($row = $result->fetch_assoc()) {
                    echo "<tr><td>".$row['event_date']."</td><td>".$row['event_name']."</td></tr>";
                  }
                }
              ?>
            </tbody>
        </table>

        <!-- цвета не окончательные, пока что я просто взяла предустановленные, потом по окружению будет понятно, какие лучше взять -->
        <a href="tapahtumat.php" class="btn btn-outline-danger">интерактивная(?) кнопка</a>  
        
    </div>    

    </main>

<?php include 'include/footer.php'; ?>