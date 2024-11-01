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
    <title>Festival OFF</title>
    <link rel="icon" href="#" type="svg">
    <link rel="stylesheet" href="..\ressources\liaisons\scss\layout\_accueil.scss">
    <?php include ($niveau . "liaisons/fragments/headlinks.inc.html");?>
</head>
 
<body>
    <?php include ($niveau . "liaisons/fragments/entete.inc.php");?>
    <main>
    <main>
        <h1 class="h1">Actualités</h1>
        
        <?php
        echo '<div class="cartes_actu">';
foreach ($arrActualites as $actualite) {
    echo '<article class="carte">';
    $jourSemaineTexte = $arrJours[array_search($actualite['jour_semaine_actualite'], ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'])];
    echo '<p class="date">Publié le ' . $jourSemaineTexte . ' ' . $actualite["jour_actualite"] . ' ' . $arrMois[$actualite["mois_actualite"]] . ' ' . $actualite["annee_actualite"] . ' à ' . $actualite["heure_actualite"] . 'h' . $actualite["minute_actualite"] . ' par ' . $actualite["auteurs"] . '</p>';
    echo '<img class="album" src="..\public\liaisons\images\actu\babaloones_w190_actu.jpg" alt="">';
    echo '<div class="emplacement">';
    echo '<img class="svg" src="..\public\liaisons\images\ion_location-outline.svg" alt="">';
    echo '<h2 class="p_actu">' . $actualite["titre"] . '</h2>';
    echo '</div>';
    echo '<p class="texte">' . $actualite["article"] . '</p>';
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
            <img class="album__artiste" src="https://fakeimg.pl/225x335" alt="">
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
            <img class="album__artiste" src="https://fakeimg.pl/225x335" alt="">
        <?php } ?>
    </li>
    <?php
    echo '</div>';
}
?>
</section>


        <!-- <img src="#" alt="">
 
        <h2 class="actu">Actualité</h2>
        <div id="contenu" >
            <div >
                <h3 >9 Juillet 2018</h3>
                <p >Publiée à : 08:33</p>
                <img class="album" src="..\public\liaisons\images\actu\babaloones_w190_actu.jpg" alt="album the babalooneys">
                <div >
                <img class="svg" src="..\public\liaisons\images\ion_location-outline.svg" alt="">
                <p>Scène de la famille Télé-Québec - accès libre</p>
                </div>
                <p>À 19 h 30, The Babalooneys (Québec), un quintette (...)</p>
                <button>Lire la suite</button>
            </div>
            <div class="carte">
                <h3>10 Juillet 2018</h3>
                <p>Publiée à : 10:00</p>
                <img class="album" src="..\public\liaisons\images\actu\Harvest-Breed_w190_actu.jpg" alt="album the babalooneys">
                <div class="emplacement">
                <img class="svg" src="..\public\liaisons\images\ion_location-outline.svg" alt="">
                <p>Scène Caisse populaire de Québec - accès libre</p>
                </div>
                <p>À 17 h, le sextuor Harvest Breed (Sherbrooke) (...)</p>
                <button>Lire la suite</button>
            </div>
            <div class="carte">
                <h3>9 Juillet 2018</h3>
                <p>Publiée à : 08:33</p>
                <img class="album" src="..\public\liaisons\images\actu\LATourelle-Orkestra_w190_actu.jpg" alt="album the babalooneys">
                <div class="emplacement">
                <img class="svg" src="..\public\liaisons\images\ion_location-outline.svg" alt="">
                <p>Rue Cartier/Scène Brunet - accès libre</p>
                </div>
                <p>À 17 h 30, LaTourelle Orkestra (Québec) vous (...)</p>
                <button>Lire la suite</button>
            </div>
  -->
           
            <!-- <div class="artistes"><h2>Artistes à d'écouvrir</h2>
                <div class="artsiteA">
                    <img src="#" alt="Artistes">
                    <h3>Diamond Rings</h3>
                    <p>Véritable coqueluche du courant électro/pop kitsch, Diamond Rings n'a plus besoin de présentation. De son habillement excentrique sur scène où ceinture dorée, make up arc-en-ciel, leggings et bijoux démodés sont ressuscités, en passant par ses textes outrageusement naïfs, Diamond  </p>
                </div>
                <div class="artsiteB">
                    <h3>Diamond Rings</h3>
                    <p>Véritable coqueluche du courant électro/pop kitsch, Diamond Rings n'a plus besoin de présentation. De son habillement excentrique sur scène où ceinture dorée, make up arc-en-ciel, leggings et bijoux démodés sont ressuscités, en passant par ses textes outrageusement naïfs, Diamond  </p>
                    <img src="#" alt="Artistes">
                </div>
                <div class="artsiteA">
                    <img src="#" alt="Artistes">
                    <h3>Diamond Rings</h3>
                    <p>Véritable coqueluche du courant électro/pop kitsch, Diamond Rings n'a plus besoin de présentation. De son habillement excentrique sur scène où ceinture dorée, make up arc-en-ciel, leggings et bijoux démodés sont ressuscités, en passant par ses textes outrageusement naïfs, Diamond  </p>
                </div>
                <div class="artsiteB">
                    <h3>Diamond Rings</h3>
                    <p>Véritable coqueluche du courant électro/pop kitsch, Diamond Rings n'a plus besoin de présentation. De son habillement excentrique sur scène où ceinture dorée, make up arc-en-ciel, leggings et bijoux démodés sont ressuscités, en passant par ses textes outrageusement naïfs, Diamond  </p>
                    <img src="#" alt="Artistes">
                </div> -->
               
                <div class="lieux">
                    <h3>Lieux de spéctacles</h3>
                    <img src="..\public\liaisons\images\artistes\image7.png" alt="foule">
                    <iframe src="https://www.google.com/maps/d/u/0/embed?mid=1NCNDAUUorKPuCK3pHCYjet3qiz469kg&ehbc=2E312F&noprof=1" width="320" height="150"></iframe>
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
        <img src="https://fakeimg.pl/180x180" alt="logo du partenaire"><br>
        <a href="https://maps.app.goo.gl/gT3yxpwCdNnYioiV7">840 Avenue Honoré-Mercier, Québec</a>
    </div>
    <div class="carte-partenaires">
        <img src="https://fakeimg.pl/180x180" alt="logo du partenaire"><br>
        <a href="https://maps.app.goo.gl/4qnNNcaJrcRiQZmS9">634 Rue Saint-Jean, Québec</a>
    </div>
    <div class="carte-partenaires">
        <img src="https://fakeimg.pl/180x180" alt="logo du partenaire"><br>
        <a href="https://maps.app.goo.gl/gCHcXerXuiCc45t6A">447 Rue Saint-Jean, Québec</a>
    </div>
    <div class="carte-partenaires">
        <img src="https://fakeimg.pl/180x180" alt="logo du partenaire"><br>
        <a href="https://maps.app.goo.gl/bZfbZ1aekpk8jPQs9">298 Rue Saint-Jean, Québec</a>
    </div>
    <div class="carte-partenaires">
        <img src="https://fakeimg.pl/180x180" alt="logo du partenaire"><br>
        <a href="https://maps.app.goo.gl/Jk8fiWAJUZME3Han9">248 rue Saint-Jean, Québec</a>
    </div>
    <div class="carte-partenaires">
        <img src="https://fakeimg.pl/180x180" alt="logo du partenaire"><br>
        <a href="https://maps.app.goo.gl/4gbw4B4DhNY7jeYS7">832 St-Joseph Est, Québec</a>
    </div>
</section>
</div>
</main>
<?php include ($niveau . "liaisons/fragments/piedDePage.inc.php");?>
</body>
</html>