<?php

/**
 * Created by PhpStorm.
 * User: John
 * Date: 24/05/2017
 * Time: 15:53
 */
class action {

	public $wpdb;

	public function __construct(){
		include_once  ABSPATH .'/wp-config.php';
		global $wpdb;
		$this->wpdb = $wpdb;

	}

	public function fermer($userId, $id){

		$sql = $this->wpdb->prepare('INSERT INTO ch_demandes_historique (id_user, id_action, date, id_demande, etat) 
		VALUES (%d, 0, NOW(), %d, %d)',$userId, $id, 1);

		$this->wpdb->query($sql);
	}

	public function updateReferent($salarie, $id){
		$salarie = $this->wpdb->get_results($this->wpdb->prepare('SELECT id FROM ch_users WHERE user_nicename = %s', $salarie));

		$sql = $this->wpdb->prepare('INSERT INTO ch_demandes_historique (id_user, id_action, date, id_demande) 
		VALUES (%d, 1, NOW(), %d)',$salarie[0]->id, $id);

		$this->wpdb->query($sql);
	}

	public function logSentMailHistorique($idUser, $id,  $lastid){
		$sql = $this->wpdb->prepare('INSERT INTO ch_demandes_historique (id_user, id_action, date, id_demande, etat, id_sent_mail) VALUES ((%s), (%s), NOW(), (%s), (%d), (%s))',$idUser, 2,  $id, 2, $lastid);
		$this->wpdb->query($sql);
	}

	public function getDeadlineId($id){
		$deadline = $this->wpdb->get_results('SELECT id_demande FROM ch_deadlines WHERE id_demande = '.$id.'');
		return $deadline;
	}

	public function updateDeadline($tempsRestant, $id){
		$sql = $this->wpdb->prepare('UPDATE ch_deadlines SET deadline = (%d) WHERE id_demande = (%d)', $tempsRestant, $id);
		$this->wpdb->query($sql);
	}

	public function insertDeadline($id, $tempsRestant){
		$sql = $this->wpdb->prepare('INSERT INTO ch_deadlines (id_demande, deadline) VALUES ((%d), (%d))',$id, $tempsRestant);
		$this->wpdb->query($sql);
	}

	public function getDeadline($id){
		$sql = $this->wpdb->prepare('SELECT deadline FROM ch_deadlines WHERE id_demande = %d', $id);
		$date = $this->wpdb->get_results($sql);
		return $date;
	}

	public function getActions(){
		return $this->wpdb->get_results('SELECT * FROM ch_actions');
	}

	public function ouvrir($userId, $id){
		$sql = $this->wpdb->prepare('INSERT INTO ch_demandes_historique (id_user, id_action, date, id_demande, etat) 
		VALUES (%d, 0, NOW(), %d, %d)',$userId, $id, 0);

		$this->wpdb->query($sql);
	}

}