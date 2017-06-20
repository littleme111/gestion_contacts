<?php

/**
 * Created by PhpStorm.
 * User: John
 * Date: 19/05/2017
 * Time: 15:47
 */
class delais {

	public $wpdb;

	public function __construct(){
		global $wpdb;
		$this->wpdb = $wpdb;
	}

	public function getDelayedActions($id){


		$id = str_replace(",", "','", $id);
		$postActions = $this->wpdb->get_results("SELECT histo.id, histo.id_action, action.delai, histo.id_demande FROM ch_demandes_historique as histo JOIN ch_actions as action WHERE  id_action NOT IN ('1', '3') AND id_demande IN  ('".$id ."')");

		return $postActions;
	}

}