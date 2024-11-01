<?php

// Verifier si l'exécution se fait sur le serveur de développement (local) ou celui de la production:
if (stristr($_SERVER['HTTP_HOST'], 'local') || (substr($_SERVER['HTTP_HOST'], 0, 7) == '192.168')) {
    $blnLocal = TRUE;
} else {
    $blnLocal = FALSE;
}

//var_dump($_SERVER['HTTP_HOST']);

// Selon l'environnement d'exécution (développement ou en ligne)
if ($blnLocal) {
    $strHost = 'localhost';
    $strBD='25_rpni1_off';
    $strUser = '25_rpni1_off';
    $strPassword= '25_rpni1_off'; 
    error_reporting(E_ALL);
} else {
    $strHost = 'timunix3.csfoy.ca';
    $strBD='24_rpni1_charbon';
    $strUser = '24_rpni1_charbon';
    $strPassword = 'Jwbxx9f_8]g2vgxO';
    error_reporting(E_ALL & ~E_NOTICE);
}

//Data Source Name pour l'objet PDO
$strDsn = 'mysql:dbname='.$strBD.';host='.$strHost;
//Tentative de connexion
$pdoConnexion = new PDO($strDsn, $strUser, $strPassword);
//Changement d'encodage de l'ensemble des caractères pour UTF-8
$pdoConnexion->exec("SET CHARACTER SET utf8");
//Pour obtenir des rapports d'erreurs et d'exception avec errorInfo() du pilote PDO
$pdoConnexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//$objPdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);

?>
