<?php
$niveau = '../../';

if (isset($_GET['id_artiste'])) {
    $id_artiste = $_GET['id_artiste'];
} else {
    $id_artiste = 0;
}
$arrMois=array ('Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août' , ' Septembre', 'Octobre', 'Novembre' , 'Décembre') ;
$arrJours=array ('Dimanche', 'Lundi', 'Mardi ', 'Mercredi' , ' Jeudi', 'Vendredi' , ' Samedi ' ) ;

	//Inclusion du fichier de configuration
    include $niveau . 'liaisons/php/config.inc.php';

	//Établissement de la chaine de requête
    $strRequete =  "SELECT
        artistes.id AS id_artiste,
        artistes.nom AS nom_artiste,
        artistes.description AS description_artiste,
        artistes.provenance AS provenance_artiste, 
        artistes.pays AS pays_artiste,
        artistes.site_web AS site_web_artiste,
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
    $arrInfosArtiste['pays_artiste']=$ligne['pays_artiste'];
    $arrInfosArtiste['site_web_artiste']=$ligne['site_web_artiste'];


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

	$pdosResultat->closeCursor();
    $pdoResultatEvenement->closeCursor();
    $pdoResultatArtisteSimilaire->closeCursor();

?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Fiche Artiste</title>
    <?php include $niveau . 'liaisons/fragments/headlinks.inc.html'; ?>
    <base href="<?php echo $niveau; ?>" />
</head>
<body>
<?php include $niveau . 'liaisons/fragments/entete.inc.php'; ?>
<div class="banner">
    <img class="banner__image" src="liaisons/images/artistes/<?php echo $id_artiste; ?>_1.jpg" alt="Bannière de l'artiste">
    <p class="banner__artist-name"><?php echo $arrInfosArtiste['nom_artiste']; ?></p>
</div>

<div class="info-header">
    <svg class="info-header__background" width="1440" height="144" viewBox="0 0 1440 144" fill="none" xmlns="http://www.w3.org/2000/svg">
    <path d="M0 44.3925C0 44.3925 109.245 47.9546 179.5 33.3274C386.069 -9.68053 506.628 50.0737 721 44.3925C862.066 40.6541 939.48 11.8948 1080.5 16.4974C1223.43 21.1623 1440 44.3925 1440 44.3925V143.241C1440 143.241 1234.2 120.019 1100.16 117.607C924.022 114.438 881.805 145.161 705.6 143.241C557.582 141.628 423.649 114.032 275.76 117.607C166.455 120.25 0 143.241 0 143.241V44.3925Z" fill="#FF4FBC"/>
    <path d="M0 29.0651C0 29.0651 109.245 32.6272 179.5 18C386.069 -25.0079 506.628 34.7463 721 29.0651C862.066 25.3267 939.48 -3.43261 1080.5 1.16998C1223.43 5.83492 1440 29.0651 1440 29.0651V127.913C1440 127.913 1234.2 104.691 1100.16 102.28C924.022 99.1109 881.805 129.834 705.6 127.913C557.582 126.3 423.649 98.7042 275.76 102.28C166.455 104.922 0 127.913 0 127.913V29.0651Z" fill="#7720D4"/>
    </svg>
    <h2 class="info-header__title">Informations</h2>
</div>

<div class="artist-info">
    <img class="artist-info__photo" src="liaisons/images/artistes/<?php echo $id_artiste; ?>_<?php echo rand(1, 5) ?>.jpg" alt="Image de l'artiste">
    <div class="artist-info__details">
        <?php
        echo "<p class='artist-info__provenance'>" . $arrInfosArtiste['provenance_artiste'] . ", " . $arrInfosArtiste['pays_artiste'] . "</p>";
        echo "<p class='artist-info__style'>" . $arrInfosArtiste['style_musical'] . "</p>";
        echo "<p class='artist-info__website'><a class='artist-info__website__link' href='" . $arrInfosArtiste['site_web_artiste'] . "'>" . $arrInfosArtiste['site_web_artiste'] . "</a></p>";
        ?>
    </div>
</div>

<p class="artist-description"><?php echo $arrInfosArtiste['description_artiste']; ?></p>

<div class="event-list">
    <?php
    if (count($arrEvenements) > 0) { 
        foreach ($arrEvenements as $index => $evenement) { 
            echo '<p class="event-list__location">' . $evenement['nom_lieu'] . '</p>';
            
            $moisTexte = $arrMois[$evenement['mois_evenement'] - 1];
            $jourSemaineTexte = $arrJours[array_search($evenement['jour_semaine_evenement'], ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'])];
            
            echo '<p class="event-list__date">' . $jourSemaineTexte . ' ' . $evenement['jour_evenement'] . ' ' . $moisTexte . ' ' . $evenement['annee_evenement'] . ' à ' . $evenement['heure_evenement'] . 'h' . $evenement['minute_evenement'] . '</p>';
            
            echo '<img class="event-list__image" src="liaisons/images/artistes/' . $id_artiste . '_' .  rand(1, 5) . '.jpg" alt="Image de l\'événement">'; 

            if ($index < count($arrEvenements) - 1) {
                echo '<div class="event-list__separator"></div>';
            }
        }
    } else {
        echo '<p class="event-list__no-event">Aucun événement trouvé.</p>';
    }
    ?>
</div>

<h2 class="similar-artists__title">Vous pourriez aussi aimer</h2>
<div class="similar-artists">
<?php
    if (count($arrArtisteSimilaire) > 0) { 
        $count = 0; // Initialise le compteur
        foreach ($arrArtisteSimilaire as $artisteSimilaire) {
            if ($count >= 3) break; // Arrête la boucle après 3 artistes
            echo '<div class="similar-artists__item">';
            echo '<a class="similar-artists__link" href="artistes/fiches/index.php?id_artiste=' . $artisteSimilaire['id_artiste'] . '">';
            echo $artisteSimilaire['nom_artiste'];
            echo '<img class="similar-artists__image" src="liaisons/images/artistes/' . $artisteSimilaire['id_artiste'] . '_' . rand(1, 5) . '.jpg" alt="Image de l\'artiste similaire">';
            echo '</a>';
            echo '</div>';
            $count++; // Incrémente le compteur
        }
    } else {
        echo '<p class="similar-artists__no-artist">Aucun artiste similaire trouvé.</p>'; 
    }
?>

    
</div>

<?php include $niveau . 'liaisons/fragments/piedDePage.inc.php'; ?>
</body>
</html>
