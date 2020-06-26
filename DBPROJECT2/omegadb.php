<?php


  define( 'DBHOST_', 'acadmysqldb001p.uta.edu' );
  define( 'DBUSER_', 'sxk7794' ); 
  define( 'DBPASS_', '5190$Niggi' );
  define( 'DBNAME_', 'sxk7794' );  

  $conn = mysqli_connect(DBHOST_, DBUSER_, DBPASS_, DBNAME_);
  // Check connection
  if (!$conn) {
      die("Connection failed: " . mysqli_connect_error());
  }
  else{
    echo("<script>console_log('a string');</script>");
  }

?>