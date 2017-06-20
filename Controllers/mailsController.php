<?php
//require_once ABSPATH .'/wp-content/plugins/test_plugin/Controllers/controllerController.php';
/**
 * Created by PhpStorm.
 * User: John
 * Date: 04/05/2017
 * Time: 17:45
 */
class mailsController extends controllerController {
	public $data = array();
	public $myData = array();
	public $idsToString;
	public $refId;

	public function __construct($includes){
		parent::__construct($includes);
		$this->includes = $includes;
		self::styles();
		require_once ABSPATH.'wp-content/plugins/gestion_contacts/Models/mails.php';
		$mails = new mails();
		$email = $mails->getMails();
		$contacts = $mails->getReferents();



		require_once ABSPATH .'wp-content/plugins/gestion_contacts/Models/users.php';
		$user = new users();
		$users = $user->getUsers();

		return $this->render(ABSPATH.'wp-content/plugins/gestion_contacts/Views/mails.tpl.php', array($email, $users, $contacts));
	}
	public function styles(){
		$css = array_search('/wp-content/plugins/gestion_contacts/includes/css/foundation.css', $this->includes['cssSrcs']);
		$js = array_search('/wp-content/plugins/gestion_contacts/includes/js/foundation.js', $this->includes['jsSrcs']);
		wp_enqueue_style($css, $this->includes['cssSrcs'][$css], false );
		wp_enqueue_script($js, $this->includes['jsSrcs'][$js], false );

		$css = array_search('/wp-content/plugins/gestion_contacts/includes/css/mails.css', $this->includes['cssSrcs']);
		$js = array_search('/wp-content/plugins/gestion_contacts/includes/js/mails.js', $this->includes['jsSrcs']);
		wp_enqueue_style($css, $this->includes['cssSrcs'][$css], false );
		wp_enqueue_script($js, $this->includes['jsSrcs'][$js], false );



	}
}