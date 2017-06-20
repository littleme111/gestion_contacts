<?php
/*
Plugin Name: Gestion Contacts
Description: Gestion des demandes de contact
Author:      Ortega David, Feron Balthazar
*/




require_once "frontController.php";
//Calls func upon admin menu load : only for admin users.
add_action('admin_menu', 'func');
function func(){
	//Both menu entries call thefunc : acts as front controller
	add_menu_page('Tous', 'Tous Les Tickets', 'manage_options', 'Gestion/tous', 'thefunc');
	add_menu_page('Mes', 'Mes Tickets', 'manage_options', 'Gestion/mestickets', 'thefunc');
	add_menu_page('Devis','Devis', 'manage_options', 'Gestion/Devis', 'thefunc');
	add_menu_page('RH', 'RH', 'manage_options', 'Gestion/RH', 'thefunc');
	add_menu_page('Contact','Contact', 'manage_options', 'Gestion/Contact', 'thefunc');
	//	add_menu_page('Detail', 'null', 'manage_options', 'Test/detail', 'thefunc');
	add_submenu_page('null', 'Detail', 'Details', 'manage_options',
		'Gestion/detail', 'thefunc');
	add_menu_page('Liste', 'Gestion des Listes', 'manage_options', 'Gestion/liste', 'thefunc');
	add_menu_page('Mails', 'Gestion des Mails', 'manage_options', 'Gestion/mails', 'thefunc');
}



register_activation_hook( __FILE__, 'gestion_install' );
register_activation_hook( __FILE__, 'gestion_install_data' );



function gestion_install(){
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	$tables = ['actions', 'deadlines', 'demandes_historique', 'etats', 'liste', 'mails', 'sent_mails', 'salaries', 'referents'];
	global $wpdb;
	$charset_collate = $wpdb->get_charset_collate();


	$table_name = $wpdb->prefix.$tables[0];
	$sql = "CREATE TABLE $table_name (
		id          Int NOT NULL AUTO_INCREMENT ,
        nom         Varchar (50) NOT NULL ,
        description Varchar (255) NOT NULL ,
        delai       Int NOT NULL ,
        PRIMARY KEY (id )
	) $charset_collate;";
	dbDelta( $sql );

	$table_name = $wpdb->prefix.$tables[1];
	$sql = "CREATE TABLE $table_name (
		id         Int NOT NULL AUTO_INCREMENT,
        id_demande Int NOT NULL,
        deadline   Int NOT NULL,
        PRIMARY KEY (id ) ,
        INDEX (id_demande )
	) $charset_collate;";
	dbDelta( $sql );



	$table_name = $wpdb->prefix.$tables[2];
	$sql = "CREATE TABLE $table_name (
		`id` int(11) NOT NULL AUTO_INCREMENT,
  	`id_user` bigint(11) UNSIGNED NOT NULL,
  	`id_action` int(11)  NULL,
 	 `date` datetime  NULL,
  	`message` varchar(255)  NULL,
  	`id_demande` bigint(20) UNSIGNED NOT NULL,
  	`etat` int(11) NOT NULL,
  	`id_sent_mail` int(11) NULL,
  	`tempsRestant` int(11) NULL COMMENT 'en jours',
        PRIMARY KEY (id )

	) $charset_collate;";
	dbDelta( $sql );

	$table_name = $wpdb->prefix.$tables[3];
	$sql = "CREATE TABLE $table_name (
		id      Int NOT NULL,
        nom         Varchar (50) NOT NULL ,
        description Varchar (255) NOT NULL ,
        PRIMARY KEY (id )
	) $charset_collate;";
	dbDelta( $sql );

	$table_name = $wpdb->prefix.$tables[4];
	$sql = "CREATE TABLE $table_name (
	id        Int NOT NULL AUTO_INCREMENT,
        nom_liste Varchar (50) NOT NULL ,
        PRIMARY KEY (id )
	) $charset_collate;";
	dbDelta( $sql );

	$table_name = $wpdb->prefix.$tables[5];
	$sql = "CREATE TABLE $table_name (
	id        Int NOT NULL AUTO_INCREMENT,
        nom       Varchar (50) ,
        header    Varchar (255) ,
        contenu   Text ,
        signature Varchar (255) ,
        PRIMARY KEY (id ) ,
        INDEX (nom )

	) $charset_collate;";
	dbDelta( $sql );

	$table_name = $wpdb->prefix.$tables[6];
	$sql = "CREATE TABLE $table_name (
	`id` int(11) NOT NULL,
  	`destinataire` varchar(50) NOT NULL,
  	`contenu` text NOT NULL,
  	`expediteur` int(11) NOT NULL,
  	`date_envoi` date DEFAULT NULL,
  	`id_demande` bigint(20) UNSIGNED NOT NULL

	) $charset_collate;";
	dbDelta( $sql );
	$table_name = $wpdb->prefix.$tables[7];
	$sql = "CREATE TABLE $table_name(
	`id` int(11) NOT NULL,
  	`id_user` int(11) NOT NULL,
  	`id_liste` int(11) NOT NULL
	)  $charset_collate;";
	dbDelta( $sql );
	//
	$table_name = $wpdb->prefix.$tables[8];
	$sql = "CREATE TABLE $table_name(
	 `id` int(11) NOT NULL,
  	`id_user` int(11) NOT NULL,
  	`type_demande` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL
	)  $charset_collate;";
	dbDelta( $sql );


	$sql = "ALTER TABLE ch_demandes_historique ADD CONSTRAINT FK_ch_demandes_historique_id_ch_actions FOREIGN KEY (id_action) REFERENCES ch_actions(id);";
	dbDelta( $sql );
	$sql = "ALTER TABLE ch_demandes_historique ADD CONSTRAINT FK_ch_demandes_historique_ID FOREIGN KEY (ID) REFERENCES ch_posts(ID);";
	dbDelta( $sql );


}

function gestion_install_data() {
	global $wpdb;
	$table = $wpdb->prefix.'actions';
	$sql = "INSERT INTO ".$table." (`id`, `nom`, `description`, `delai`) VALUES
	(1, 'Transfert', 'Transférer la demande a un autre salarié', 0),
	(2, 'Reponse', 'Répondre a la demande avec un choix de template de mail', 3),
	(3, 'Fermer', 'Fermer le Ticket', 0);";
	$wpdb->query($sql);

	$table = $wpdb->prefix.'etats';
	$sql = "INSERT INTO ".$table." (`id`, `nom`, `description`) VALUES
	(0, 'Ouvert', 'Le ticket na pas encore été traité'),
	(3, 'Fermé', 'Le ticket est résolu.'),
	(2, 'Répondu', 'Un mail de réponse à été envoyé'),
	(1, 'Transfert', 'Transféré a un autre salarié');";

	$wpdb->query($sql);

	$table = $wpdb->prefix.'mails';
	$sql = "INSERT INTO ".$table." (`nom`, `contenu`) VALUES
	('Mail Type RH', 'Nous  accusons réception de votre offre de collaboration et nous vous remercions de l’intérêt que vous portez à Choosit.
	Votre dossier sera traité dans les plus brefs délais. Cependant, si vous n’avez pas de nouvelles de notre part dans les trois semaines qui suivent ce courrier,
	veuillez considérer que nous ne sommes pas en mesure de répondre favorablement à votre candidature.
	Nous vous prions d’agréer, Madame, Mademoiselle, Monsieur, nos salutations les meilleures.
	Salutations

	Madame / Monsieur

	Responsable des ressources humaines' )";
	$wpdb->query($sql);

	$table = $wpdb->prefix.'liste';

	$sql = "INSERT INTO ".$table."(`id`, `nom_liste`) VALUES
			(1, 'Devis'),
			(2, 'Contact'),
			(3, 'RH')";
	$wpdb->query($sql);

	$table = $wpdb->prefix.'referents';

	$sql = "INSERT INTO ".$table."(`id`, `id_user`, `type_demande`) VALUES
			(1, 1, 'Contact'),
			(2, 1, 'Devis'),
			(3, 1, 'Ressources Humaines')";
	$wpdb->query($sql);


}

register_deactivation_hook(__FILE__, 'on_uninstall');

function on_uninstall(){
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	$tables = ['actions', 'deadlines', 'demandes_historique', 'etats', 'liste', 'mails', 'sent_mails', 'salaries', 'referents'];
	global $wpdb;

	foreach($tables as $table){
		$sql = "DROP TABLE ".$wpdb->prefix.$table.";";

		$wpdb->query($sql);
	}
}

//Hook is used to "intercept" file uploads before they are deleted by the contact form plugin.
//This has been problematic as the file deletion couldn't be stopped, so the copy had to be effective before, any insert in the database,
//Therefore, we had no id to link to the file.
//The solution used here is to copy the file into a folder named after the email of the person.

add_action('wpcf7_before_send_mail', 'getUploads');
function getUploads(){
	require_once ABSPATH . "/wp-content/plugins/gestion_contacts/Models/users.php";
	$users = new users();
	$refs = $users->getMailRefs();
	//Get submissions object.
	$submission = WPCF7_Submission::get_instance();
	//	get Posted data and uploaded files.
	$data = $submission->get_posted_data();
	$files = $submission->uploaded_files();
	$dest = '';
	$liste = '';
	$message = "";
	if($data['_wpcf7'] == 157){
		foreach($refs as $ref){
			if($ref['type_demande'] == "Ressources Humaines"){
				$liste = 'RH';
				$dest = $ref['user_email'];
				$message = $data['your-message'];
			}
		}
		$type = 'rh/';
	}else if($data['_wpcf7'] == 158){
		foreach($refs as $ref){
			if($ref['type_demande'] == "Devis"){
				$dest = $ref['user_email'];
				$message = $data['description-projet'];
				$liste = 'Devis';
			}
		}
		$type = 'devis/';
	}else if($data['_wpcf7'] == 94){
		foreach($refs as $ref){
			if($ref['type_demande'] == "Contact"){
				$dest = $ref['user_email'];
				$message = $data['your-message'];
				$liste = 'Contact';
			}
		}
	}else{
		//in case of error as to not loose files.
		$type = 'undefined/';
	}

	$i = 0;
	//loop on array containing file paths.
	foreach($files as $file){
		//Path is saved for the copy function
		$path = $file;
		//Path is exploded to remove parts of path not pertainig to us.
		$file = explode('/', $file);
		//Last index contains name of file.
		$name = array_pop($file);
		array_pop($file);
		array_pop($file);
		//Put back together.
		$file = implode('/', $file);
		//Check on type of form submission, to create corresponding folder.

		//Set path to variable.
		$check = ABSPATH . "wp-content/uploads/ContactFormUploads/".$type.$data['your-email'];
		//Check if directory exists.
		if(is_dir($check)){
			//Change permissions (in case).
			chmod($file . '/ContactFormUploads/'.$type.$data['your-email'], 0745);
			//Make last directory and copy.
			mkdir($file . '/ContactFormUploads/'.$type.$data['your-email'].'/'.$i, 0745, true);
			copy($path, $file . '/ContactFormUploads/'.$type.$data['your-email'].'/'.$i.'/'.$name);
		}else{
			mkdir($file . '/ContactFormUploads/'.$type.$data['your-email'], 0745);
			mkdir($file . '/ContactFormUploads/'.$type.$data['your-email'].'/'.$i.'/', 0745, true);
			copy($path, $file . '/ContactFormUploads/'.$type.$data['your-email'].'/'.$i.'/'.$name);
		}

		$i++;
	}
	require_once ABSPATH.'/wp-content/plugins/gestion_contacts/Models/liste.php';
	$object = new liste();
	$salaries = $object->getListId($liste);
	$mails = $object->getListMails($salaries);

	foreach($mails as $mail){
		$test[] =  $mail[0][0];
	}
	$dests = implode(',', $test);

	$headers    = array
	(
		'MIME-Version: 1.0',
		'Content-Type: text/html; charset="UTF-8";',
		'Content-Transfer-Encoding: 7bit',
		'Date: ' . date('r', $_SERVER['REQUEST_TIME']),
		'Message-ID: <' . $_SERVER['REQUEST_TIME'] . md5($_SERVER['REQUEST_TIME']) . '@' . $_SERVER['SERVER_NAME'] . '>',
		'X-Mailer: PHP v' . phpversion(),
		'X-Originating-IP: ' . $_SERVER['SERVER_ADDR'],
	);
	$contenu = "Une demande de contact a été soumise 
	sur le site Choosit.com. Vous pourrez retrouver plus de détails dans le back office de choosit.";
	$contenuTotal = $contenu .'Message :  '.$message;
	$contenuTotal = wordwrap($contenuTotal, 70);

	mail($dest.','.$dests, ' Reponse Automatique - Contact Choosit', $contenuTotal, implode("\n", $headers));


}