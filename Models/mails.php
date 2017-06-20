<?php

/**
 * Created by PhpStorm.
 * User: John
 * Date: 04/05/2017
 * Time: 17:14
 */
class mails {
	public $data;
	public $wpdb;

	public function __construct(){
		include_once  ABSPATH .'/wp-config.php';
		global $wpdb;
		$this->wpdb = $wpdb;
	}



	public function getMails(){
		$mails = $this->wpdb->get_results('SELECT * FROM ch_mails');
		return $mails;
	}
	public function getSpecificMail($nom){
		$mails = $this->wpdb->get_results('SELECT contenu FROM ch_mails WHERE nom = "'.$nom.'"');
		print json_encode($mails);
	}


	public function updateMail($text, $nom) {

		try {
			$sql = $this->wpdb->prepare( 'SELECT id FROM ch_mails WHERE nom = (%s)', $nom );
			$id = $this->wpdb->get_results( $sql , ARRAY_N);
			$id  = $id[0][0];

			$sql = $this->wpdb->prepare( 'UPDATE ch_mails SET   contenu = (%s) WHERE id = (%d)',
				 $text,  $id );

			$this->wpdb->query( $sql );
			print json_encode(array('result' => 'success'));

		} catch ( Exception $e ) {
			print json_encode(array('result' => $e ));
		}
	}

	public function createMail($nom){
		try{
			$this->wpdb->query('INSERT INTO ch_mails (nom) VALUES ("'.$nom.'")');
			print json_encode(array('result' => 'success'));
		}catch (Exception $e){

			print json_encode(array('result' => $e));
		}
	}

	public function deleteMail($nom){
		try{
			$this->wpdb->query('DELETE FROM ch_mails WHERE nom = ("'.$nom.'")');
			print json_encode(array('result' => 'success'));
		}catch(Exception $e){
			print json_encode(array('result' => $e));
		}
	}

	public function logSentMail($destinataire, $contenu, $idUser, $id){
		$sql = $this->wpdb->prepare('INSERT INTO ch_sent_mails (destinataire, contenu, expediteur, date_envoi, id_demande) VALUES ((%s), (%s), (%s), NOW(), (%d))',$destinataire, $contenu, $idUser, $id);
		$this->wpdb->query($sql);
		$lastid = $this->wpdb->insert_id;
		return $lastid;
	}

	public function getReferents(){
		global $wpdb;
		$data = $wpdb->get_results("SELECT id_user, type_demande FROM ch_referents", ARRAY_N);
		return $data;
	}
	public function updateListReferent($idUser, $type){
		global $wpdb;
		$wpdb->query('UPDATE ch_referents SET id_user = '.$idUser.' WHERE type_demande = "'.$type.'"');
	}
}

