<?php
// Création d'une nouvelle ressource cURL
$ch = curl_init();

// Configuration de l'URL et d'autres options
curl_setopt($ch, CURLOPT_URL, "https://www.temoignage-normandie.site/routiers/Cron/lancerInvitation");
curl_setopt($ch, CURLOPT_HEADER, 0);

// Récupération de l'URL et affichage sur le navigateur
curl_exec($ch);

// Configuration de l'URL et d'autres options
curl_setopt($ch, CURLOPT_URL, "https://www.temoignage-normandie.site/routiers/Cron/addCren");
curl_setopt($ch, CURLOPT_HEADER, 0);

// Récupération de l'URL et affichage sur le navigateur
curl_exec($ch);

// Fermeture de la session cURL
curl_close($ch);

if(date("d", strtotime("first monday of this month"))==date('d')){
// Création d'une nouvelle ressource cURL
$ch = curl_init();

// Configuration de l'URL et d'autres options
curl_setopt($ch, CURLOPT_URL, "https://www.temoignage-normandie.site/routiers/Cron/sendReport");
curl_setopt($ch, CURLOPT_HEADER, 0);

// Récupération de l'URL et affichage sur le navigateur
curl_exec($ch);    
// Fermeture de la session cURL
curl_close($ch);
}
?>