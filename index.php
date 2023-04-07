<?php

	include 'autoryzacja1.php';
    $conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname)
    or die('Blad polaczenia z serwerem: '.mysqli_error($conn));
	mysqli_query($conn, 'SET NAMES utf8');
	
	if(isset($_GET['delete_film']))
	{
		//usuwanie filmu
		mysqli_query($conn,"DELETE FROM gatunek_filmu WHERE film_id = ".$_GET['delete_film'].";");
		mysqli_query($conn,"DELETE FROM aktorzy_film  WHERE film_id = ".$_GET['delete_film'].";");
		mysqli_query($conn,"DELETE film FROM film WHERE film_id = ".$_GET['delete_film'].";");
		header("Location: index.php");
	}

	if(isset($_POST['film_id']))//edycja tytuł i rok
	{
		$result = mysqli_query($conn, "UPDATE film SET tytuł='".$_POST['tytul']."', rok_powstania='".$_POST['rok']."' WHERE film_id=".$_POST['film_id'].";");
	}
	if(isset($_POST['gatunek_id'])) //edycja gatunków
	{
		$result = mysqli_query($conn, "UPDATE gatunek_filmu SET gatunek_id = '".$_POST['gatunek_id']."' WHERE film_id='".$_POST['film_id']."' && gatunek_filmu.id = '".$_POST['id']."' ;");
	 
		if(isset($_POST['gatunek_id2'])) //jeśli oryginalnie były dwa gatunki, a po edycji również dwa
		{
			$result = mysqli_query($conn, "UPDATE gatunek_filmu SET gatunek_id = '".$_POST['gatunek_id2']."' WHERE film_id='".$_POST['film_id']."' && gatunek_filmu.id = '".$_POST['id2']."' ;");
		}
		if((!isset($_POST['gatunek_id2'])) && (isset($_POST['id2']))) //jeśli oryginalnie były dwa gatunki, a po edycji jeden
		{
			$result = mysqli_query($conn, "DELETE FROM gatunek_filmu WHERE id='".$_POST['id2']."';");
		}
	 
		if(isset($_POST['gatunek_id3'])) //jeśli oryginalnie był jeden gatunek, a po edycji dwa
		{
			$result = mysqli_query($conn, "INSERT INTO gatunek_filmu (film_id, gatunek_id) VALUES ('".$_POST['film_id']."', '".$_POST['gatunek_id3']."');");
		} 
	}

	if(isset($_POST['szukaj'])) //wyszukiwanie wyników
	{
		$value_to_search = $_POST['tytul']; 
		$query = "SELECT film.film_id, film.tytuł, film.rok_powstania, group_concat(gatunek.nazwa) FROM film JOIN gatunek_filmu ON film.film_id = gatunek_filmu.film_id JOIN gatunek ON gatunek_filmu.gatunek_id = gatunek.gatunek_id WHERE tytuł LIKE '%".$value_to_search."%' GROUP BY film.film_id ;";
		$search_result = search($query); //wynik wyszukiwania
	}
	
	else //wszystkie filmy w bazie
	{
		$query = "SELECT film.film_id, film.tytuł, film.rok_powstania, group_concat(gatunek.nazwa) FROM film JOIN gatunek_filmu ON film.film_id = gatunek_filmu.film_id JOIN gatunek ON gatunek_filmu.gatunek_id = gatunek.gatunek_id GROUP BY film.film_id;";
		$search_result = search($query);
	}
	
	function search($query) 
	{
		include 'autoryzacja1.php';
		$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
		$filter_result = mysqli_query($conn, $query);
		return $filter_result;
	}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Baza Filmów</title>
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
	<div>
	<form action ="index.php" method="post">
		<input type="text" id="tytul" name="tytul" placeholder="Wyszukaj film">
		<button type="submit" name="szukaj" id="search" value="Szukaj">Szukaj</button>
		<button id="reset" type="submit">Resetuj</button>
		<a href="insert.php" id="insert">Brak filmu na liście? Dodaj go!</a>
		<table>
			<tr>
				<th>Tytuł</th>
				<th>Rok powstania</th>
				<th>Gatunek</th>
				<th>Edycja</th>
				<th>Usuwanie</th>
			</tr>
			<?php 
			while ($row = mysqli_fetch_array($search_result))
			{
				echo '<tr>';
					echo '<td><a href="actors.php?film_id='.$row['film_id'].'">'.$row['tytuł'].'</a></td>';
					echo '<td>'.$row['rok_powstania'].'</td>';
					echo '<td>'.$row['group_concat(gatunek.nazwa)'].'</td>'; 
					echo '<td><a href="edit.php?film_id='.$row['film_id'].'">edytuj</a></td>';
					echo '<td>';
					if((isset($_GET['film_id']))&&($_GET['film_id']==$row['film_id'])) echo '<a href="index.php?delete_film='.$row['film_id'].'">potwierdź</a>';
					else echo '<a href="index.php?film_id='.$row['film_id'].'">usuń</a>';
					echo '</td>';
				echo '</tr>';
			}
			?>
		<table>
	</form>
	</div>

</body>
</html>
