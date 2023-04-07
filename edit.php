<?php
        include 'autoryzacja1.php';
        $conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname)
        or die('Błąd połączenia z serwerem: '.mysqli_error($conn));
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
		<title>Edytuj film | Baza Filmów</title>
		<link rel="stylesheet" href="styles1.css" type="text/css">
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    </head>

    <body>
	 <header>
         <form action="index.php" method="post" id="main_page"><button id="main">
		 <i class="fa fa-film" style="font-size:4vw"></i>
		 Baza Filmów</button></form>
    </header>
	<?php
	
		//wyświetlanie danych filmu
        $result = mysqli_query($conn, "SELECT * FROM film WHERE film.film_id=".$_GET['film_id'].";")
        or die("Błąd w zapytaniu");
				
        echo '<form action="index.php" method="POST" class="insert">';
			while($row = mysqli_fetch_array($result))
			{
				echo '<input name="tytul" id="edit_in" class="insert_input" value="'.$row['tytuł'].'"><br>';
				echo '<input name="rok" class="insert_input" value="'.$row['rok_powstania'].'"><br>';
				echo '<input type="hidden" name="film_id" value="'.$row['film_id'].'">';
			}
		
		$result = mysqli_query($conn, "SELECT gatunek_filmu.gatunek_id, film.film_id, gatunek_filmu.id FROM film JOIN gatunek_filmu ON film.film_id = gatunek_filmu.film_id WHERE film.film_id=".$_GET['film_id'].";")
        or die("Błąd w zapytaniu");
		$row = mysqli_fetch_array($result);
		
		echo'<br><select name="gatunek_id" id="gatunek_id"> //wybieranie pierwszego gatunku
				<option disabled selected>Wybierz gatunek (wymagane)</option>';
				$result1 = mysqli_query($conn, "SELECT * FROM gatunek;");
				while($row1 = mysqli_fetch_array($result1))
				{
					echo "<option value='". $row1['gatunek_id'] ."'>" .$row1['nazwa'] ."</option>";
				}	  
				echo '</select><br><br>';
			echo '<input type="hidden" name="film_id" value="'.$row['film_id'].'">';
			echo '<input type="hidden" name="id" value="'.$row['id'].'">';
		
		$row = mysqli_fetch_array($result);
		if(isset($row['gatunek_id'])) //wybieranie drugiego gatunku jeśli drugi gatunek był już ustawiony
		{
			echo'<select name="gatunek_id2" id="gatunek_id2">
					<option disabled selected>Wybierz gatunek (opcjonalne)</option>';
					$result1 = mysqli_query($conn, "SELECT * FROM gatunek;");
					while($row1 = mysqli_fetch_array($result1))
					{
						echo "<option value='". $row1['gatunek_id'] ."'>" .$row1['nazwa'] ."</option>";
					}	  
					echo '</select><br><br>';
					echo '<input type="hidden" name="film_id" value="'.$row['film_id'].'">';
					echo '<input type="hidden" name="id2" value="'.$row['id'].'">';
		}
		
		else //wybieranie drugiego gatunku jeśli drugi gatunek nie był ustawiony
		{
			echo '<select name="gatunek_id3" id="gatunek_id3">
			<option disabled selected>Wybierz gatunek (opcjonalne)</option>';
			$result = mysqli_query($conn, "SELECT * FROM gatunek;");
			while($row = mysqli_fetch_array($result))
			{
				echo "<option value='". $row['gatunek_id'] ."'>" .$row['nazwa'] ."</option>";
			}	  
			echo '</select><br><br>';
		}
		
		echo '<button class="insert_button" type="submit" id=update name="send" value="update">Zapisz</button>';
		?>

    </body>

</html>
