<?php  

	$pageTitle = 'Index';
	session_start();

	include 'init.php';

	$stmt = $bdd->prepare('SELECT * FROM Livre ORDER BY id DESC');
	$stmt->execute();
	$livres = $stmt->fetchAll();


	echo '<div class="flex-container">';

		foreach ($livres as $livre)
		{
			echo '<div class= "zone">';
			echo '<a href="livres.php?id=' . $livre['id'] . '"><img src="../admin/img/' . $livre['image'] . '"></a>';
			echo '</div>';

		}

	echo '</div>';

?>

<?php

	include $tpl . 'footer.php';
	
?>