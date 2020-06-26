<?php


include 'omegadb.php';

if (isset($_POST['submit'])){

	$ssn = $_POST[ssn];
	$campusadd = $_POST[campusadd];
	$phonenum = $_POST[phonenum];
	$typeofmem = $_POST[typeofmem];
	$homeadd = $_POST[homeadd];

	
	
	$sql = "SELECT * from Members where SSN =".$ssn;
	$result = mysqli_query($conn, $sql);
	$rows = mysqli_num_rows($result);


	if($rows > 0){
		echo '<script>alert("Member with ssn '.$ssn.' is already present in the database");
		window.location.href = "addmem.html";</script>';
	}
	else{
	

		$sql = "INSERT into Members values (".$ssn.", '".$campusadd."','".$phonenum."', NULL, NULL, NULL, NULL, NULL, CURRENT_DATE(), ".$_POST["ssn"].")";

		if (mysqli_query($conn, $sql)) {
			if (strcmp($typeofmem, "genmem") == 0) {
				$sql = "INSERT into Gen_Member values (".$ssn.", '".$campusadd."')";
				if (mysqli_query($conn, $sql)) {
					echo '<script>alert("Member successfully added");
					window.location.href = "addmem.html";</script>';
				}
				else{
					echo "Error: "  . "<br>" . mysqli_error($conn);
				}
			}
			else{
				$sql = "INSERT into Professor values (".$ssn.")";
				if (mysqli_query($conn, $sql)) {
					echo '<script>alert("Member successfully added");
					window.location.href = "addmem.html";</script>';
				}
				else{
					echo "Error: "  . "<br>" . mysqli_error($conn);
				}
			}
			
		}
		else{
			echo "Error: "  . "<br>" . mysqli_error($conn);
		}
	}



}

?>