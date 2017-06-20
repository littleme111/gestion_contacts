<?php
require_once ABSPATH .'/wp-content/plugins/gestion_contacts/Controllers/controllerController.php';

class listeController extends controllerController {
	public $data;
	public $view;
	public $liste;

	public function __construct($includes){
		//Call parent constructor to initialize includes.
		parent::__construct($includes);

		self::styles();
		// On appelle le modèle pour récupérer les données brutes
		require_once ABSPATH.'wp-content/plugins/gestion_contacts/Models/liste.php';
		$model = new liste();
		$this->data['liste'] = $model->getLists();
		//Load css and js


		// => on envoi au template
		return $this->render(ABSPATH .'wp-content/plugins/gestion_contacts/Views/liste.tpl.php', $this->data);

	}
	public function styles(){
		$css = array_search('/wp-content/plugins/gestion_contacts/includes/css/liste.css', $this->includes['cssSrcs']);
		$js = array_search('/wp-content/plugins/gestion_contacts/includes/js/liste.js', $this->includes['jsSrcs']);
		wp_enqueue_style($css, $this->includes['cssSrcs'][$css], false );
		wp_enqueue_script($js, $this->includes['jsSrcs'][$js], false );
		//Foundation
		$css = array_search('/wp-content/plugins/gestion_contacts/includes/css/foundation.css', $this->includes['cssSrcs']);
		$js = array_search('/wp-content/plugins/gestion_contacts/includes/js/foundation.js', $this->includes['jsSrcs']);
		wp_enqueue_style($css, $this->includes['cssSrcs'][$css], false );
		wp_enqueue_script($js, $this->includes['jsSrcs'][$js], false );
	}
}
