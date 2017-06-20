<?php

class liste {
	/*============================================================================================*/
	/*=====================================AFFICHE les listes=====================================*/
	/*============================================================================================*/
	public function getLists(){
		global $wpdb;
		return $wpdb->get_results( 'SELECT * FROM ch_liste');
	}

	/*============================================================================================*/
	/*=======================================CREER UNE LISTE======================================*/
	/*============================================================================================*/
	public function createList($nom_liste){
		global $wpdb;
		require_once  ABSPATH .'/wp-config.php';
		$sql = $wpdb->prepare('INSERT INTO ch_liste (nom_liste) VALUES (%s)', $nom_liste);
		$wpdb->query($sql);
		$data["id"]= $wpdb->insert_id;

		$data["users"] = $wpdb->get_results("SELECT id, user_nicename FROM ch_users",ARRAY_N);
		print json_encode($data);
		return $data;
	}

	/*============================================================================================*/
	/*======================SUPPRIMER UNE LISTE ET LES ELEMENTS LA COMPOSANT======================*/
	/*============================================================================================*/
	public function deleteList($id_liste){
		global $wpdb;
		require_once  ABSPATH .'/wp-config.php';
		$sql = $wpdb->prepare('DELETE FROM `ch_liste` WHERE `id` = (%d)', $id_liste);
		$sql2 =$wpdb->prepare('DELETE FROM `ch_salaries` WHERE `id_liste` = (%d)', $id_liste);
		$wpdb->query($sql);
		$wpdb->query($sql2);
	}

/*============================================================================================*/
/*========================AFFICHE noms de la liste et les non listés==========================*/
/*============================================================================================*/
	public function showUsers($idListe){
		global $wpdb;
		require_once  ABSPATH .'/wp-config.php';
			$data['userList'] = $wpdb->get_results( 'SELECT ch_users.user_nicename, ch_users.id, ch_salaries.id_liste  FROM ch_users JOIN ch_salaries
			WHERE ch_users.id = ch_salaries.id_user AND ch_salaries.id_liste='.$idListe.'',ARRAY_N);
			$idUsers = array_column($data['userList'], 0);
			$idUsers = implode("','",$idUsers);

			$data['userNotList'] = $wpdb->get_results("SELECT id, user_nicename FROM ch_users WHERE user_nicename NOT IN ('" . $idUsers . "')");
			print json_encode($data);
		return $data;
	}

/*============================================================================================*/
/***************************AJOUT ET SUPPRESSION des noms dans la liste************************/
/*============================================================================================*/

/*=======================AJOUTE un utilisateur dans une liste au clic=======================*/
public function addUser($idListe, $idUser){
	require_once  ABSPATH .'/wp-config.php';
	global $wpdb;
	$wpdb->query('INSERT INTO ch_salaries (id_liste,  id_user) VALUES ('.$idListe.', '.$idUser.')');
}


/*=======================SUPPRIME un nom de la liste lors du clic=======================*/
	public function delUser($idListe, $idUser){
		require_once  ABSPATH .'/wp-config.php';
		global $wpdb;
		$wpdb->query('DELETE FROM ch_salaries WHERE id_liste = '.$idListe.' AND id_user = '.$idUser.'');
	}


	/*=======================RECUPERE id  de liste =======================*/
	public function getListId($nom){
		require_once  ABSPATH .'/wp-config.php';
		global $wpdb;
		$id = $wpdb->get_results($wpdb->prepare('SELECT id FROM ch_liste WHERE nom_liste = (%s)',$nom),ARRAY_N);
		$id = $id[0];
		$salaries = $wpdb->get_results($wpdb->prepare('SELECT  id_user FROM ch_salaries WHERE id_liste = (%d)', $id), ARRAY_N);
		return $salaries;
	}

	public function getListMails($users){
		require_once  ABSPATH .'/wp-config.php';
		global $wpdb;
		$mail = array();
		foreach($users as $user){
			$id = $user[0];
			$mail[] = $wpdb->get_results($wpdb->prepare('SELECT user_email FROM ch_users WHERE ID  = (%s)', $id),ARRAY_N);
		}

		return $mail;
	}



} ///////////////////////////////////////////////////////////////////// END CLASS list /////////////////////////////////////////////////////////////////
