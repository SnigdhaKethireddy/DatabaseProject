<?php



include 'omegadb.php';


if (isset($_POST['submit'])){

	$ssn = $_POST[ssn];

	
	$sql = "SELECT * from Members where SSN =".$ssn;
	$result = mysqli_query($conn, $sql);
	$rows = mysqli_num_rows($result);

	if($rows > 0){


		$sql = "SELECT * from Members where SSN =".$ssn." and Card_Number IS EMPTY";
		$result = mysqli_query($conn, $sql);
		$rows = mysqli_num_rows($result);

		if($rows > 0){
			echo '<script>alert("Card for member with ssn '.$ssn.' is not yet generated. Please first generate card.");
			window.location.href = "gencard.html";</script>';
		}
		else{
			$sql = "SELECT * from Members where SSN =".$ssn;
			$result = mysqli_query($conn, $sql);
			$rows = mysqli_num_rows($result);
			$row_ = mysqli_fetch_array($result,MYSQLI_ASSOC);

			$sql = "SELECT DATEDIFF(CURRENT_DATE(), '".$row_['Expiry_Date']."') as noofdays_for_expiry";
			$result = mysqli_query($conn, $sql);
			$row_ = mysqli_fetch_array($result,MYSQLI_ASSOC);
			$days = $row_['noofdays_for_expiry'];
			if( $days < 0){
				$days = $days*(-1);
				echo '<script>alert("Card for member with ssn '.$ssn.' is not yet expired. Please come back after '.$days.' days");
				window.location.href = "renew.html";</script>';
			}
			else{
				$sql = "UPDATE Members set Card_By_SSN =".$_POST["ssn"].", Issue_Date = CURRENT_DATE(), Expiry_Date = DATE_ADD(CURRENT_DATE(), INTERVAL 48 MONTH), Notice_Date = DATE_ADD(CURRENT_DATE(), INTERVAL 47 MONTH) WHERE SSN = ".$ssn;
				if (mysqli_query($conn, $sql)) {
					$sql = "SELECT * from Members where SSN =".$ssn;
					$result = mysqli_query($conn, $sql);
					$row_ = mysqli_fetch_array($result,MYSQLI_ASSOC);

					echo '<script>alert("Card for member with ssn '.$ssn.' has been renewed.\nCard renewed  by librarian with ssn: '.$row_['Card_By_SSN'].'\nCard Number(remains same): '.$row_['Card_Number'].'\nNew Notice_Date: '.$row_['Notice_Date'].'\nNew Expiry Date: '.$row_['Expiry_Date'].'");
					window.location.href = "renew.html";</script>';	
				 	  	
				}
				else{
				    echo "Error: "  . "<br>" . mysqli_error($conn);
				}
			}		
		}






	}
	else{
		echo '<script>alert("Member with ssn '.$ssn.' is not found. please add it first.");
		window.location.href = "addmem.html";</script>';
	}
	
	



}

?>