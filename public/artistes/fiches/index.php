<?php
$niveau = '../../../';

if (isset($_GET['id_artiste'])) {
    $id_artiste = $_GET['id_artiste'];
} else {
    $id_artiste = 0;
}
$arrMois=array ('Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août' , ' Septembre', 'Octobre', 'Novembre' , 'Décembre') ;
$arrJours=array ('Dimanche', 'Lundi', 'Mardi ', 'Mercredi' , ' Jeudi', 'Vendredi' , ' Samedi ' ) ;

	//Inclusion du fichier de configuration
    include $niveau . 'public/liaisons/php/config.inc.php';

	//Établissement de la chaine de requête
    $strRequete =  "SELECT
        artistes.id AS id_artiste,
        artistes.nom AS nom_artiste,
        artistes.description AS description_artiste,
        artistes.provenance AS provenance_artiste, 
        styles.nom AS style_musical

        FROM
        styles_artistes
        JOIN 
        artistes ON styles_artistes.artiste_id = artistes.id
        JOIN 
        styles ON styles_artistes.style_id = styles.id
        WHERE 
        artistes.id = " . $id_artiste ;

        $strRequeteEvenements = "SELECT 
        evenements.id AS id_evenement,
       DAY(date_et_heure) AS jour_evenement,
        MONTH(date_et_heure) AS mois_evenement,
        YEAR(date_et_heure) AS annee_evenement,
        HOUR(date_et_heure) AS heure_evenement,
        MINUTE(date_et_heure) AS minute_evenement,
        DAYNAME(date_et_heure) AS jour_semaine_evenement,
        lieux.nom AS nom_lieu
        FROM
        evenements
        JOIN
        lieux ON evenements.lieu_id = lieux.id
        WHERE
        evenements.artiste_id = " . $id_artiste;

$strRequeteArtisteSimilaire = "SELECT DISTINCT artistes.id, nom 
FROM artistes 
INNER JOIN styles_artistes ON artistes.id=styles_artistes.artiste_id 
WHERE style_id IN (
    SELECT style_id 
    FROM styles_artistes 
    WHERE artiste_id = " . $id_artiste . "
) 
AND styles_artistes.artiste_id <> " . $id_artiste;

        


	//Initialisation de l'objet PDOStatement et exécution de la requête
	$pdosResultat = $pdoConnexion->prepare($strRequete);
	$pdosResultat->execute();
    $pdoResultatEvenement = $pdoConnexion->prepare($strRequeteEvenements);
    $pdoResultatEvenement->execute();
    $pdoResultatArtisteSimilaire = $pdoConnexion->prepare($strRequeteArtisteSimilaire);
    $pdoResultatArtisteSimilaire->execute();


	//Extraction de l'enregistrements de la BD
	$arrInfosArtiste=array();
	$ligne=$pdosResultat->fetch();
    $arrInfosArtiste['id_artiste']=$ligne['id_artiste'];
	$arrInfosArtiste['nom_artiste']=$ligne['nom_artiste'];
	$arrInfosArtiste['style_musical']=$ligne['style_musical'];
    $arrInfosArtiste['description_artiste']=$ligne['description_artiste'];
    $arrInfosArtiste['provenance_artiste']=$ligne['provenance_artiste'];


    $arrEvenements=array();
    while ($ligne = $pdoResultatEvenement->fetch()) {
        $arrEvenements[] = array(
            'id_evenement' => $ligne['id_evenement'],
            'jour_evenement' => $ligne['jour_evenement'],
            'mois_evenement' => $ligne['mois_evenement'],
            'annee_evenement' => $ligne['annee_evenement'],
            'heure_evenement' => $ligne['heure_evenement'],
            'minute_evenement' => $ligne['minute_evenement'],
            'jour_semaine_evenement' => $ligne['jour_semaine_evenement'],
            'nom_lieu' => $ligne['nom_lieu']
        );
    }
    $arrArtisteSimilaire=array();
    while ($ligne = $pdoResultatArtisteSimilaire->fetch()) {
        $arrArtisteSimilaire[] = array(
            'id_artiste' => $ligne['id'],
            'nom_artiste' => $ligne['nom']
        );
    }









	
    //Fermeture du curseur


	$pdosResultat->closeCursor();
    $pdoResultatEvenement->closeCursor();
    $pdoResultatArtisteSimilaire->closeCursor();
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Fiche Artiste</title>
    <?php include $niveau . 'public/liaisons/fragments/headlinks.inc.html'; ?>
</head>
<body>
<?php include $niveau . 'public/liaisons/fragments/entete.inc.php'; ?>
    <h1>Fiche du participant</h1>
	<?php
		//Affichage du participant et de son identifiant
		echo "Nom: " . $arrInfosArtiste['nom_artiste'] . "<br>description: " .$arrInfosArtiste['description_artiste'] . "<br>Provenance: " . $arrInfosArtiste['provenance_artiste'];
		echo "<br>style: " . $arrInfosArtiste['style_musical'];

    ?>
   <h2>Événements</h2>
<?php
if (count($arrEvenements) > 0) { // Vérifier si des événements existent
    foreach ($arrEvenements as $evenement) { // Parcourir chaque événement
        echo '<p>' . $evenement['nom_lieu'] . '</p>';
        
        $moisTexte = $arrMois[$evenement['mois_evenement'] - 1];  // Mois texte
        $jourSemaineTexte = $arrJours[array_search($evenement['jour_semaine_evenement'], ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'])];  // Jour texte
        
        echo $jourSemaineTexte . " " . $evenement['jour_evenement'] . " " . $moisTexte . " " . $evenement['annee_evenement'] . " à " . $evenement['heure_evenement'] . "h" . $evenement['minute_evenement'];
    }
} else {
    echo '<p>Aucun événement trouvé.</p>'; // Message si aucun événement n'est trouvé
}
?>
<h2>Artistes similaires</h2>
<?php
if (count($arrArtisteSimilaire) > 0) { // Vérifier si des artistes similaires existent
    foreach ($arrArtisteSimilaire as $artisteSimilaire) { // Parcourir chaque artiste similaire
        echo '<a href="index.php?id_artiste=' . $artisteSimilaire['id_artiste'] . '">' . $artisteSimilaire['nom_artiste'] . '</a><br>';
    }
} else {
    echo '<p>Aucun artiste similaire trouvé.</p>'; // Message si aucun artiste similaire n'est trouvé
}
?>

    

<p>
	<a href="../index.php">Retour</a>
</p>
</body>
</html>