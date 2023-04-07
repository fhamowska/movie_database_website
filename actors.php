<?php
        include 'autoryzacja1.php';
        $conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname)
        or die('Błąd połączenia z serwerem: '.mysqli_error($conn));
		
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Aktorzy w filmie | Baza Filmów</title>
        <meta charset="UTF-8">
		<title>Aktorzy | Baza Filmów</title>
		<link rel="stylesheet" href="styles1.css" type="text/css">
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;500&display=swap" rel="stylesheet">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    </head>

    <body>

	<header>
         <form action="index.php" method="post" id="main_page"><button id="main">
		 <i class="fa fa-film" style="font-size:4vw"></i>
		 Baza Filmów</button></form>
	</header>
		
	<?php 
		
	if(isset($_GET['ins_film'])) //dodawanie aktora do filmu
	{
		echo'<form action="actors.php?film_id='.$_GET['ins_film'].'" method="post" class="insert">
			<label for="imie" class="insert_label">Imię:</label><br>
			<input type="text" id="imie" name="imie" class="insert_input"><br>
			<label for="nazwisko" class="insert_label">Nazwisko:</label><br>
			<input type="text" id="nazwisko" name="nazwisko" class="insert_input"><br>
			<label for="rok_urodzenia" class="insert_label">Rok urodzenia:</label><br>
			<input type="text" id="rok_urodzenia" name="rok_urodzenia" class="insert_input"><br><br>
			<input type="hidden" name="film_id" value="'.$_GET['ins_film'].'">
			<input type="submit" value="Dodaj" class="insert_button"><br><br>
		</form>';
	}
	
	else
	{
	
		if(isset($_GET['delete_aktor'])) //usuwanie aktora z danego filmu
		{
			mysqli_query($conn,"DELETE FROM aktorzy_film WHERE film_id=".$_GET['delete_film']." && aktor_id=".$_GET['delete_aktor']." ;");
			header('Location: actors.php?film_id='.$_GET['delete_film'].'&aktor_id='.$_GET['delete_aktor'].' ');
		}
		
		$tytul = mysqli_query($conn, "SELECT tytuł, rok_powstania, film_id FROM film WHERE film_id=".$_GET['film_id'].";");
		$row = mysqli_fetch_array($tytul);
		echo '<a id="dane">'.$row['tytuł'].' ('.$row['rok_powstania'].')</a>'; //dane filmu

		if((!empty($_POST['imie']))&&(!empty($_POST['nazwisko']))) //dodawanie aktora
		{
			mysqli_query($conn, "INSERT IGNORE INTO aktor(imie, nazwisko, rok_urodzenia) VALUES  ('".$_POST['imie']."','".$_POST['nazwisko']."','".$_POST['rok_urodzenia']."');"); 
			
			$last_id = mysqli_insert_id($conn);
			
			if(!empty($last_id)) //jeśli aktor był nowy w bazie i został dodany do tabeli aktorzy 
			{
				mysqli_query($conn, "
				INSERT INTO aktorzy_film(film_id,aktor_id) VALUES ('".$_POST['film_id']."','".$last_id."');"); //łączymy go z filmem
			}
			else //jeśli aktor istniał już w bazie
			{
				$result = mysqli_query($conn, "SELECT * FROM aktor WHERE imie='".$_POST['imie']."' AND nazwisko='".$_POST['nazwisko']."' AND rok_urodzenia='".$_POST['rok_urodzenia']."';");
				$actor = mysqli_fetch_array($result);
				mysqli_query($conn, "INSERT INTO aktorzy_film(film_id,aktor_id) VALUES ('".$_POST['film_id']."','".$actor['aktor_id']."');"); //łączenie istniejącego aktora i filmu
			}
		}
		
		echo'<br><a href="actors.php?ins_film='.$_GET['film_id'].'" id="insert_actor">Brak aktora? Dodaj go!</a>
		
		<table id="actors">
			<tr>
				<th>imię</th>
				<th>nazwisko</th>
				<th>wiek</th>
				<th>usuń</th>
			</tr>';
		$result = mysqli_query($conn, "SELECT * FROM aktor JOIN aktorzy_film ON aktor.aktor_id = aktorzy_film.aktor_id WHERE aktorzy_film.film_id =".$_GET['film_id'].";");
	
		while ($row = mysqli_fetch_array($result))
		{
			echo '<tr>
				<td><a href="actors_movies?aktor_id='.$row['aktor_id'].'.php">'.$row['imie'].'</a></td>
				<td><a href="actors_movies?aktor_id='.$row['aktor_id'].'.php">'.$row['nazwisko'].'</a></td>';
				$result1 = mysqli_query($conn, "SELECT (YEAR(CURDATE())-rok_urodzenia) AS wiek FROM aktor WHERE aktor_id = ".$row['aktor_id'].";");	//wiek aktora
				$row1 = mysqli_fetch_array($result1);
				echo '<td>'.$row1['wiek'].'</td>';
				echo '<td>';
				if((isset($_GET['aktor_id']))&&($_GET['aktor_id']==$row['aktor_id']))
				{
					echo '<a href="actors.php?delete_film='.$row['film_id'].'&delete_aktor='.$row['aktor_id'].'">potwierdź</a>';
				}
				else echo '<a href="actors.php?film_id='.$row['film_id'].'&aktor_id='.$row['aktor_id'].'">usuń</a>
				</td>
				</tr>';
		}
		echo '</table>';
	}	

	?>
			

    </body>

</html>