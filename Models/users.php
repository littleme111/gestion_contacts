<?php

/**
 * Created by PhpStorm.
 * User: John
 * Date: 04/05/2017
 * Time: 17:14
 *
 *
 * Standard Model, fetches the user related data from the ch_users data table.
 *
 */
class users {
	public $data;
	public $wpdb;
	public function __construct(){
		include_once  ABSPATH. '/wp-config.php';
		global $wpdb;
		$this->wpdb = $wpdb;
	}
	public function getUsers(){

		$users = $this->wpdb->get_results('SELECT user_nicename, id FROM ch_users', ARRAY_N);

		return $users;
	}

	public function getUsersId($expediteur){
		$id = $this->wpdb->get_results($this->wpdb->prepare('SELECT id FROM ch_users where user_nicename = (%s)', $expediteur));
		return $id;
	}

	public function getReferents(){
		global $wpdb;
		$referents = $wpdb->get_results('SELECT refs.id_user, refs.type_demande, users.user_nicename FROM ch_referents as refs JOIN ch_users as users WHERE refs.id_user = users.id', ARRAY_A);
		return $referents;
	}

	public function getMailRefs(){
		global $wpdb;
		$referents = $wpdb->get_results('SELECT refs.id_user, refs.type_demande, users.user_nicename, users.user_email FROM ch_referents as refs JOIN ch_users as users WHERE refs.id_user = users.id', ARRAY_A);
		return $referents;
	}

}

