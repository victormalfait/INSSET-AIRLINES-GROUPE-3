<?php
  require_once("connexion.php");

  $connexion = connexion();
  $requeteInfos="SELECT * FROM maintenance ORDER BY immatriculation ASC;";
  $envoiRequeteInfos=$connexion->query($requeteInfos);
  if (!$envoiRequeteInfos) {
    $infoErreur = $connexion->errorInfo();
    die("Infos des maintenances : Code erreur ".$connexion->errorCode()." ".$infoErreur[2]);
  }
  else {
    while ($tableauInfos = $envoiRequeteInfos->fetch(PDO::FETCH_ASSOC)) {
		$output[] = $tableauInfos;
	}
    $infosMaintenance = json_encode($output);
    print($infosMaintenance);
    $connexion = null;
  }
?>