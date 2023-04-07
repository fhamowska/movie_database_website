<?php

	include 'autoryzacja1.php';
    $conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname)
    or die('Blad polaczenia z serwerem: '.mysqli_error($conn));
	mysqli_query($conn, 'SET NAMES utf8');

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Dodaj film | Baza Filmów</title>
    <link rel="stylesheet" href="styles1.css" type="text/css">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;500&display=swap" rel="stylesheet"><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
	 <header>
         <form action="index.php" method="post" id="main_page"><button id="main">
		 <i class="fa fa-film" style="font-size:4vw"></i>
		 Baza Filmów</button></form>
    </header>
	
	<?php
	
	if(isset($_POST['tytul'])) //dodwania filmu
	{
		mysqli_query($conn, "INSERT INTO film (tytuł, rok_powstania) VALUES ('".$_POST['tytul']."','".$_POST['rok']."');");
		$last_id = mysqli_insert_id($conn);
		
		if((!empty($_POST['gatunek_id']))&&(!empty($_POST['gatunek_id2']))) //jeśli dodane zostają dwa gatunki
		{
			mysqli_query($conn, "INSERT INTO gatunek_filmu (film_id, gatunek_id) VALUES ('".$last_id."','".$_POST['gatunek_id']."'),('".$last_id."','".$_POST['gatunek_id2']."');");
		}
		else //jeśli dodawany jest jeden gatunek
		{
			mysqli_query($conn, "INSERT INTO gatunek_filmu (film_id, gatunek_id) VALUES ('".$last_id."','".$_POST['gatunek_id']."');");
		}
		header('Location: index.php');
	}
	else
	{

	    echo'<form id="insert_movie" class="insert" "action="insert.php" method="post">
        <label for="tytul" class="insert_label">Tytuł:</label><br>
        <input type="text" id="tytul" name="tytul" class="insert_input" required><br>
        <label for="rok" class="insert_label">Rok powstania:</label><br>
        <input type="text" id="rok" name="rok" class="insert_input" required><br><br>';
		
		//wybór kategorii 
		echo'<select name="gatunek_id" id="gatunek_id" required> 
			<option disabled selected>Wybierz gatunek (wymagane)</option>';
			$result = mysqli_query($conn, "SELECT * FROM gatunek;");
			while($row = mysqli_fetch_array($result))
			{
				echo "<option value='". $row['gatunek_id'] ."'>" .$row['nazwa'] ."</option>";
			}	  
			echo '</select><br><br>';
			
		//wybór drugiej kategorii
		echo'<select name="gatunek_id2" id="gatunek_id2">
		<option disabled selected>Wybierz gatunek (opcjonalne)</option>';
        $result = mysqli_query($conn, "SELECT * FROM gatunek;");
        while($row = mysqli_fetch_array($result))
        {
            echo "<option value='". $row['gatunek_id'] ."'>" .$row['nazwa'] ."</option>";
        }	  
		echo '</select><br><br>';
		
		echo'<button type="submit" value="Submit" class="insert_button">Dodaj</button><br><br>
		</form>';
	}
	?>
	
</body>
</html>
