<?php

/**
 * Created by PhpStorm.
 * User: John
 * Date: 04/05/2017
 * Time: 17:46
 */
class controllerController {
	//View : path to tpl file.
	public $view;
	//States : extra data got from the state table.
	public $states;
	//Actions: extra data got from the actions table.
	public $actions;
	//Includes : paths and nalmes for js and css files.
	public $includes;
	//Statistics : used tio display stats on pages.
	public $statistics = array('fermes' => 0, 'ouverts' => 0, 'en attente' => 0, 'Traité' => 0, 'RH' => 0, 'Devis' => 0, 'Contact' => 0);
	//Used for details page, ne return to front controller
	public $demande;

	public function __construct($includes){
		//Set includes for child use.
		$this->includes = $includes;
	}
	//Render function allows calling tpl and passing data to the view.
	public function render($view, $data = []){
		$response = new controllerController($this->includes);
		$response->view = $view;

		require_once "$response->view";
		$response->data = $data;
		$response->statistics = $this->statistics;
		return $response;
	}
	//returns all ids for the data (finction is needed because Flamingo class doesnt allow this.
	public function prepareStatesQuery(){
		$ids = array();

		foreach($this->data as $value){

			if (!isset($value->id)) {
				continue;
			}
			$ids[] = $value->id;
		}

		$idsToString = implode(',', $ids);

		return $idsToString;
	}

	//SetStates checks through data and states gotten
	public function setStates(){

		$b = 0;
		foreach ($this->data as $data) {
			foreach ( $this->states as $state ) {
//				error_log(print_r($state,TRUE));
				if(isset($state->id_demande)) {
					if ( $data->id == $state->id_demande ) {
						$data->{'referent'} = $state->user_nicename;
						$data->{'etat'}     = $state->etat;
						continue 2;
					}
				}else{
					$b = 1;
				}
			}
			if($b == 1){
				$data->{'etat'} = 0;
				$data->{'referent'} = '0';

			}
		}
	}


	public function getActions(){
		require_once ABSPATH . 'wp-content/plugins/gestion_contacts/Models/action.php';
		$action = new action();
		$this->actions = $action->getActions();

		$actionsHtml = "<select class='smaller'><option value=''>Selectionnez une action</option> ";
		foreach($this->actions as $data){

			$actionsHtml .= "<option value='".$data->nom."'>".$data->nom."</option>";
		};

		$actionsHtml .= "</select>";
		for($i = 0; $i < count($this->data); $i++) {

			$this->data[ $i ]->{'actionController'} = $actionsHtml;

		};

	}

	/**
	 * UserDisplay : tranforms names of forms into nicename,
	 */
	public function userDisplay() {

		foreach ( $this->data as $data => $value ) {

			if ( isset( $value->etat ) ) {
				switch ( $value->etat ) {
					case 0:
						$value->etat = "En cours";
						break;
					case 1:
						$value->etat = "Fermé";
						break;
					case 2:
						$value->etat = "Mail Envoyé";
						break;

				}
			}
			if ( isset( $value->channel ) ) {

				switch ( $value->channel ) {
					case 'formulaire-de-contact-1':
						$value->channel = "Contact";
						break;
					case 'rejoindre-la-team':
						$value->channel = "RH";
						break;
					case 'lancez-votre-projet':
						$value->channel = "Devis";
						$value->fields['your-message'] = $value->fields['description-projet'];

						break;
				}
			}
		}
	}


}
