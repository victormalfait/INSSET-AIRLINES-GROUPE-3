<?php
  require_once("insset_airlines.inc.php"); // Inclusion des identifiants de connexion à la base

  function connexion() {
    $serveur = "mysql:host=".HOTE.";dbname=".BASE;
    try {
      $connexion = new PDO($serveur,USER,PASS);
      $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // paramétrage pour affichage des erreurs PDO
    }
    catch(PDOException $except) {
      echo "Erreur lors de la connexion au serveur MySQL :<BR />",$except->getMessage();
      die();
    }
  return $connexion;
  }
?>