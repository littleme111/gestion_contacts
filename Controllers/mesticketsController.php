<?php
//require_once ABSPATH .'/wp-content/plugins/test_plugin/Controllers/controllerController.php';
/**
 * Created by PhpStorm.
 * User: John
 * Date: 04/05/2017
 * Time: 17:45
 */
class mesticketsController extends controllerController {
	public $data = array();
	public $myData = array();
	public $idsToString;
	public $refId;
	public $title = 'Mes Tickets';

	public function __construct($includes){
		//Call parent constructor to initialize includes.
		parent::__construct($includes);
		self::styles();


		//Call and instantiate models,
		require_once ABSPATH.'wp-content/plugins/gestion_contacts/Models/tous.php';
		
		$model = new tous();
		

		//Get Flamimingo Data
		$this->data = $model->getData();

		/**
		 * Gets the ids of all the submissions, will br needed for aditional database queries
		 */
		$this->idsToString = $this->prepareStatesQuery();
		//Get states  and set Delays
		$this->states = $model->getStatesDelais($this->idsToString);

		//Set the actual states depending on the type of request
		$this->setStates();
		//Filter requests bases on the current user ID
		$this->getUserDemandes();
		//load actions form database and create Select for table.
		$this->getActions();
		//Display human readable information
		$this->userDisplay();

		//Get Current User id
		$current_user = wp_get_current_user();




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

	public function getUserDemandes(){
		global $wpdb;
		//Get current User ID
		$user = wp_get_current_user();
		$username = $user->user_nicename;

		/**
		 * Loop on all data, if data->referent != user connecté - do nothing
		 * Else take index and store in new array myData.
		 * Tis method is used rather than unsetting incorrect data because the returned array has broken indexes
		 * that interfere with rest of the functions.
		 */
		$this->myData = array();
		for ($i= 0, $x = 0; $i < count($this->data); $i++){
			if($this->data[$i]->referent !== $username){
				continue;
			}else{
				$this->myData[$x] = $this->data[$i];
						$x++;
			}
		};
		//Pass back the selected data to $this->data;
		$this->data = $this->myData;
	}

}