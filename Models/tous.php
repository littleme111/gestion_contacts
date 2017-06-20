<?php

/**
 * Created by PhpStorm.
 * User: John
 * Date: 04/05/2017
 * Time: 17:14
 */
class tous {
	public $data;
	public $wpdb;

	public function __construct(){
		global $wpdb;
		$this->wpdb = $wpdb;
	}

	public function getData($ppp = -1, $channel = 0){
		//Get data from forms with Flamingo Class
		require_once ABSPATH . 'wp-content/plugins/flamingo/admin/includes/class-inbound-messages-list-table.php';
		$flam = new Flamingo_Inbound_Message;
		$this->data = $flam->find(array(
			'posts_per_page' => $ppp,
			'offset' => 0,
			'orderby' => 'ID',
			'order' => 'DESC',
			'channel_id' => $channel

		));

		return $this->data;
	}
	public function getStatesDelais($idsToString) {

		$idsTosString = str_replace( ",", "','", $idsToString );

		$states = $this->getStates( $idsTosString );
		$delais = $this->getDelais( $idsTosString );

		require_once ABSPATH.'/wp-content/plugins/gestion_contacts/Models/users.php';
		$users = new users();
		$referents = $users->getReferents();
		$rContact = "";
		$rDevis = "";
		$rRH = "";
		for($i = 0; $i < count($referents); $i++){

			if($referents[$i]['type_demande'] == 'Contact'){
				$rContact = $referents[$i]['user_nicename'];
			}else if($referents[$i]['type_demande'] == 'Devis'){
				$rDevis= $referents[$i]['user_nicename'];
			}else if($referents[$i]['type_demande'] == 'Ressources Humaines'){
				$rRH = $referents[$i]['user_nicename'];
			}
		}


		//Loop on all data
		for ( $y = 0; $y < count( $this->data ); $y ++ ) {77
			//Define two booleans for later use.
			$b = true;
			$c = true;
			//Secondary loop on delays.
			for ( $x = 0; $x < count( $delais ); $x ++ ) {
				//if ids match, create date string from timestamp.
				if ( $this->data[ $y ]->id == $delais[ $x ]->id_demande ) {
					$a = date( 'd-m-Y h:m:s', $delais[ $x ]->deadline );
					//assign date to data.
					$this->data[ $y ]->{'delais'} = $a;
					//bool becomes false as we dont need to calculate this field again.
					$b = false;
				}
			}
			//Secondary loop on states.
			for($i = 0; $i < count($states); $i++){
				//if ids match : assign referent and state.
				if($this->data[$y]->id == $states[$i]->id_demande){
					$this->data[$y]->{'referent'} = $states[$i]->user_nicename;
					$this->data[$y]->{'etat'} = $states[$i]->etat;
					//bool becomes false as we dont need to calculate this field again.
					$c = false;
				}
			}
			//If b = true, not dealine has been found or set so calculate remaining time depending on type of request.
			if ($b) {
				if ( $this->data[ $y ]->channel == 'formulaire-de-contact-1' ) {
					$tps = 15;
				} else if ( $this->data[ $y ]->channel == 'rejoindre-la-team' ) {
					$tps = 10;
				} else if ( $this->data[ $y ]->channel == 'lancez-votre-projet' ) {
					$tps = 5;
				}
				//Assign date.
				$this->data[ $y ]->{'delais'} = date( 'd-m-Y h:m:s', strtotime( $this->data[ $y ]->date . "+" . $tps . " days" ) );
			}
			//If b == true, no referent has been found so get default referent and set state to 0 as no action has been taken.
			if($c){

				if ( $this->data[ $y ]->channel == 'formulaire-de-contact-1' ) {
					$this->data[$y]->{'referent'} = $rContact;
				} else if ( $this->data[ $y ]->channel == 'rejoindre-la-team' ) {
					$this->data[$y]->{'referent'} = $rRH;
				} else if ( $this->data[ $y ]->channel == 'lancez-votre-projet' ) {
					$this->data[$y]->{'referent'} = $rDevis;
				}
				$this->data[$y]->{'etat'} = '0';
			}
		}


		return $states;
	}



	public function getStates($idsTosString){


		//Remove " from idsToString
//		var_dump($idsTosString);

		if (strlen($idsTosString) > 0) {
//
			$refs = $this->wpdb->get_results("SELECT histo1.id_demande, histo1.id_user, histo1.date, histo1.id_action, histo1.etat, posts1.id, posts1.user_nicename FROM `ch_demandes_historique` as histo1 JOIN ch_users as posts1  where histo1.date = (
    								SELECT MAX(histo2.date) FROM `ch_demandes_historique` as histo2 WHERE histo1.id_demande = histo2.id_demande GROUP BY histo2.id_demande
									) AND  posts1.id = histo1.id_user AND histo1.id_demande IN ('".$idsTosString."')");

			return $refs;
		}
	}

	public function getDelais($ids){

		$delais = $this->wpdb->get_results("SELECT id_demande, deadline FROM ch_deadlines WHERE id_demande IN ('".$ids."')");
		return $delais;

}

}

