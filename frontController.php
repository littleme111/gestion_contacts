<?php

function thefunc(){

//Sets names and paths for all css and js files needed by plugin.


	require_once ABSPATH.'/wp-content/plugins/gestion_contacts/includes/styles.php';
	require_once ABSPATH.'/wp-content/plugins/gestion_contacts/Controllers/controllerController.php';




		//Explode the url (with replacement of %2F created by wordpress.
		// Get the last part of the url to call controller with same name.
		$params = $_SERVER['REQUEST_URI'];
		$params = str_replace('%2F', '/', $params);
		$params = str_replace('&', '/', $params);
		$params = explode('/', $params);


		$cont = $params[3];

		//Append Controller to the url.
		$cont .= 'Controller';

		require_once 'Controllers/'.$cont.'.php';

		//Instanciate the corresponding controller.
		$controller = new $cont($includes);




}
