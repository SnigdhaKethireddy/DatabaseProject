<?php

include 'omegadb.php';


if (isset($_POST['submit'])){

	$ssn = $_POST[ssn];
	$isbn = $_POST[isbn];
	$issue_date = $_POST[issue_date];



	$sql = "SELECT * from Borrow where Member_SSN =".$ssn." AND Book_ISBN=".$isbn." AND Issued_On='".$issue_date."'";
	$result = mysqli_query($conn, $sql);
	$rows = mysqli_num_rows($result);

	if($rows > 0){
		$sql = "SELECT * from Can_Lend where Book_ISBN =".$_POST['isbn'];
		$result = mysqli_query($conn, $sql);
		$row_ = mysqli_fetch_array($result,MYSQLI_ASSOC);
		$borrowed_count = $row_['Borrowed'];
		$avaliable_count = $row_['Available'];

		$avaliable_count = $avaliable_count+1;
		$borrowed_count = $borrowed_count-1;
		$sql = "UPDATE Can_Lend SET Available =".$avaliable_count.", Borrowed=".$borrowed_count." WHERE Book_ISBN=".$_POST['isbn'];
		mysqli_query($conn, $sql);

		$sql = "UPDATE Borrow SET Returned_Or_Not = 1, Accessed_By=".$_POST["ssn"].", Returned_On = CURRENT_DATE() where Member_SSN =".$ssn." AND Book_ISBN=".$isbn." AND Issued_On='".$issue_date."'";

		if (mysqli_query($conn, $sql)) {
			$sql = "SELECT Issued_On, Returned_Date, Returned_On, DATEDIFF(Returned_On, Issued_On) as days_borrowed, DATEDIFF(Returned_On, Returned_Date) as days_returned from Borrow where Member_SSN =".$ssn." AND Book_ISBN=".$isbn." AND Issued_On='".$issue_date."'";
			$result = mysqli_query($conn, $sql);
			$row_ = mysqli_fetch_array($result,MYSQLI_ASSOC);
			echo '<script>alert("Book Returned successfully.\n\n#### Return Receipt ####\nIssue Date is: '.$row_['Issued_On'].'\nDue Date is : '.$row_['Returned_Date'].'\nReturn Date is : '.$row_['Returned_On'].'\nNumber of Days Borrowed: '.$row_['days_borrowed'].'\nNumbers of days extra borrowed from actual: '.$row_['days_returned'].'");
			window.location.href = "return.html";</script>';
		}
		else{
			echo "Error: "  . "<br>" . mysqli_error($conn);
		}
	}
	else{
		echo '<script>alert("Invalid borrowing details entered. Please check again.");
		window.location.href = "return.html";</script>';
	}


	



	



}

?>