<?php
require_once ABSPATH .'wp-content/plugins/gestion_contacts/Controllers/controllerController.php';
/**
 * Created by PhpStorm.
 * User: John
 * Date: 04/05/2017
 * Time: 17:40
 * Class tousController alloxws thel oading of all submitted webform submissions.
 * The workflow is as follows :
 *  Require and instantiate the Flaming Controller Class : This is necessary because of the way the website was implemented
 *  using the plugin Falmingo to store all data in the Post Table. Ass all fields are stores in one longtext field flamingo
 *  class is the preferred way of getting the data back from the database in a readable format. The flamingo returns an array of objects, each containig the data from the forms.
 *
 *
 *
 */
class tousController extends controllerController {

	public $data;
	public $states;
	public $idsToString;
	public $title = "Tous les Tickets";
	public $nbrOfPosts = 25;

	public function __construct($includes){
		//Call parent constructor to initialize includes.
		parent::__construct($includes);
		global $wpdb;


		//Load css and js
		self::styles();




		//Call and instantiate models
		require_once ABSPATH .'wp-content/plugins/gestion_contacts/Models/tous.php';
		
		$model = new tous();
		
		//Get Flamimingo Data
		$this->data = $model->getData();

		/**
		 * Gets the ids of all the submissions, will br needed for aditional database queries
		 */
		$this->idsToString = $this->prepareStatesQuery();

		//Get states  and set Delays
		$this->states = $model->getStatesDelais($this->idsToString);
		var_dump($this->data);
		//Set the actual states depending on the type of request
		$this->setStates();

		//load actions form database and create Select for table.
		$this->getActions();

		//Display human readable information
		$this->userDisplay();




		//Render the page.
		return $this->render(ABSPATH.'wp-content/plugins/gestion_contacts/Views/tous.tpl.php', array($this->data, $this->title));
	}
	public function styles(){
		$css = array_search('/wp-content/plugins/gestion_contacts/includes/css/mes.css', $this->includes['cssSrcs']);
		$js = array_search('/wp-content/plugins/gestion_contacts/includes/js/mes.js', $this->includes['jsSrcs']);
		wp_enqueue_style($css, $this->includes['cssSrcs'][$css], false );
		wp_enqueue_script($js, $this->includes['jsSrcs'][$js], false );

		$css = array_search('/wp-content/plugins/gestion_contacts/includes/css/foundation.css', $this->includes['cssSrcs']);
		$js = array_search('/wp-content/plugins/gestion_contacts/includes/js/foundation.js', $this->includes['jsSrcs']);
		wp_enqueue_style($css, $this->includes['cssSrcs'][$css], false );
		wp_enqueue_script($js, $this->includes['jsSrcs'][$js], false );

		$css = array_search('/wp-content/plugins/gestion_contacts/includes/css/jquery.dataTables.min.css', $this->includes['cssSrcs']);
		$js = array_search('/wp-content/plugins/gestion_contacts/includes/js/jquery.dataTables.js', $this->includes['jsSrcs']);
		wp_enqueue_style($css, $this->includes['cssSrcs'][$css], false );
		wp_enqueue_script($js, $this->includes['jsSrcs'][$js], false );

		$js = array_search('/wp-content/plugins/gestion_contacts/includes/js/tableActions.js', $this->includes['jsSrcs']);
		wp_enqueue_script($js, $this->includes['jsSrcs'][$js], false );
	}

}
