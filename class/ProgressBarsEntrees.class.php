<?php 

require_once 'includes/_header.php';
require_once ROOT_PATH.'class/Participant.class.php';

/**
* Classe des ProgressBarsEntrees pour le Gala
*/
class ProgressBarsEntrees{

	private $totalGuests;
	private $totalGuestsArrived;

	const height='10';
	const width='120';

	function __construct($id=-1,$attr=array()){
		global $DB;
		$this->totalGuests           = $DB->findCount('entrees','','guest_id');
		$this->totalGuestsArrived    = $DB->findCount('entrees',array('arrived'=>1),'guest_id');
		$this->totalGuestsNotArrived = $this->totalGuests - $this->totalGuestsArrived;
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
			// if (isset($this->guestsNumbers[$var])) {
			// 	return $this->guestsNumbers[$var];
			// }elseif (isset($this->icamAndTheirGuests[$var])) {
			// 	return $this->icamAndTheirGuests[$var];
			// }elseif (isset($this->nightOptions[$var])) {
			// 	return $this->nightOptions[$var];
			// }
		}else return $this->$var;
	}
}