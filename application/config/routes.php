<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'homepage';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
$route[LOGIN_PAGE] = 'homepage/connexion';
$route['connexion'] = 'homepage/connexion';
$route['deconnexion'] = 'homepage/deconnexion';
$route['recuperation'] = 'homepage/recuperation';
$route['verification/(:num)/(:any)'] = 'homepage/verification/$1/$2';
$route['activite'] = 'participation/activite';
$route['planning'] = 'participation/planning';
$route['rapport'] = 'rapport/mesRapports';
$route['disponibilites'] = 'utilisateur/disponibilites';
$route['contacts'] = 'utilisateur/contacts';
$route['informations'] = 'utilisateur/infos';
$route['options'] = 'utilisateur/options';
$route['accueilRP'] = 'homepage/accueilRP';
$route['proclamateurs'] = 'utilisateur/listeRP';
$route['inscription/(:num)/(:any)'] = 'utilisateur/inscription/$1/$2';
$route['creneaux'] = 'participation/partAVenir';
$route['creneau/(:num)'] = 'participation/creneau/$1';
<<<<<<< HEAD
$route['annuler/(:num)'] = 'participation/annulerRP/$1';
$route['rapportsAssemblee'] = 'rapport/rapportsAssemblee';
$route['creneauxAssemblee'] = 'creneau/creneauxAssemblee';
$route['informationsAssemblee'] = 'utilisateur/infosRP';
$route['accueilAdmin'] = 'homepage/accueilAdmin';
$route['rp'] = 'utilisateur/listeAdmin';
$route['inscriptionRP/(:num)/(:any)'] = 'utilisateur/inscriptionRP/$1/$2';
=======
$route['creneauProcl/(:num)'] = 'participation/creneauProcl/$1';
$route['annuler/(:num)'] = 'participation/annulerRP/$1';
$route['annulerProcl/(:num)/(:num)'] = 'participation/annulerProcl/$1/$2';
$route['rapportsAssemblee'] = 'rapport/rapportsAssemblee';
$route['creneauxAssemblee'] = 'creneau/creneauxAssemblee';
$route['informationsAssemblee'] = 'utilisateur/infosRP';
$route['informationsAdmin'] = 'utilisateur/infosAdmin';
$route['accueilAdmin'] = 'homepage/accueilAdmin';
$route['rp'] = 'utilisateur/listeAdmin';
$route['inscriptionRP/(:num)/(:any)'] = 'utilisateur/inscriptionRP/$1/$2';
$route['rapportRP'] = 'Rapport/rapportRP';
$route['bilan/(:num)'] = 'Participation/activiteRP/$1';
$route['bilan'] = 'Participation/activiteRP';
$route['gestAssemblee'] = 'Utilisateur/gestionAssemblee';
$route['gestLieux'] = 'Creneau/gestionLieux';
$route['gestPubs'] = 'Rapport/gestionPubs';
$route['ctcRP/(:num)'] = 'Utilisateur/ctcRP/$1';
$route['activiteAdmin/(:num)'] = 'Participation/activiteAdmin/$1';
$route['activiteAdmin'] = 'Participation/activiteAdmin';
$route['modifier/(:num)/(:num)'] = 'Rapport/modifier/$1/$2';
$route['ajoutLangue/(:num)'] = 'Rapport/ajoutLangueExistant/$1';
$route['supprLng/(:num)/(:num)']='Rapport/supprLng/$1/$2';
>>>>>>> 7ff721c (initial commit)
