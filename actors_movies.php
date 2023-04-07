<?php
        include 'autoryzacja1.php';
        $conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname)
        or die('Błąd połączenia z serwerem: '.mysqli_error($conn));
		mysqli_query($conn, 'SET NAMES utf8');
?>
<!DOCTYPE html>
<html>
    <head>
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
		$result = mysqli_query($conn, "SELECT * FROM film JOIN aktorzy_film ON film.film_id=aktorzy_film.film_id WHERE aktorzy_film.aktor_id='".$_GET['aktor_id']."';")
        or die("Błąd w zapytaniu");
		
		$result1 = mysqli_query($conn, "SELECT imie, nazwisko FROM aktor WHERE aktor_id='".$_GET['aktor_id']."';");
		$row=mysqli_fetch_array($result1);
		echo '<a id="dane">'.$row['imie'].'&nbsp'.$row['nazwisko'].'</a>'; //dane aktora
		
	?>

	<div>
		<table id="movies">
			<tr>
				<th>Tytuł</th>
				<th>Rok powstania</th>
			</tr>
			<?php 
			while ($row = mysqli_fetch_array($result)) //filmy w których występuje dany aktor
			{
				echo '<tr>';
				echo '<td><a href="actors.php?film_id='.$row['film_id'].'">'.$row['tytuł'].'</a></td>';
				echo '<td>'.$row['rok_powstania'].'</td>';
				echo '</tr>';
			}
			?>
		<table>
	</div>
	
    </body>

</html>