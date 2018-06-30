<?php
$conn = mysqli_connect($dbServername, $dbUsername);
if(! $conn) {
            die('Could notconnect: ' . mysqli_error()
             }
echo 'Connected successfully<br>';
$sql='SELECT * FROM table';
$result=mysqli_query($conn, $sql);
$participants=array($result);
  shuffle($participants);
foreach($participants as $participant) {
      echo "$participant";
           }
mysqli_close($conn);
?>
