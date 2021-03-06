<?php

require_once 'includes/_header.php';
require_once ROOT_PATH.'class/Participant.class.php';

/**
* Classe des ProgressBars pour le Gala
*/
class ProgressBars{

	private $totalGuests;
	private $difference;
	private $guestsNumbers;
	private $icamAndTheirGuests;
	private $nightOptions;
	private $recettes;
	private $totalGuestsArrived;

	const height='10';
	const width='120';

	function __construct($id=-1,$attr=array()){
		global $DB;
		$this->totalGuests = $DB->findCount('guests');
		$this->totalGuestsArrived    = $DB->findCount('entrees',array('arrived'=>1),'guest_id');
		$this->totalGuestsNotArrived = $this->totalGuests - $this->totalGuestsArrived;

		$this->difference = current($DB->queryFirst('SELECT d.count-dd.count FROM (SELECT COUNT(*) count FROM guests  WHERE inscription >= CURDATE() AND inscription < CURDATE() + INTERVAL 1 DAY)d, (SELECT COUNT(*) count FROM guests  WHERE inscription >= CURDATE() - INTERVAL 1 DAY AND inscription < CURDATE())dd'));
        // -------------------------------------------------- //
		$this->guestsNumbers['guests']= $DB->findCount('icam_has_guest','','icam_id');
		$this->guestsNumbers['Icam']  = $this->totalGuests - $this->guests;
		$this->guestsNumbers['les2018']= $DB->findCount('guests',array('promo'=>2018));
		$arrayPromsIcamLille          = array_merge(Participant::$promos['Intégrés'],Participant::$promos['Intégrés'],array(Participant::$promos['Erasmus']));
		$this->guestsNumbers['icamLille'] = $DB->findCount('guests',array('is_icam'=>1,'cond'=>'promo = "'.implode('" OR promo = "', $arrayPromsIcamLille).'"'));
		$this->guestsNumbers['permanantsIngesParents'] = $DB->findCount('guests',array('cond'=>'promo = "Permanent" OR promo = "Ingenieur" OR promo = "Parent"'));
		$this->guestsNumbers['EtudiantsSans2018']       = $this->Icam - $this->les2018 - $this->permanantsIngesParents;
		$this->guestsNumbers['progressionGuest']       = current($DB->queryFirst('SELECT COUNT(*) count FROM guests  WHERE inscription >= CURDATE() AND inscription < CURDATE() + INTERVAL 1 DAY'));
        // -------------------------------------------------- //
        $this->icamAndTheirGuests = array(
	        'IcamOneGuest' => current($DB->queryFirst('SELECT COUNT(*) count FROM (SELECT COUNT(*) c FROM icam_has_guest GROUP BY icam_id)i  WHERE i.c=1')),
	        'IcamTwoGuest' => current($DB->queryFirst('SELECT COUNT(*) count FROM (SELECT COUNT(*) c FROM icam_has_guest GROUP BY icam_id)i  WHERE i.c=2')),
	        'progressionIcam' => current($DB->queryFirst('SELECT COUNT(*) count FROM guests  WHERE is_icam=1 AND inscription >= CURDATE() AND inscription < CURDATE() + INTERVAL 1 DAY'))
        );
        $this->icamAndTheirGuests['IcamNoGuest']  = $this->Icam - $this->IcamOneGuest - $this->IcamTwoGuest;
        // -------------------------------------------------- //
        $this->recettes = current($DB->queryFirst('SELECT (SUM(price)) globalPrice FROM guests'));
        $this->progressionRecettes = current($DB->queryFirst('SELECT (SUM(price)) dayRecettes FROM guests  WHERE inscription >= CURDATE() AND inscription < CURDATE() + INTERVAL 1 DAY'));
        $this->prixBDE = (3*($this->guestsNumbers['EtudiantsSans2018']));
        /*
"SELECT * FROM (SELECT COUNT(id) total FROM `guests`)total,
(SELECT COUNT(id) avant FROM `guests` WHERE inscription < '2012-12-25')avant,
(SELECT COUNT(id) apres FROM `guests` WHERE inscription > '2012-12-25')apres,
(SELECT MAX(count) rush FROM (SELECT COUNT(id) count FROM `guests` GROUP BY DATE(inscription))count)rush"
        ///*/
	}

	public function getGuests($width=self::width,$height=self::height){
        return Functions::getMultipleProgressBar(
            array('sum'=>$this->totalGuests, 'all'=>array(
                array('pourcent'=>$this->les2018,'class'=>'warning','title'=>'Nous les 2018 : '.$this->les2018.'/'.$this->totalGuests),
                array('pourcent'=>$this->EtudiantsSans2018,'class'=>'success','title'=>'Etudiants Icam +2018 : '.$this->EtudiantsSans2018.'/'.$this->totalGuests),
                array('pourcent'=>$this->guests,'class'=>'info','title'=>'Invités extérieurs : '.$this->guests.'/'.$this->totalGuests),
                array('pourcent'=>$this->permanantsIngesParents,'class'=>'warning','title'=>'Permanants, Ingénieurs, Parents : '.$this->permanantsIngesParents.'/'.$this->totalGuests)
            )),
            array('height'=>$height,'width'=>$width,'color'=>'green','class'=>'warning','display'=>'inline-block')
        );
	}

	public function geticamAndTheirGuests($width=self::width,$height=self::height){
        return Functions::getMultipleProgressBar(
            array('sum'=>$this->Icam, 'all'=>array(
                array('pourcent'=>$this->IcamTwoGuest,'class'=>'success','title'=>'Icams/Permanants ayant 2 invités : '.$this->IcamTwoGuest.'/'.$this->Icam),
                array('pourcent'=>$this->IcamOneGuest,'class'=>'info','title'=>'Icams/Permanants ayant 1 invités : '.$this->IcamOneGuest.'/'.$this->Icam),
                array('pourcent'=>$this->IcamNoGuest,'class'=>'danger','title'=>'Icams/Permanants ayant 0 invités : '.$this->IcamNoGuest.'/'.$this->Icam),
            )),
            array('height'=>$height,'width'=>$width,'color'=>'','class'=>'danger','display'=>'inline-block')
        );
	}

	public function getNightOptions($width=self::width,$height=self::height){
        return Functions::getMultipleProgressBar(
            array('sum'=>$this->totalGuests, 'all'=>array(
                array('pourcent'=>$this->repas,'class'=>'info','title'=>'Invités allant au repas : '.$this->repas.'/'.$this->totalGuests),
                array('pourcent'=>$this->buffet,'class'=>'success','title'=>'Invités allant au buffet : '.$this->buffet.'/'.$this->totalGuests),
                array('pourcent'=>$this->nonRepas,'class'=>'warning','title'=>'Invités allant qu\'à la soirée : '.$this->nonRepas.'/'.$this->totalGuests),
            )),
            array('height'=>$height,'width'=>$width,'color'=>'','class'=>'warning','display'=>'inline-block')
        );
	}

	public function getGuestsArrival($width=self::width,$height=self::height){
        return Functions::getMultipleProgressBar(
            array('sum'=>$this->totalGuests, 'all'=>array(
                array('pourcent'=>$this->totalGuestsArrived,'class'=>'success','title'=>'Invités arrivés : '.$this->totalGuestsArrived.'/'.$this->totalGuests),
                array('pourcent'=>$this->totalGuestsNotArrived,'class'=>'danger','title'=>'Invités que l\'on attend encore : '.$this->totalGuestsNotArrived.'/'.$this->totalGuests),
            )),
            array('height'=>$height,'width'=>$width,'color'=>'red','class'=>'danger','display'=>'inline-block')
        );
	}

	public function __get($var){
		if (!isset($this->$var)) {
			if (isset($this->guestsNumbers[$var])) {
				return $this->guestsNumbers[$var];
			}elseif (isset($this->icamAndTheirGuests[$var])) {
				return $this->icamAndTheirGuests[$var];
			}elseif (isset($this->nightOptions[$var])) {
				return $this->nightOptions[$var];
			}
		}else return $this->$var;
	}
}