<?php

require_once 'includes/_header.php';

/**
* Classe des participants au Gala
*/
class Participant{
	private $id;
	private $attr;
	private $invites;
	private $invitor;
	private $DB;

	public static $guestsCount;

	public static $promos = array(
		'Intégrés'             =>array(117=>117,118=>118,119=>119,120=>120,121=>121),
		'Apprentis'            =>array(2017=>2017,2018=>2018,2019=>2019,2020=>2020,2021=>2021),
		'Erasmus'              =>'Erasmus',
		'Formations Continues' =>'Formations Continues',
		'Permanent'            =>'Permanent',
		'Ingenieur'            =>'Ingénieur',
		'Parent'               =>'Parent',
		'Artiste'              =>'Artiste',
		'Artiste Icam'         =>'Artiste Icam',
		'Autre Site'           =>'Autre Site',
		'VIP'                  =>'VIP',
    );

	public static $dataPlageHoraireEntree = array(
	    '17h30-19h30'=>'17h30-19h30: Conférence',
	    '19h30-20h'=>'19h30-20h: Diner',
	    '21h-21h45'=>'21h-21h45: 1er créneau',
	    '21h45-22h30'=>'21h45-22h30: 2ème créneau',
	    '22h30-23h'=>'22h30-23h: 3ème créneau',
	    'buffet116'=>'buffet116'
	);
 /*buffet = conference dans la bdd, pas toucher appellation */
	public static $prixBouteilleChamp = 14.5;
	public static $prixParPromo = array(
		'121' => array('nbInvites' => 2,
				'prixIcam' => array("soiree" => 20),
				'prixInvite' => array("soiree" => 22)),
		'120' => array('nbInvites' => 2,
				'prixIcam' => array("soiree" => 20),
				'prixInvite' => array("soiree" => 22)),
		'119' => array('nbInvites' => 2,
				'prixIcam' => array("soiree" => 0),
				'prixInvite' => array("soiree" => 22)),
		'118' => array('nbInvites' => 2,
				'prixIcam' => array("soiree" => 20),
				'prixInvite' => array("soiree" => 22)),
		'117' => array('nbInvites' => 2,
				'prixIcam' => array("soiree" => 20),
				'prixInvite' => array("soiree" => 22)),
		'2021' => array('nbInvites' => 2,
				'prixIcam' => array("soiree" => 20),
				'prixInvite' => array("soiree" => 22)),
		'2020' => array('nbInvites' => 2,
				'prixIcam' => array("soiree" => 20),
				'prixInvite' => array("soiree" => 22)),
		'2019' => array('nbInvites' => 2,
				'prixIcam' => array("soiree" => 20),
				'prixInvite' => array("soiree" => 22)),
		'2018' => array('nbInvites' => 2,
				'prixIcam' => array("soiree" => 20),
				'prixInvite' => array("soiree" => 22)),
		'2017' => array('nbInvites' => 2,
				'prixIcam' => array("soiree" => 20),
				'prixInvite' => array("soiree" => 22)),
		"Formations Continues" => array('nbInvites' => 2,
				'prixIcam' => array("soiree" => 20),
				'prixInvite' => array("soiree" => 22)),
		"Erasmus" => array('nbInvites' => 2,
				'prixIcam' => array("soiree" => 20),
				'prixInvite' => array("soiree" => 22)),
		"Permanents" => array('nbInvites' => 2,
				'prixIcam' => array("soiree" => 22),
				'prixInvite' => array("soiree" => 22)),
		"Ingenieur" => array('nbInvites' => 2,
				'prixIcam' => array("soiree" => 22),
				'prixInvite' => array("soiree" => 22)),
		"Parents" => array('nbInvites' => 2,
				'prixIcam' => array("soiree" => 22),
				'prixInvite' => array("soiree" => NULL)),
		"Artistes" => array('nbInvites' => 2,
				'prixIcam' => array("soiree" => 0),
				'prixInvite' => array("soiree" => 22)),
		"Autre Site" => array('nbInvites' => 2,
				'prixIcam' => array("soiree" => 22),
				'prixInvite' => array("soiree" => 22)),
		"VIP" => array('nbInvites' => 2,
				'prixIcam' => array("soiree" => 20),
				'prixInvite' => array("soiree" => 20))
	);

    public static $paiement = array('espece'=>'En espèces','CB'=>'Carte bancaire','cheque'=>'Par Chèque','Pumpkin'=>'Avec Pumpkin','PayIcam'=>'Avec PayIcam');
    public static $sexe = array('1'=>'Homme','2'=>'Femme');
    public static $formule = array('Soirée'=>'Soirée');

	const TABLE_NAME = 'guests';
	const LOG_FILE = 'log/log.txt';

	// -------------------- Constructeur -------------------- //
	function __construct($id=-1,$attr=array()){
		global $DB;
		$this->DB = $DB;
		// Nouveau participant
		$this->invitor = false;
		$this->id   = $id;
		$this->attr = $attr;
		$this->attr = self::checkGuestFields($this->getAttrPlusId());

		// Récupérer les infos d'un participant
		$this->update();

	}

	// -------------------- Méthodes de base -------------------- //
	public function save(){
		// $search = self::search(array('nom'=>$this->nom,'prenom'=>$this->prenom));
		// if (count($search) == 1) {
		// 	$this->id = current(current($search));
		// }
        $this->id = self::saveParticipantData($this->getAttrPlusId());
        $this->update();
        if ($this->is_icam == 0) {
        	$this->clearGuests();
        }
        return $this->id;
	}
	public function saveFields($fieldsToSave){
		if (!($this->id > 0) || empty($fieldsToSave))
			return false;
		if (!is_array($fieldsToSave))
			$fieldsToSave = array($fieldsToSave);
		$GuestTableFields = Functions::getFirstVals($this->DB->find('SHOW COLUMNS FROM '.self::TABLE_NAME));
		$d = array();
		foreach ($fieldsToSave as $fieldName) {
			if (in_array($fieldName, $GuestTableFields))
				$d[$fieldName] = $this->$fieldName;
		}
		$this->DB->save(self::TABLE_NAME,$d,array('update'=>array('id'=>$this->id)));
        $this->update();
        // Functions::setFlash('Changements effectués (id='.$this->id.')');
        if ($this->is_icam == 0) {
        	$this->clearGuests();
        }
        return true;
	}
	public function update(){
		if (!empty($this->id) && empty($this->attr['nom']) && self::isGuest($this->id)) {
			$this->attr = $this->DB->findFirst(self::TABLE_NAME,array('conditions'=>array('id'=>$this->id)));
			if(empty($this->attr)){Functions::setFlash("Erreur, utilisateur inexistant...");return false;}
			if(isset($this->attr['id'])) unset($this->attr['id']);
			$this->updatePrice();
		}
		$GuestsIds = $this->DB->find('icam_has_guest',array('conditions'=>array('icam_id'=>$this->id),'fields'=>array('guest_id')));
		foreach ($GuestsIds as $guestId) { $guestId = current($guestId);
			if ($guestId != $this->id) {
				$this->invites[] = new Participant($guestId);
			}
		}
	}
	public function getAttrPlusId(){
		$return = !empty($this->attr)?$this->attr:array();
		$return['id'] = $this->id;
		return $return;
	}
	public function getAttrIdGuest(){
		$return = !empty($this->attr)?$this->attr:array();
		$return['id'] = $this->id;
		if (is_array($this->invites)) {
			foreach ($this->invites as $Guest) {
				$return['invites'][] = $Guest->getAttrPlusId();
			}
		}
		return $return;
	}
	public function checkForm($POST){
		$this->attr = self::checkGuestFields($POST); // $POST for invite table : 'id','prenom','nom','email','repas','promo','telephone','is_icam'
	    $this->id   = $POST['id'];
	    $this->updatePrice();
		return $this->getAttrPlusId();
	}
	public function updatePrice(){
		$oldPrice = $this->price;
		$this->price = $this->getPrice($this->getAttrPlusId());
		if ($oldPrice != $this->price)
			$this->saveFields('price');
	}
	public function updateSexe(){
		$prenoms = array('Caroline','Julia','Claire','Emmanuelle','Camille','Anaïs','Djilane','Josephine','Anne-Catherine','Cécile','Clotilde-Marie','Julia','Marine','Marion','Perrine','Ragnheidur','Juliette','Coline','Mylène','Claire-Isabelle','Paula','Aude','Solenne','Mélanie','Carmen','Bertille','Hortense');
		$oldSex = $this->sexe;
    	$this->sexe = (in_array($this->prenom, $prenoms))?2:1;
		if ($oldSex != $this->sexe){
			$this->saveFields('sexe');
		}
	}
	// ------------------------- Pour les Invites ------------------------- //
	public function saveInvites($invites){
		$this->clearGuests();
		for ($i=0; $i < count($invites) ; $i++) { $guest = $invites[$i];
			if ($this->promo == "Parent")
				$guest['promo'] = "Parent";
			if ((isset($guest['id']) && $guest['id'] == $this->id) || (isset($guest['nom'],$guest['prenom']) && $guest['nom'] == $this->nom && $guest['prenom'] == $this->prenom)){
				Functions::setFlash("Erreur, Une personne ne peut pas s'inviter lui même !",'error');
				return false;
			}
			elseif (empty($guest['nom']) && empty($guest['prenom']) && $guest['id'] >0) {
				self::deleteGuest($guest['id']);
			}
			elseif (!empty($guest['nom']) || !empty($guest['prenom'])) {
				// if ($i == 0 || (($this->promo == '118' || $this->promo == '117' || $this->promo == 'Artiste Icam') && $i == 1) || ($this->promo == 'Artiste Icam' && $i == 2)) {
					if ($i > 0 && $this->invites[0]->nom == $guest['nom'] && $this->invites[0]->prenom == $guest['prenom']) {
						Functions::setFlash("Erreur, Vous ne pouvez pas inviter deux personnes du même nom si ?",'error');
						return false;
					}
					$this->invites[$i] = $this->saveInvite($guest);
				// }
			}
		}
	}
	public function saveInvite($invite){
		$G = new Participant();
		$G->invitor = $this->getAttrPlusId();
		$G->checkForm($invite);
		$G->save();
		self::assignGuest($this->id,$G->id);
		return $G;
	}
	public function clearGuests(){
		global $DB;
		$sql = "DELETE FROM icam_has_guest WHERE icam_id = :icam_id";
		$DB->query($sql,array('icam_id'=>$this->id));
		$this->invites = array();
	}
	public function findThisIcamGarantId(){
		global $DB;
		return current($DB->query('SELECT icam_id FROM icam_has_guest WHERE guest_id = :guest_id',array('guest_id' => $this->id)));
	}

	// ---------------------------------------- Static Functions ---------------------------------------- //

	static public function bracelet_exists($guestId,$bracelet_id){
		global $DB;
		if ($guestId == -1) {
	      $q = array('bracelet_id'=>$bracelet_id);
	      $sql = 'SELECT COUNT(bracelet_id) FROM guests WHERE bracelet_id = :bracelet_id';
	    }else{
	      $q = array('bracelet_id'=>$bracelet_id,'id'=>$guestId);
	      $sql = 'SELECT COUNT(bracelet_id) FROM guests WHERE bracelet_id = :bracelet_id AND id != :id';
	    }

	    $Guests = current($DB->queryFirst($sql, $q));
	    if ($Guests >=1)
	      return 1;
	    else
	      return 0;
	}

	static public function getGuestsCount(){
		global $DB;
		if (empty(self::$guestsCount)) {
			self::$guestsCount = $DB->findCount('guests');
			return self::$guestsCount;
		}else{
			return self::$guestsCount;
		}
	}

	public static function getInvites($id,$fields='*'){
		global $DB;
		if (self::isGuest($id)) {
			if (is_array($fields))
				$fields = implode(', ', $fields);
			$sql = 'SELECT '.$fields.' FROM icam_has_guest LEFT JOIN guests ON guest_id = id WHERE icam_id = :icam_id';
			return $DB->query($sql,array('icam_id'=>$id));
		}
		return array();
	}
	public function getInvitor($id,$fields='*'){
		global $DB;
		if ($this->invitor) {
			return $this->invitor;
		} else if (self::isGuest($id)) {
			if (is_array($fields))
				$fields = implode(', ', $fields);
			$sql = 'SELECT '.$fields.' FROM icam_has_guest LEFT JOIN guests ON icam_id = id WHERE guest_id = :guest_id';
			$retour = $DB->queryFirst($sql,array('guest_id'=>$id));
			return $retour;
		}
		return array();
	}
	public static function getInvitorStatic($id,$fields='*'){
		global $DB;
		if (self::isGuest($id)) {
			if (is_array($fields))
				$fields = implode(', ', $fields);
			$sql = 'SELECT '.$fields.' FROM icam_has_guest LEFT JOIN guests ON icam_id = id WHERE guest_id = :guest_id';
			$retour = $DB->queryFirst($sql,array('guest_id'=>$id));
			return $retour;
		}
		return array();
	}

	public static function assignGuest($icam_id,$guest_id){
		global $DB;
		$icam_has_guest = array('icam_id' => $icam_id,'guest_id' => $guest_id);
		// icam_has_guest
		$sql = 'SELECT COUNT(*) FROM icam_has_guest WHERE icam_id = :icam_id AND guest_id = :guest_id';
		$isAlreadyInvited = current($DB->queryFirst('SELECT COUNT(*) FROM icam_has_guest WHERE guest_id = '.$guest_id));
		$sql = "INSERT INTO icam_has_guest (icam_id ,guest_id) VALUES (:icam_id, :guest_id)";
		$DB->query($sql,$icam_has_guest);
		if ($isAlreadyInvited != 0) {
			Functions::setFlash("Un invité du même nom existe déjà",'info');
		}
	}
	public static function search($array){
		global $DB;
		return $DB->find(self::TABLE_NAME,array('conditions'=>$array,'fields'=>'id'));
	}
	public static function saveParticipantData($d){		
		global $DB;
		if (empty($d['nom']) || empty($d['prenom'])) return 0;
		if (empty( $d['id'])) $d['id'] = 0;
		if ($d['id'] != 0 && $d['id'] != -1 && current($DB->queryFirst('SELECT COUNT(*) FROM '.self::TABLE_NAME.' WHERE id = '.$d['id'])) == 1) {
			$id = $d['id']; unset($d['id']);
        	$DB->save(self::TABLE_NAME,$d,array('update'=>array('id'=>$id)));
        	Functions::setFlash("Changements effectués");
        }else{
        	unset($d['id']);
        	$id = $DB->save(self::TABLE_NAME,$d,'insert');
        	Functions::setFlash("Ajout de ".$d['prenom']." effectué");
        	self::ajouterAuxLog(date('Y-m-d H:i:s').' : Ajout invité #'.$id.' '.$d['nom'].' '.$d['prenom'].' '.$d['promo']."\n");
        }
        return $id;
	}
	public static function isGuest($id){
        global $DB;
        if (is_numeric($id) && $DB->findCount(self::TABLE_NAME,array('id'=>$id)) == 1) return true;
    }
	public static function checkGuestFields($attributes){
        global $DB;
        // création du champ attr
		$GuestTable = Functions::getFirstVals($DB->find('SHOW COLUMNS FROM '.self::TABLE_NAME));
		$attr = array();
		foreach ($GuestTable as $tabName) {
			if ($tabName == 'is_icam') $attr[$tabName] = 0;
			elseif ($tabName == 'sexe') $attr[$tabName] = 1;
			elseif ($tabName == 'price') $attr[$tabName] = 20;
			elseif ($tabName == 'inscription') $attr[$tabName] = date('Y-m-d H:i:s');
			else $attr[$tabName] = '';
		}
		$id=$attributes['id'];
		unset($attr['id']);
		foreach ($attr as $key => $v) {
			if (isset($attributes[$key])) {
				if (in_array($key, ['bracelet_id']))
					$attr[$key] = (float) $attributes[$key];
				else
					$attr[$key] = htmlspecialchars($attributes[$key], ENT_QUOTES, "UTF-8");
			}
		}

		if (isset($attr['inscription']) && (empty($attr['inscription']) || $attr['inscription'] == '0000-00-00 00:00:00'))
			$attr['inscription'] = date('Y-m-d H:i:s');
		// $array = $attr;
		// $array['id'] = $id;

		// $attr['price'] = $this->getPrice($array);
		return $attr;
    }

	// public static function secureArrayDatas($array){
	// 	$return = (is_array($array))?$array:array($array);
 //        foreach ($array as $key => $val) {
 //        	if (!is_array($val)) {
 //        		$array[$key] = htmlspecialchars($val, ENT_QUOTES, "UTF-8");
 //        	}
 //        }
 //        return $array;
 //    }

    /**
    * Permet de supprimer une valeur dans la bdd.
    * @global PDO $DB
    * @param string $name
    * @param string $title
    * @return boolean
    **/
    public static function check_delete($page=null){
        if (empty($page))
            $page = 'admin_liste_guests.php';
        if(!empty($_GET['del_guest'])){
            $title = 'Invité';
            self::deleteGuest($_GET['del_guest']);
            // header('Location:'.$page);exit;
        }else if(!empty($_POST['action'])){
        	if ($_POST['action'] == -1 && isset($_POST['action2']) && $_POST['action2'] != -1)
                $_POST['action'] = $_POST['action2'];
            if ($_POST['action'] == 'delete') {
                $flash = array();
                foreach ($_POST['guests'] as $id) {
                    self::deleteGuest($id);
                }
            }
            return false;
        }
    }

	public static function findIcamGarantId($guest_id){
		global $DB;
		if (current($DB->queryFirst('SELECT id FROM guests WHERE id = :id AND is_icam = 1',array('id'=>$guest_id))))
			return $guest_id;
		else
			return current($DB->queryFirst('SELECT icam_id FROM icam_has_guest WHERE guest_id = :guest_id',array('guest_id' => $guest_id)));
	}

    public static function deleteGuest($id){
    	global $DB;
    	if ($DB->findCount('guests',"id=$id") != 0) {
        	$invites = $DB->query('SELECT * FROM icam_has_guest WHERE icam_id = :icam_id',array('icam_id'=>$id));
            $DB->delete('icam_has_guest','icam_id='.$id);
            $DB->delete('icam_has_guest','guest_id='.$id);
            $DB->delete('entrees','guest_id='.$id);
            $DB->delete('guests','id='.$id);
            self::ajouterAuxLog(date('Y-m-d h:i:s').' : Suppression invité #'.$id."\n");
            if (!empty($invites)) {
            	$guestDeleted = 0;
            	foreach ($invites as $guest) {
            		$DB->delete('entrees','guest_id='.$guest['guest_id']);
            		$DB->delete('guests','id='.$guest['guest_id']);
            		self::ajouterAuxLog(date('Y-m-d h:i:s').' : Suppression invité #'.$guest['guest_id']."\n");
            		$guestDeleted++;
            	}
            }
            Functions::setFlash('<strong>Invité #'.$id.' supprimé</strong>'.((!empty($guestDeleted))?' ainsi que ses '.$guestDeleted.' invité'.(($guestDeleted>1)?'s':''):''),'success');
            return true;
        }else{
            Functions::setFlash('<strong>Invité inconnu</strong>','error');
            return false;
        }
    }

	public function getPrice($guest){
		$retour = false;
		if (isset($guest['id'],$guest['promo'],$guest['is_icam'])
			&& ( in_array($guest['promo'], self::$promos) || $guest['promo'] == 'Ingenieur'
			|| in_array($guest['promo'], self::$promos['Intégrés'])
			|| in_array($guest['promo'], self::$promos['Apprentis'])
			|| empty($guest['promo']))
		) {
			if ($guest['is_icam'] == 0) {
				$invitor = $this->getInvitor($guest['id']);
				$retour = self::$prixParPromo[$invitor['promo']]['prixInvite']['soiree'];
			}else{
				$retour = self::$prixParPromo[$guest['promo']]['prixIcam']['soiree'];
			}
		}
		return $retour;
	}

	static public function updateAllDataBase(){
		global $DB;
		debug($DB->queryFirst('SELECT SUM(price) globalPrice FROM '.self::TABLE_NAME),'avant');
		$GuestsIds = Functions::getFirstVals($DB->find(self::TABLE_NAME,array('fields'=>array('id'))));
		// debug($GuestsIds,'$GuestsIds');
		$price = array();
		foreach ($GuestsIds as $id) {
			$Guest = new Participant($id);
			$Guest->updatePrice();
			// $Guest->updateSexe();
			$Guest->saveFields('price');
			$price[$id] = $Guest->price;
		}
		debug($DB->queryFirst('SELECT SUM(price) globalPrice FROM '.self::TABLE_NAME),'après');
	}

	static public function resetEntrees(){
		global $DB;
		$Guests = $DB->query('SELECT id FROM guests');
		foreach ($Guests as $k => $v) {
			$Guests[$k] = array(
				'guest_id'    => current($v)
				// 'arrived'      => 0,
				// 'arrival_time' => $time
			);
		}
		$DB->query('TRUNCATE TABLE entrees;');
		$DB->save('entrees',array('fields'=>array('guest_id'/*,'arrived','arrival_time'*/),'values'=>$Guests),'insert');
		$DB->query('UPDATE entrees LEFT JOIN guests ON id = guest_id SET `arrived` = 1, `arrival_time` = CURRENT_TIMESTAMP( ) WHERE promo = 2018;');
		return true;
	}

	static public function getPromosCount(){
		$retour = 0;
		foreach (self::$promos as $v) {
			$retour+=count($v);
		}
		return $retour;
	}

	static public function getGuestForm($nb = 0){
		global $DB;
		global $form;
		global $Auth;
		if (!isset($form->data['invites'][$nb]['inscription']))
			$form->data['invites'][$nb]['inscription'] = date('Y-m-d H:i:s');
		ob_start(); ?>
		<div id="invite<?php echo ($nb+1); ?>" class="span invite">
		    <legend>Invité <?php echo ($nb+1); ?></legend>
		    <div>
		    	<?php echo $form->input('invites['.$nb.'][id]', 'hidden'); ?>
		    	<?php if($Auth->isAdmin())
		    			echo $form->input('invites['.$nb.'][inscription]','Date d\'inscription : ', array('maxlength'=>"20",'class'=>'datetimepicker'));
		    		else echo $form->input('invites['.$nb.'][inscription]', 'hidden');?>
		        <?php echo $form->input('invites['.$nb.'][nom]','Nom : ', array('maxlength'=>'155')); ?>
		        <?php echo $form->input('invites['.$nb.'][prenom]','Prénom : ', array('maxlength'=>'155'/*, 'required'=>'1'*/)); ?>
		        <?php echo $form->select('invites['.$nb.'][sexe]', 'Homme/Femme : ', array('data'=>Participant::$sexe)); ?>
		        <?php echo $form->select('invites['.$nb.'][paiement]', 'Paiement : ', array('data'=>Participant::$paiement)); ?>
		        <?php echo $form->input('invites['.$nb.'][bracelet_id]','Numero du bracelet : ', array('maxlength'=>'4','class'=>'input-mini bracelet_id')); ?>
		    </div>
		</div>
	    <?php
	    $return = ob_get_contents();
		ob_end_clean();
		return $return;
	}

	/* -------------------- Export -------------------- */

    public static function ajouterAuxLog($msg){
        file_put_contents(self::LOG_FILE, $msg, FILE_APPEND);
    }

	// -------------------- Getters & Setters -------------------- //
	public function __get($var){
		if (!isset($this->$var)) {
			if (isset($this->attr[$var])) {
				return $this->attr[$var];
			}
		}else return $this->$var;
	}
	public function __set($var,$val){
		if (!isset($this->$var)) {
			if (isset($this->attr[$var])) {
				$this->attr[$var] = $val;
			}
		}else $this->$var = $val;
	}
}