<?php


include 'omegadb.php';


if (isset($_POST['submit'])){

	$ssn = $_POST[ssn];

	$sql = "SELECT * from Professor where MEM_SSN =".$ssn;
	$result = mysqli_query($conn, $sql);
	$rows = mysqli_num_rows($result);


	if($rows > 0){
		$graceperiod = 28;
	}
	else{
		$graceperiod = 7;
	}

	$sql = "SELECT * from Members where SSN =".$ssn;
	$result = mysqli_query($conn, $sql);
	$rows = mysqli_num_rows($result);

	if($rows > 0){
		$sql = "SELECT * from Can_Lend where Book_ISBN =".$_POST['isbn'];
		$result = mysqli_query($conn, $sql);
		$rows = mysqli_num_rows($result);

		if($rows > 0){
			$row_ = mysqli_fetch_array($result,MYSQLI_ASSOC);
			$borrowed_count = $row_['Borrowed'];
			$avaliable_count = $row_['Available'];
			if($avaliable_count > 0){

				$sql = "SELECT * from Borrow where Book_ISBN =".$_POST['isbn']." AND Member_SSN=".$ssn." AND Issue_On = CURRENT_DATE()";
				$result = mysqli_query($conn, $sql);
				$rows = mysqli_num_rows($result);

				if($rows > 0){
					echo '<script>alert("A person can borrow only one copy of a particular ISBN book on a particular day.");
					window.location.href = "borrow.html";</script>';
				}
				else{
					$sql = "SELECT * from Borrow where Member_SSN=".$ssn." AND Returned_Or_Not = 0";
					$result = mysqli_query($conn, $sql);
					$rows = mysqli_num_rows($result);

					if($rows < 5){
						$avaliable_count = $avaliable_count-1;
						$borrowed_count = $borrowed_count+1;
						$sql = "UPDATE Can_Lend SET Available =".$avaliable_count.", Borrowed=".$borrowed_count." WHERE Book_ISBN=".$_POST['isbn'];
						mysqli_query($conn, $sql);

						$sql = "INSERT into Borrow values ( ".$_POST['isbn'].", ".$ssn.", ".$ssn.", 0, CURRENT_DATE(), ".$graceperiod.", DATE_ADD(CURRENT_DATE(), INTERVAL ".$_POST['days']." DAY), 0, NULL, NULL)";

						if (mysqli_query($conn, $sql)) {

							$sql = "SELECT DATE_ADD(CURRENT_DATE(), INTERVAL ".$_POST['days']." DAY) as return_date, CURRENT_DATE() as issue_date";
							$result = mysqli_query($conn, $sql);
							$row_ = mysqli_fetch_array($result,MYSQLI_ASSOC);
							echo '<script>alert("Book Borrowed successfully.\n\n#### Issue Receipt ####\nIssue Date is: '.$row_['issue_date'].'\nReturn Date is : '.$row_['return_date'].'");
							window.location.href = "borrow.html";</script>';
						}
						else{
							echo "Error: "  . "<br>" . mysqli_error($conn);
						}
					}
					else{
						echo '<script>alert("Members are allowed to check out only 5 books at a time.");
						window.location.href = "borrow.html";</script>';
					}
				}

			}
			else{
				echo '<script>alert("No copies of this book are left in the library. Please try for another edition or binding.");
				window.location.href = "borrow.html";</script>';
			}

		}
		else{
			echo '<script>alert("Book might not be in the library or cannot be lend.");
			window.location.href = "borrow.html";</script>';	
		}
	}
	else{
		echo '<script>alert("Member not present in library. Please add member first.");
		window.location.href = "addmem.html";</script>';	
	}



	



}

?>