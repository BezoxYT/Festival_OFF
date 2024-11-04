<?php $niveau="./";?>
<?php include ($niveau . "liaisons/php/config.inc.php");?>
<?php
$arrMois=array ('Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août' , ' Septembre', 'Octobre', 'Novembre' , 'Décembre') ;
$arrJours=array ('Dimanche', 'Lundi', 'Mardi ', 'Mercredi' , ' Jeudi', 'Vendredi' , ' Samedi ' ) ;
 
        $strRequeteActualites="SELECT
        titre,
        DAY(date_actualite) AS jour_actualite,
        MONTH(date_actualite) AS mois_actualite,
        YEAR(date_actualite) AS annee_actualite,
        HOUR(date_actualite) AS heure_actualite,
        MINUTE(date_actualite) AS minute_actualite,
        DAYNAME(date_actualite) AS jour_semaine_actualite,
        auteurs,
        article
        FROM
        actualites
        ORDER BY
        annee_actualite DESC,
        mois_actualite DESC,
        jour_actualite DESC
        LIMIT 0,3";
 
        $strRequeteArtisteVedette = 'SELECT
        artistes.id AS id_artiste,
        artistes.nom AS nom_artiste
        FROM
        artistes
        ORDER BY
        nom_artiste ASC';
 
 
        $pdosResultatActualites=$pdoConnexion->prepare($strRequeteActualites);
        $pdosResultatActualites->execute();
        $pdosResultatArtisteVedette = $pdoConnexion->prepare($strRequeteArtisteVedette);
        $pdosResultatArtisteVedette->execute();
        ?>
 
<?php
        $arrActualites=array ();
        for ($cptEnr=0;$ligneActualite=$pdosResultatActualites->fetch();$cptEnr++){
        $arrActualites[$cptEnr] ["titre"]=$ligneActualite["titre"];
        $arrActualites [$cptEnr] ["jour_actualite"]=$ligneActualite["jour_actualite"];
        $arrActualites [$cptEnr] ["jour_semaine_actualite"]=$ligneActualite ["jour_semaine_actualite"];
        $arrActualites[$cptEnr] ["mois_actualite"]=$ligneActualite["mois_actualite"];
        $arrActualites[$cptEnr] ["annee_actualite"]=$ligneActualite ["annee_actualite"];
        $arrActualites [$cptEnr] ["heure_actualite"]=$ligneActualite["heure_actualite"];
        $arrActualites [$cptEnr] ["minute_actualite"]=$ligneActualite["minute_actualite"];
        $arrActualites [$cptEnr] ["auteurs"]=$ligneActualite["auteurs"];
        //Coupe le texte en tableau
        $arrArticle=explode(" ",$ligneActualite["article"]);
        //Si plus grand que 45 mots
        if (count ($arrArticle) >45){
        //Couper le reste du texte
        array_splice($arrArticle,45, count ($arrArticle));
        }
        //Reprend le tableau et recompose le texte, stocke dans la propriété article du tableau
        $arrActualites [$cptEnr] ["article"]=implode(" ",$arrArticle) ;
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
 
 
        $pdosResultatActualites->closeCursor () ;
        $pdosResultatArtisteVedette->closeCursor();
        ?>
 
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Festival indépendant de découvertes musicales.">
    <meta name="keyword" content="Festival, OFF, artistes,  musique, découverte">
    <meta name="author" content="Guilhem Torres et Donovan Bezuidenhout">
    <meta charset="utf-8">
    <title>Festival OFF - Accueil</title>
    <link rel="shortcut icon" href="liaisons\images\favicons\logoOff_blanc.png" type="image/x-icon">
    <link rel="stylesheet" href="..\ressources\liaisons\scss\layout\_accueil.scss">
    <?php include ($niveau . "liaisons/fragments/headlinks.inc.html");?>
</head>
 
<body>
<a href="#contenu" class="sauter screen-reader-only focusable"> Aller au contenu</a>
    <?php include ($niveau . "liaisons/fragments/entete.inc.php");?>
    <main role="main">
    <section class="hero"> 
    <div class="hero__container">
    <h1 class="hero__h1">TOUT EST POSSIBLE</h1>
    <img class="hero__image" srcset="liaisons/images/off_accueil.jpg 400w, liaisons/images/off_accueil_1200x800.jpg 1200w"
         sizes="(max-width: 600px) 400px, 1200px"
         src="liaisons/images/off_accueil_400x200.jpg" alt="chaussure">
</div>
</section>

<section>
    <div class="titre">
    <svg class="titre__h1" width="1440" height="144" viewBox="0 0 1440 144" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M0 44.3925C0 44.3925 109.245 47.9546 179.5 33.3274C386.069 -9.68053 506.628 50.0737 721 44.3925C862.066 40.6541 939.48 11.8948 1080.5 16.4974C1223.43 21.1623 1440 44.3925 1440 44.3925V143.241C1440 143.241 1234.2 120.019 1100.16 117.607C924.022 114.438 881.805 145.161 705.6 143.241C557.582 141.628 423.649 114.032 275.76 117.607C166.455 120.25 0 143.241 0 143.241V44.3925Z" fill="#FF4FBC"/>
        <path d="M0 29.0651C0 29.0651 109.245 32.6272 179.5 18C386.069 -25.0079 506.628 34.7463 721 29.0651C862.066 25.3267 939.48 -3.43261 1080.5 1.16998C1223.43 5.83492 1440 29.0651 1440 29.0651V127.913C1440 127.913 1234.2 104.691 1100.16 102.28C924.022 99.1109 881.805 129.834 705.6 127.913C557.582 126.3 423.649 98.7042 275.76 102.28C166.455 104.922 0 127.913 0 127.913V29.0651Z" fill="#7720D4"/>
    </svg>
    <h2 class="titre__h2" id="contenu">Actualités</h2>
</div>
</section>
<?php
$images = [
    'liaisons/images/actu/babaloones_w190_actu.jpg',
    'liaisons/images/actu/harvest-breed_w190_actu.jpg',
    'liaisons/images/actu/latourelle-orkestra_w190_actu.jpg',
];
echo '<div class="cartes_actu">';
foreach ($arrActualites as $index => $actualite) {
    echo '<article class="carte">';
    $jourSemaineTexte = $arrJours[array_search($actualite['jour_semaine_actualite'], ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'])];
    echo '<p class="date">Publié le ' . $jourSemaineTexte . ' ' . $actualite["jour_actualite"] . ' ' . $arrMois[$actualite["mois_actualite"]] . ' ' . $actualite["annee_actualite"] . ' à ' . $actualite["heure_actualite"] . 'h' . $actualite["minute_actualite"] . ' par ' . $actualite["auteurs"] . '</p>';
    
    if (isset($images[$index])) {
        echo '<img class="album" src="' . $images[$index] . '" alt="Image de l\'actualité">';
    } else {
        echo '<img class="album" src="liaisons/images/actu/default_image.jpg" alt="Image par défaut">';
    }
    echo '<div class="emplacement">';
    echo '<img class="svg" src="liaisons\images\ion_location-outline.svg" alt="">';
    echo '<h2 class="p_actu">' . $actualite["titre"] . '</h2>';
    echo '</div>';
    echo '<p class="texte_m">' . $actualite["article"] . '</p>';
    echo '<button class="button">Lire la suite</button>';
    echo '</article>';
}
echo '</div>';
?>

<section class="artiste_background">
        <h1 class="h1_artiste">Artistes en vedette</h1>
<ul>
<?php
for ($intCpt = 0; $intCpt < count($arrArtistesChoisis); $intCpt++) {
    $classAlternance = ($intCpt % 2 == 0) ? 'alt1' : 'alt2';
    echo '<div class="artistes ' . $classAlternance . '">';
    ?>
    <li class="carte_artiste">
        <?php if ($classAlternance === 'alt1') { ?>
            <img class="album__artiste" src="liaisons/images/artistes_sug/<?php echo $arrArtistesChoisis[$intCpt]['id_artiste']; ?>_<?php echo rand(1, 2) ?>_rect_w620.jpg" alt="imagebanniere">

            <div class="content_artiste">
                <a class="nom__artsite" href="artistes/fiches/index.php?id_artiste=<?php echo $arrArtistesChoisis[$intCpt]['id_artiste']; ?>">
                    <?php echo $arrArtistesChoisis[$intCpt]['nom_artiste']; ?>
                </a>
                <button class="button_artiste">Voir la fiche de l'artiste</button>
            </div>
        <?php } else { ?>
            <div class="content_artiste">
                <a class="nom__artsite" href="artistes/fiches/index.php?id_artiste=<?php echo $arrArtistesChoisis[$intCpt]['id_artiste']; ?>">
                    <?php echo $arrArtistesChoisis[$intCpt]['nom_artiste']; ?>
                </a>
                <button class="button_artiste">Voir la fiche de l'artiste</button>
            </div>
            <img class="album__artiste" src="liaisons/images/artistes_sug/<?php echo $arrArtistesChoisis[$intCpt]['id_artiste']; ?>_<?php echo rand(1, 2) ?>_rect_w620.jpg" alt="imagebanniere">
        <?php } ?>
    </li>
    <?php
    echo '</div>';
}
?>
</section>
 
                <div class="lieux">
                    <h3>Lieux de spéctacles</h3>
                    <img src="liaisons\images\foule_carte.jpg" alt="foule">
                    <iframe src="https://www.google.com/maps/d/u/0/embed?mid=1NCNDAUUorKPuCK3pHCYjet3qiz469kg&ehbc=2E312F&noprof=1" width="300" height="175"></iframe>
                </div>
                <h3 class="h3_tarif" id="tarifs">Tarifs</h3>
                <section class="tarifs">
                    <div class="carte-tarif">
                        <p class="prix">10$</p>
                        <p class="p_tarif">Pour toute la durée du festival</p>
                        <button class="btn-tarifs">Voir plus</button>
                    </div>
                    <div class="carte-tarif">
                        <p class="prix">5$</p>
                       
                        <p class="p_tarif">Soir <br>(spectacles à Méduse)</p>
                        <button class="btn-tarifs">Voir plus</button>
                    </div>
                    <div class="carte-tarif">
                        <p class="prix">Gratuit</p>
                        <p class="p_tarif">Spectacles extérieurs gratuits</p>
                        <button class="btn-tarifs">Voir plus</button>
                    </div>
                    <div class="carte-tarif">
                        <p class="prix">Gratuit</p>
                        <p class="p_tarif">Spectacles gratuits au Parvis de l’église Saint-Jean-Baptiste, au bar le Sacrilège et au Fou-Bar.</p>
                        <button class="btn-tarifs">Voir plus</button>
                    </div>
                </div>
</section>
<div class="web">
    <div class="texte-web">
        <p>Procurez-vous un passeport en ligne à <a href="https://lepointdevente.com/billets/off2024/"> <br> lepointdevente.com</a> <br> et profitez <strong>d’offres spéciales!</strong></p>
    </div>
</div>
 
<h2 class="h2_partenaires" id="partenaires">Les passeports sont aussi disponibles en prévente chez nos partenaires :</h2>
<section class="partenaires" >
    <div class="carte-partenaires">
        <img src="liaisons\images\partenaires\ninkasi_w180.png" alt="logo du partenaire"><br>
        <a href="https://maps.app.goo.gl/gT3yxpwCdNnYioiV7">840 Avenue Honoré-Mercier, Québec</a>
    </div>
    <div class="carte-partenaires">
        <img src="liaisons\images\partenaires\erico_w180.png" alt="logo du partenaire"><br>
        <a href="https://maps.app.goo.gl/4qnNNcaJrcRiQZmS9">634 Rue Saint-Jean, Québec</a>
    </div>
    <div class="carte-partenaires">
        <img src="liaisons\images\partenaires\sacrilege_w180.png" alt="logo du partenaire"><br>
        <a href="https://maps.app.goo.gl/gCHcXerXuiCc45t6A">447 Rue Saint-Jean, Québec</a>
    </div>
    <div class="carte-partenaires">
        <img src="liaisons\images\partenaires\bonnetdane_w180.png" alt="logo du partenaire"><br>
        <a href="https://maps.app.goo.gl/bZfbZ1aekpk8jPQs9">298 Rue Saint-Jean, Québec</a>
    </div>
    <div class="carte-partenaires">
        <img src="liaisons\images\partenaires\melomane_w180.png" alt="logo du partenaire"><br>
        <a href="https://maps.app.goo.gl/Jk8fiWAJUZME3Han9">248 rue Saint-Jean, Québec</a>
    </div>
    <div class="carte-partenaires">
        <img src="liaisons\images\partenaires\knockout_w180.png" alt="logo du partenaire"><br>
        <a href="https://maps.app.goo.gl/4gbw4B4DhNY7jeYS7">832 St-Joseph Est, Québec</a>
    </div>
</section>
</div>
</main>
<?php include ($niveau . "liaisons/fragments/piedDePage.inc.php");?>
</body>
</html>