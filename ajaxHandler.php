<?php

if(isset($_POST)){
	//Wp-load allows the use ABSPATH Constant avoiding need for path rewriting on project move.
	require_once "../../../wp-load.php";

	require_once ABSPATH . "/wp-content/plugins/gestion_contacts/Controllers/actionController.php";
}


//On change of select - 3 types of action : answer, tranfer or close.
//Each just loads confirmation dialogue.
//BValidation is set further down.
if(isset($_POST['registration'])){

	$action = new actionController();
	$id = $_POST['id'];
	if( $_POST['action'] == "Reponse"){
		$id = $_POST['id'];
		$email = $_POST['email'];
		try{
			$action->loadMails($email, $id);
		}catch(Exception $e){
			error_log(print_r($e, true));
		}

	}else if( $_POST['action'] == "Transfert"){
		$id = $_POST['id'];
		$users = $action->transferer($id);

	}else if( $_POST['action'] == "Fermer"){
		$user = wp_get_current_user();
		$userId = $user->ID;
		$action->fermer($id, $userId);
	}

}
//On confirmation of transfer.
if(isset($_POST['type']) && $_POST['type'] == 'transfert'){
	require_once ABSPATH ."/wp-content/plugins/gestion_contacts/Controllers/actionController.php";
	$salarie = $_POST['action'];
	$id = $_POST['id'];
	$actions = new actionController();
	try{
		$actions->updateReferent($salarie, $id);
	}catch (Exception $e) {
	}
}
//On Mail send.
if(isset($_POST['type']) && $_POST['type'] == "envoi_mail"){
	$mail = $_POST['mail'];
	$contenu = $_POST['contenu'];
	$expediteur = $_POST['expediteur'];
	$destinataire = $_POST['destinataire'];
	$id = $_POST['id'];
	$action = new actionController();
	$action->sendMail($mail, $contenu, $expediteur, $destinataire, $id);

}
//On click of content of ticket, load flamingo class for specified ticket.
if(isset($_POST['type']) && $_POST['type'] == 'details'){
	require_once  ABSPATH ."/wp-content/plugins/gestion_contacts/Controllers/detailController.php";

	$values = $_POST['values'];
	$detail = new detailController();
	$detail->setDemande($values);
}

//On Mail update. Change content of text in database.
if(isset($_POST['type']) && $_POST['type'] == 'UpdateMail'){
	require_once  ABSPATH ."/wp-content/plugins/gestion_contacts/Models/mails.php";
	$text = $_POST['mailText'];
	$nom = $_POST['nom'];
	$mails = new mails();
	$mails->updateMail($text, $nom);
}

//On create or delete of mail in Email mgmt page.
//Either u^date or delete based on user input.
if(isset($_POST['type']) && ($_POST['type'] == "CreateMail" || $_POST['type'] == 'DeleteMail')){
	require_once  ABSPATH ."/wp-content/plugins/gestion_contacts/Models/mails.php";
	$nom = $_POST['nom'];
	$mails = new mails();
	if($_POST['type'] == 'CreateMail'){
		$mails->createMail($nom);
	}
	if($_POST['type'] == 'DeleteMail'){
		$mails->deleteMail($nom);
	}
}

if(isset($_POST['ouverture'])){
	$id = $_POST['id'];
	require_once  ABSPATH . 'wp-content/plugins/gestion_contacts/Models/action.php';
	$action = new action();
	$user = wp_get_current_user();
	$userId = $user->ID;
	$action->fermer($userId, $id);
}

if(isset($_POST['type']) && $_POST['type'] == 'updateReferent'){
	require_once  ABSPATH ."/wp-content/plugins/gestion_contacts/Models/mails.php";
	$mails = new mails();
	$contact = $_POST['contact'];
	$ref = $_POST['ref'];

	$mails->updateListReferent($ref, $contact);

}

if(isset($_POST['type']) && $_POST['type'] == 'updateMailList'){
	require_once ABSPATH.'wp-content/plugins/gestion_contacts/Models/mails.php';
	$mails = new mails();
	$email = $mails->getMails();
	print json_encode($email);
}

if(isset($_POST['type']) && $_POST['type'] == 'getMailContent'){
	$nom = $_POST['nom'];
	require_once ABSPATH.'wp-content/plugins/gestion_contacts/Models/mails.php';
	$mails = new mails();
	$email = $mails->getSpecificMail($nom);
}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/// /////////////////////////////////DAVID////////////////////////////////////////////////////////////////
/// ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


//=======================AFFICHER nom dans la liste=======================/
if(isset($_POST['show'])){
	require_once ABSPATH . 'wp-content/plugins/gestion_contacts/Models/liste.php';
	$liste = new liste();
	$id_liste = $_POST['id_liste'];
	$liste->showUsers($id_liste);
}
//=======================AJOUTER ET SUPPRIMER noms dans une liste=======================/
if(isset($_POST['listeUser'])){
	require_once ABSPATH . 'wp-content/plugins/gestion_contacts/Models/liste.php';
	$liste = new liste();
	$idUser = $_POST['idUser'];
	$idListe = $_POST['idListe'];
	if($_POST['listeUser'] == 'suppression'){
		$liste->delUser($idListe, $idUser);
	}else if ($_POST['listeUser'] == 'ajout'){
		$liste->addUser($idListe, $idUser);
	}
}
//=======================CREER liste=======================/
if(isset($_POST['nom_liste'])){
	require_once ABSPATH . 'wp-content/plugins/gestion_contacts/Models/liste.php';
	$liste = new liste();
	$nom_liste = $_POST['nom_liste'];
	$liste->createList($nom_liste);
}
//=======================SUPPRIMER liste=======================/
if(isset($_POST['delete'])){
	require_once ABSPATH . 'wp-content/plugins/gestion_contacts/Models/liste.php';
	$liste = new liste();
	$id_liste = $_POST['id_liste'];
	$liste->deleteList($id_liste);
}


?>
