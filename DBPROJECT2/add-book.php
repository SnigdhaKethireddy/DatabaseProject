<?php

include 'omegadb.php';

if (isset($_POST['submit'])){
	


	$sql = "SELECT * from Book where ISBN =".$_POST['isbn'];
	$result = mysqli_query($conn, $sql);
	$rows = mysqli_num_rows($result);

	if($rows > 0){
		echo '<script>alert("Book ISBN '.$_POST['isbn'].' is already present in the database");
		window.location.href = "addbook.html";</script>';
	}
	else{

		$sql = "INSERT into Book values (".$_POST['isbn'].", '".$_POST['title']."','".$_POST['author']."','".$_POST['subject']."', ".$_POST["ssn"].", CURRENT_DATE())";

		if (mysqli_query($conn, $sql)) {
			if (strcmp($_POST['library'], "type_of_books") == 0) {
				$sql = "INSERT into Type_Of_Books values (".$_POST['isbn'].", '".$_POST['description']."', '".$_POST['binding']."', ".$_POST['edition'].")";
				if (mysqli_query($conn, $sql)) {
					if (strcmp($_POST['lent'], "can_lend") == 0) {
						$sql = "INSERT into Can_Lend values (".$_POST['isbn'].", 0, ".$_POST['total_count'].")";
						if (mysqli_query($conn, $sql)) {
							echo '<script>alert("Book successfully added to '.$_POST['library'].' and '.$_POST['lent'].' tables");window.location.href = "addbook.html";</script>';
						}
						else{
							echo "Error: "  . "<br>" . mysqli_error($conn);
						}
						
					}
					else{
						$sql = "INSERT into Cannot_Lend values (".$_POST['isbn'].", '".$_POST['type']."', ".$_POST['total_count'].")";
						if (mysqli_query($conn, $sql)) {
							echo '<script>alert("Book successfully added to '.$_POST['library'].' and '.$_POST['lent'].' tables");window.location.href = "addbook.html";</script>';
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
			else{
				$sql = "INSERT into To_Acquire values (".$_POST['isbn'].", '".$_POST['reason']."')";
				if (mysqli_query($conn, $sql)) {
					echo '<script>alert("Book successfully added to '.$_POST['library'].' table");window.location.href = "addbook.html";</script>';
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