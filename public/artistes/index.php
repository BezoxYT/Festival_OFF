<?php $niveau="../../";?>
<?php include $niveau . "public\liaisons\php\config.inc.php";

if (isset($_GET['id_style'])){
	$id_style = $_GET['id_style'];
}else {
$id_style = 0;
}
error_reporting(E_ALL);

if ($id_style == 0){
	$strRequeteArtiste = 'SELECT 
	artistes.id AS id_artiste, artistes.nom AS nom_artiste, styles.nom AS style_musical
	FROM styles_artistes 
	INNER JOIN artistes ON styles_artistes.artiste_id = artistes.id
	INNER JOIN styles ON styles_artistes.id = styles.id
	ORDER BY nom_artiste ASC';
} else {   /* POUR POUVOIR FILTRER */
	$strRequeteArtiste = 'SELECT
	artistes.id AS id_artiste, artistes.nom AS nom_artiste, styles.nom AS style_musical
	FROM styles_artistes 
	INNER JOIN artistes ON styles_artistes.artiste_id = artistes.id
	INNER JOIN styles ON styles_artistes.id = styles.id
	WHERE styles.id =  ' . $id_style . '
	ORDER BY nom_artiste ASC';
}
$strRequeteStyle = 'SELECT id, nom FROM styles';

$pdoResultatArtiste = $objPdo->prepare($strRequeteArtiste);
$pdoResultatArtiste->execute();
$pdoResultatStyle = $objPdo->prepare($strRequeteStyle);
$pdoResultatStyle->execute();

$arrArtistes  = array();

while ($ligne = $pdoResultatArtiste->fetch()){
	$nom_artiste = $ligne['nom_artiste'];
	$id_artiste = $ligne['id_artiste'];
	$style_muscial = $ligne['style_musical'];

if (!isset($arrParticipant [$id_artiste])){
	$arrArtiste[$id_artiste] = array(
		'nom_artiste' =>$nom_artiste,
		'style_musical' => array()
	);
}
//$arrartiste[$id_artiste] ['style_musical'] [] = $style_musical;
}
$pdoResultatStyle->closeCursor();
$pdoResultatArtiste->closeCursor();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<title>liste participant</title>
	<?php 
	include $niveau . 'public/liaisons/fragments/headlinks.inc.html';
	?>

	
</head>
<body>
<?php
if (isset($id_style)) {
    $strRequeteStyle = 'SELECT id, nom FROM styles WHERE id=' . $id_style;
    $pdoResultatStyle = $objPdo->prepare($strRequeteStyle); 
    $pdoResultatStyle->execute(); 
    $participantaficher = $pdoResultatStyle->fetch();

    if (!$participantaficher) {
        $participantaficher = array('nom' => 'Tous les styles');
    }
}
?>

	<h1>Voici les artistes selon : <?php echo $participantaficher['nom']; ?></h1>
<?php 
foreach ($arrArtistes as $id_artiste => $id_artiste){
	echo "<a href='fiches/index.php?id_artistes={$id_artiste}'>";
	echo $details['nom_artiste'] . ' - ';

	$styleList = '';
	foreach ($details['styles_musical'] as $index => $style){
		if ($index >0){
			$styleList .= '/';
		}
		$styleList .= $style;
	}
	echo $styleList;
	echo "</a><br><br>";
}
?>

<br><br>
<?php 
foreach ($arrArtistes as $style) { ?>
<a href='index.php?id_style=<?php echo $style["id_style"]; ?>'>
	<?php echo $style["nom_style"]; ?>
</a><br><br>
<?php } ?>

</body>
</html>
