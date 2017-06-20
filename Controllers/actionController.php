<?php
require_once ABSPATH .'/wp-content/plugins/gestion_contacts/Controllers/controllerController.php';
/**
 * Created by PhpStorm.
 * User: John
 * Date: 10/05/2017
 * Time: 08:25
 */
class actionController extends controllerController {

	public $users;

	public function __construct(){
		include_once  ABSPATH . '/wp-config.php';
		require_once ABSPATH .'/wp-content/plugins/gestion_contacts/Models/mails.php';
		require_once ABSPATH .'/wp-content/plugins/gestion_contacts/Models/users.php';
		require_once ABSPATH.'/wp-content/plugins/gestion_contacts/Models/action.php';

	}

	public function loadMails($email, $id){
		$model = new mails();
		$mails = $model->getMails();
		$models = new Users();
		$users = $models->getUsers();
		try{
			return $this->render(ABSPATH .'/wp-content/plugins/gestion_contacts/Views/reponse.tpl.php', array($mails, $users, $email, $id));
		}catch(Exception $e){
			error_log(print_r('render fail :'.$e, true));
		}

	}

	public function transferer($id){
//		require_once ABSPATH .'/wp-content/plugins/test_plugin/Models/users.php';
		$model = new users();
		$users = $model->getUsers();

		return $this->render(ABSPATH .'/wp-content/plugins/gestion_contacts/Views/transferer.tpl.php', array($users, $id));
	}
	public function fermer($id, $userId){
		$action = new action();
		$action->fermer($userId, $id);
	}

	public function updateReferent($salarie, $id){
		$action = new action();
		$action->updateReferent($salarie, $id);
	}


	public function sendMail($mail, $contenu, $expediteur, $destinataire, $id){

		$models = new Users();
		$idUser = $models->getUsersId($expediteur);
		$idUser = $idUser[0]->id;

		$model = new mails();
		$lastid = $model->logSentMail($destinataire, $contenu, $idUser, $id);

		$action = new action();
		$action->logSentMailHistorique($idUser, $id,  $lastid);


		$deadline = $action->getDeadlineId($id);

		if(isset($deadline[0]->id_demande)){
			$tempsRestant = $this->remTimeAction($id, 3);
			$action->updateDeadline($tempsRestant, $id);

		}else{
			$tempsRestant = $this->calculateRemTime($id, 3);
			$action->insertDeadline($id, $tempsRestant);
		}
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
		$contenu = wordwrap($contenu, 70);
		mail('b.feron@choosit.com, d.ortega@choosit.com', ' Reponse Automatique - Contact Choosit', $contenu, implode("\n", $headers));



//		mail('b.feron@choosit.com, d.ortega@choosit.com', 'Reponse Automatique - Contact Choosit', $contenu);

	}

	public function calculateRemTime($id, $addedTime){
		require_once ABSPATH . 'wp-content/plugins/flamingo/admin/includes/class-inbound-messages-list-table.php';

		$post = new Flamingo_Inbound_Message($id);
		$date = $post->date;

		$type = $post->channel;

		$tps = 0;
		switch($type){
			case 'formulaire-de-contact-1':
				$tps = 15;
				break;

			case 'rejoindre-la-team':
				$tps = 10;
				break;

			case 'lancez-votre-projet':
				$tps = 15;
				break;
		}
		$tps = $tps + $addedTime;
		$tpsRestant = date('d-m-Y h:m:s', strtotime($date. "+".$tps." days"));
		$timeStampRestant = strtotime($tpsRestant);
		return $timeStampRestant;
	}

	public function remTimeAction($id, $addedTime){
		$action = new action();
		$date = $action->getDeadline($id);
		$date = $date[0]->deadline;

		$date = date('d-m-Y h:m:s', $date);
		$tpsRestant = date('d-m-Y h:m:s', strtotime($date. "+".$addedTime." days"));

		$timeStampRestant = strtotime($tpsRestant);
		return $timeStampRestant;
	}

}
