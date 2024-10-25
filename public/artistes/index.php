<?php
$niveau = '../../';

if (isset($_GET['id_style'])) {
    $id_style = $_GET['id_style'];
} else {
    $id_style = 0;
}
if(isset($_GET['id_page'])){
    $id_page = $_GET['id_page'];
}else{
    $id_page = 0;
}

error_reporting(E_ALL);

include $niveau . 'public/liaisons/php/config.inc.php';

	$intMaxParPage = 4;

$enregistrementDepart = $id_page * $intMaxParPage;

//Là, il faut faire une première requête pour connaître le nombre d'enregistrements total
//pour cela on utilise la fonction SQL COUNT()
if ($id_style == 0) {
    $strRequetePages = 'SELECT COUNT(*) AS nbEnregistrement FROM artistes';
} else {
    $strRequetePages = 'SELECT COUNT(*) AS nbEnregistrement FROM styles_artistes WHERE style_id = ' . $id_style;
}

$pdosResultatPages = $pdoConnexion->prepare($strRequetePages);
	$pdosResultatPages->execute();

	//On récupère l'enregistrement dans une variable
	$ligne = $pdosResultatPages->fetch();
	//On extrait la réponse de l'enregistrement et on sauvegarde dans une variable $ligne
	$intNbArtistes = $ligne['nbEnregistrement'];
	//On libère la requête
	$pdosResultatPages->closeCursor();
	//On détermine le nombre de pages en divisant le nombre de participant par le nombre de participants par page
	$nbPages = ceil($intNbArtistes / $intMaxParPage);

// Établissement de la chaîne de requête
if ($id_style == 0) {
    $strRequeteParticipant = 'SELECT 
        artistes.id AS id_artiste,
        artistes.nom AS nom_artiste, 
        GROUP_CONCAT(styles.nom SEPARATOR " / ") AS styles_musical
    FROM 
        styles_artistes
    JOIN 
        artistes ON styles_artistes.artiste_id = artistes.id
    JOIN 
        styles ON styles_artistes.style_id = styles.id
    GROUP BY 
        artistes.id
    ORDER BY 
        artistes.nom ASC
    LIMIT '. $enregistrementDepart . ',' . $intMaxParPage;

} else {
    $strRequeteParticipant = 'SELECT 
        artistes.id AS id_artiste,
        artistes.nom AS nom_artiste, 
        GROUP_CONCAT(styles.nom SEPARATOR " / ") AS styles_musical
    FROM 
        styles_artistes
    JOIN 
        artistes ON styles_artistes.artiste_id = artistes.id
    JOIN 
        styles ON styles_artistes.style_id = styles.id
    WHERE 
        styles.id = ' . $id_style . '
    GROUP BY 
        artistes.id
    ORDER BY 
        artistes.nom ASC
    LIMIT '. $enregistrementDepart . ',' . $intMaxParPage;
}


$strRequeteStyle = 'SELECT id, nom FROM styles';

$strRequeteArtisteVedette = 'SELECT
    artistes.id AS id_artiste,
    artistes.nom AS nom_artiste
    FROM
    artistes
    ORDER BY 
    nom_artiste ASC';

// Requête
$pdosResultatParticipant = $pdoConnexion->prepare($strRequeteParticipant);
$pdosResultatParticipant->execute();
$pdosResultatStyle = $pdoConnexion->prepare($strRequeteStyle);
$pdosResultatStyle->execute();
$pdosResultatArtisteVedette = $pdoConnexion->prepare($strRequeteArtisteVedette);
$pdosResultatArtisteVedette->execute();

// Tableau pour stocker les artistes
$arrParticipants = array();

// Récupération des résultats
while ($ligne = $pdosResultatParticipant->fetch()) {
    if ($ligne) {
        // L'accès aux valeurs n'est possible que si $ligne n'est pas false
        $nom_artiste = $ligne['nom_artiste'];
        $id_artiste = $ligne['id_artiste'];
        $style_musical = $ligne['styles_musical'];
    }

    // Ajouter l'artiste au tableau
    $arrParticipants[$id_artiste] = array(
        'nom_artiste' => $nom_artiste,
        'id_artiste' => $id_artiste,
        'styles_musical' => array()
    );

        
    // Ajouter le style musical à l'artiste
    $arrParticipants[$id_artiste]['styles_musical'][] = $style_musical;
}
for($intCptEnr=0; $intCptEnr < $pdosResultatParticipant->rowCount(); $intCptEnr++) {
    if ($ligne) {
        $arrParticipants[$intCptEnr]['nom_artiste'] = $ligne['nom_artiste'];
        $arrParticipants[$intCptEnr]['id_artiste'] = $ligne['id_artiste'];

        // Nouvelle requête pour récupérer les styles de l'artiste
        $id_artiste = $ligne['id_artiste'];
        $strRequeteStylesArtiste = 'SELECT styles.nom AS style_musical 
            FROM styles_artistes 
            JOIN styles ON styles_artistes.style_id = styles.id 
            WHERE styles_artistes.artiste_id = :id_artiste';

        // Préparer et exécuter la requête
        $pdosResultatStylesArtiste = $pdoConnexion->prepare($strRequeteStylesArtiste);
        $pdosResultatStylesArtiste->execute();

        // Stocker les styles musicaux dans un tableau
        $arrParticipants[$intCptEnr]['styles_musical'] = array();
        while ($ligneStyle = $pdosResultatStylesArtiste->fetch()) {
            $arrParticipants[$intCptEnr]['styles_musical'][] = $ligneStyle['style_musical'];
        }

        
        $pdosResultatStylesArtiste->closeCursor();


        $ligne = $pdosResultatParticipant->fetch();
    }
}

$arrArtistesSugg = array();
$ligne = $pdosResultatArtisteVedette->fetch();
for($intCptEnr=0; $intCptEnr < $pdosResultatArtisteVedette->rowCount(); $intCptEnr++) {
        $arrArtistesSugg[$intCptEnr]['nom_artiste'] = $ligne['nom_artiste'];
        $arrArtistesSugg[$intCptEnr]['id_artiste'] = $ligne['id_artiste'];
        $ligne = $pdosResultatArtisteVedette->fetch();
}

$nbArtistesChoisis = rand(3,5);
$arrArtistesChoisis = array();
for($intCpt=0; $intCpt < $nbArtistesChoisis; $intCpt++) {
    $intIndexHazard=rand(0,count($arrArtistesSugg)-1);
    array_push($arrArtistesChoisis, $arrArtistesSugg[$intIndexHazard]);
    array_splice($arrArtistesSugg, $intIndexHazard, 1);
}

   



// Récupération des styles
$arrStyles = array();
while ($ligne = $pdosResultatStyle->fetch()) {
    $arrStyles[] = array(
        'id_style' => $ligne['id'],
        'nom_style' => $ligne['nom']
    );
}

$pdosResultatParticipant->closeCursor();
$pdosResultatStyle->closeCursor();
$pdosResultatArtisteVedette->closeCursor();
?>

<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Liste des participants</title>
    <?php include $niveau . 'public/liaisons/fragments/headlinks.inc.html'; ?>
    <base href="<?php echo $niveau; ?>" />
</head>
<body>
    
    <?php include $niveau . 'public/liaisons/fragments/entete.inc.php'; ?>
    <?php
    if (isset($id_style)) {
        $strRequeteStyle = 'SELECT id, nom FROM styles WHERE id=' . $id_style;
        $pdosResultatStyle = $pdoConnexion->prepare($strRequeteStyle);
        $pdosResultatStyle->execute();
        $styleafficher = $pdosResultatStyle->fetch();

        if (!$styleafficher) {
            $styleafficher = array('nom' => 'Tous les styles');
        }
    }
    ?>
    <h1>Voici les artiste selon:  <?php echo $styleafficher['nom']; ?></h1>
    <?php foreach ($arrParticipants as $participant) { ?>
    <a href='public/artistes/fiches/index.php?id_artiste=<?php echo $participant["id_artiste"]; ?>'>
        <?php echo $participant["nom_artiste"]; ?>
    </a>
    <br>
    <?php echo implode(' / ', $participant['styles_musical']); ?>
    <br><br>
<?php } ?>
    <br><br>
    <a href="public/artistes/index.php?id_style=0">Tous les styles</a>
    <br><br>
    <?php
    foreach ($arrStyles as $style) { ?>
        <a href='public/artistes/index.php?id_style=<?php echo $style["id_style"]; ?>'> 
    <?php echo $style["nom_style"]; ?>
</a>
<br><br>


    <?php } ?>
    <h1>Artiste en vedette</h1>
<ul>
<?php
for($intCpt=0; $intCpt < count($arrArtistesChoisis); $intCpt++) {
    ?>
    <li>
        <a href='public/artistes/fiches/index.php?id_artiste=<?php echo $arrArtistesChoisis[$intCpt]['id_artiste'];?>'>
            <?php echo $arrArtistesChoisis[$intCpt]['nom_artiste'];?>
        </a>
    </li>
    <?php
} 


?>
</ul>
		<?php if($id_page>0){
			//Si la page courante n'est pas la première, afficher bouton précédent?>
			<a href='public/artistes/index.php?id_page=<?php echo $id_page-1;?>
            &id_style=<?php echo $id_style;?>'>Précédent</a>
		<?php }

        if($nbPages>1){
            //Si le nombre de pages est supérieur à 1, afficher les numéros de page
            for($i=0; $i<$nbPages; $i++){
                if($i==$id_page){
                    //Si c'est la page courante, afficher le numéro sans lien
                    echo ($i+1);
                }else{
                    //Sinon, afficher le numéro avec un lien
                    ?>
                    <a href='public/artistes/index.php?id_page=<?php echo $i;?>&id_style=<?php echo $id_style;?>'>
                        <?php echo ($i+1);?>
                    </a>
                    <?php
                }
            }
        }


		if($id_page<$nbPages-1){
			//Si la page courante n'est pas la dernière, afficher bouton suivant?>
			<a href='public/artistes/index.php?id_page=<?php echo $id_page+1;?>&id_style=<?php echo $id_style;?>'>Suivant</a>
		<?php } ?>
        

		<p>
		<?php
			//affiche le numéro de la page courante sur le total de page
			echo ($id_page+1)?> de <?php echo $nbPages;?>
		</p>
</body>
</html>

			
