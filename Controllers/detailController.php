<?php
//require_once ABSPATH .'/wp-content/plugins/test_plugin/Controllers/controllerController.php';

/**
 * Created by PhpStorm.
 * User: John
 * Date: 15/05/2017
 * Time: 10:36
 */
class detailController extends controllerController {

	public $values;
	public $data;

	public function __construct($includes){

		require_once ABSPATH.'wp-content/plugins/gestion_contacts/Models/detail.php';
		parent::__construct($includes);

		self::styles();

		if(isset($_GET['id'])){
			$post = new Flamingo_Inbound_Message( $_GET['id'] );
			$detail = new detail($_GET['id']);
		}else{

		}

		$paths = $detail->getFilePaths($_GET['id']);
		$secData = $detail->getData();

		$json = array();
		for($i = 0; $i < count($secData['demandes']); $i++){

			$json[$i]['date'] = $secData['demandes'][$i]->date;
			for($x = 0; $x < count($secData['users']); $x++){
				if($secData['users'][$x]->ID == $secData['demandes'][$i]->id_user){
					$json[$i]['users'] = $secData['users'][$x]->user_nicename;
					continue;
				}
			}

			for($x = 0; $x < count($secData['actions']); $x++){
				if($secData['actions'][$x]->ID == $secData['demandes'][$i]->id_action){
					$json[$i]['actions'] = $secData['actions'][$x]->nom;
					continue;
				}
			}

			for($x = 0; $x < count($secData['etats']); $x++){
				if($secData['etats'][$x]->ID == $secData['demandes'][$i]->etat){
//					$json[$i]['etats'] = $secData['etats'][$x]->nom;
					$json[$i]['etats'] = $secData['etats'][$x]->nom;
					continue;
				}
			}

			if($secData['demandes'][$i]->id_sent_mail != 0){
				for($x = 0; $x < count($secData['mails']); $x++){
					if($secData['mails'][$x]->ID == $secData['demandes'][$i]->id_sent_mail){
						$json[$i]['mails'] = $secData['mails'][$x]->contenu;
						continue;
					}
				}
			}
		}

		return $this->render(ABSPATH.'/wp-content/plugins/gestion_contacts/Views/detail.tpl.php', array($secData, $json, $post, $paths));

	}

	public function styles(){

		$css = array_search('/wp-content/plugins/gestion_contacts/includes/css/detail.css', $this->includes['cssSrcs']);
		$js = array_search('/wp-content/plugins/gestion_contacts/includes/js/detail.js', $this->includes['jsSrcs']);
		wp_enqueue_style($css, $this->includes['cssSrcs'][$css], false );
		wp_enqueue_script($js, $this->includes['jsSrcs'][$js], false );

		$css = array_search('/wp-content/plugins/gestion_contacts/includes/css/foundation.css', $this->includes['cssSrcs']);
		$js = array_search('/wp-content/plugins/gestion_contacts/includes/js/foundation.js', $this->includes['jsSrcs']);
		wp_enqueue_style($css, $this->includes['cssSrcs'][$css], false );
		wp_enqueue_script($js, $this->includes['jsSrcs'][$js], false );


//		$js = array_search('/wp-content/plugins/gestion_contacts/includes/js/tableActions.js', $this->includes['jsSrcs']);
//		wp_enqueue_script($js, $this->includes['jsSrcs'][$js], false );

	}




}
