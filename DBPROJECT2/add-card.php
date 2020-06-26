

<?php


include 'omegadb.php';


if (isset($_POST['submit'])){

	$ssn = $_POST[ssn];

	
	$sql = "SELECT * from Members where SSN =".$ssn;
	$result = mysqli_query($conn, $sql);
	$rows = mysqli_num_rows($result);

	if($rows > 0){


		$sql = "SELECT * from Members where SSN =".$ssn." and Card_Number IS Empty";
		$result = mysqli_query($conn, $sql);
		$rows = mysqli_num_rows($result);

		if($rows > 0){
			$sql = "SELECT max(Card_Number) as max_card_num from Members";
			$result = mysqli_query($conn, $sql);
			$row_ = mysqli_fetch_array($result,MYSQLI_ASSOC);
			$new_card_num = $row_['max_card_num']+1;

			$sql = "UPDATE Members set Card_By_SSN =".$_POST["ssn"].", Card_Number=".$new_card_num.", Issued_On = CURRENT_DATE(), Notice_Date = DATE_ADD(CURRENT_DATE(), INTERVAL 47 MONTH), Expiry_Date = DATE_ADD(CURRENT_DATE(), INTERVAL 48 MONTH) WHERE SSN = ".$ssn;
			if (mysqli_query($conn, $sql)) {
				$sql = "SELECT * from Members where SSN =".$ssn;
				$result = mysqli_query($conn, $sql);
				$row_ = mysqli_fetch_array($result,MYSQLI_ASSOC);

				echo '<script>alert("Card for member with ssn '.$ssn.' has been generated.\nCard Created  by librarian with ssn: '.$row_['Card_By_SSN'].'\nCard Number: '.$row_['Card_Number'].'\nNotice_Date: '.$row_['Notice_Date'].'\nExpiry Date: '.$row_['Expiry_Date'].'");
				window.location.href = "home.html";</script>';	
			 	  	
			}
			else{
			    echo "Error: "  . "<br>" . mysqli_error($conn);
			}

		}
		else{
			echo '<script>alert("Card for member with ssn '.$ssn.' is already generated.");
			window.location.href = "home.html";</script>';	
		}






	}
	else{
		echo '<script>alert("Member with ssn '.$ssn.' is not found. please add it first.");
		window.location.href = "addmem.html";</script>';
	}
	
	



}

?>