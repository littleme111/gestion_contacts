<?php

/**
 * Created by PhpStorm.
 * User: John
 * Date: 15/05/2017
 * Time: 09:20
 */
class detail {

	public $data;
	public $id;
	public $wpdb;

	public function __construct($id) {
		$this->id= $id;
		global $wpdb;
		$this->wpdb = $wpdb;
		include_once ABSPATH .'/wp-config.php';
		require_once ABSPATH . 'wp-content/plugins/flamingo/admin/includes/class-inbound-messages-list-table.php';
	}

	public function getData(){


		$data['demandes'] = $this->wpdb->get_results($this->wpdb->prepare('SELECT * FROM ch_demandes_historique WHERE id_demande = (%d)', $this->id));
//		$data['mails'] = $this->wpdb->get_results($this->wpdb->prepare('SELECT * FROM ch_sent_mails WHERE id_demande = (%d)', $this->id));
		$data['users'] = $this->wpdb->get_results('SELECT ID, user_nicename FROM ch_users');
		$data['actions'] = $this->wpdb->get_results('SELECT ID, nom FROM ch_actions');
		$data['etats'] = $this->wpdb->get_results('SELECT ID, nom FROM ch_etats');



		$ids = "";
		foreach($data['demandes'] as $demandes){
			$ids .= $demandes->id_demande.',';
		};
		$data['mails'] = $this->wpdb->get_results($this->wpdb->prepare('SELECT ID, contenu, date_envoi FROM ch_sent_mails WHERE id_demande = (%d) ',$this->id));
		return $data;

	}

	public function getFilePaths($id){
		$paths[0] = $this->wpdb->get_results($this->wpdb->prepare('SELECT meta_value FROM ch_postmeta WHERE meta_key = (%s) AND post_id = (%s)', '_field_fichier-candidature', $id), ARRAY_N);
		$paths[1] = $this->wpdb->get_results($this->wpdb->prepare('SELECT meta_value FROM ch_postmeta WHERE meta_key = (%s) AND post_id = (%s)', '_field_fichier-candidature2', $id), ARRAY_N);
		return $paths;
	}




}