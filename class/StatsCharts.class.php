<?php 

require_once 'includes/_header.php';
require_once ROOT_PATH.'class/Participant.class.php';

/**
* Classe des statistiques pour le Gala
*/
class StatsCharts{
	const startDate = '2016-11-30';
	const endDate = '2017-01-30';

	public static function getNbOfGuestsPerDay($guestType='all'){
		global $DB;
		if ($guestType == 'all') {
			return Functions::getFirstVals($DB->query('SELECT count(*) count, DATE(inscription) date FROM `guests` GROUP BY DATE(inscription)'));
		}elseif ($guestType == 'receipts') {
			return Functions::getFirstVals($DB->query('SELECT SUM(price) count, DATE(inscription) date FROM `guests` GROUP BY DATE(inscription)'));
		}elseif ($guestType == 'icam') {
			return Functions::getFirstVals($DB->query('SELECT count(*) count, DATE(inscription) date FROM `guests` WHERE is_icam = 1 GROUP BY DATE(inscription)'));
		}elseif ($guestType == 'guest') {
			return Functions::getFirstVals($DB->query('SELECT count(*) count, DATE(inscription) date FROM `guests` WHERE is_icam = 0 GROUP BY DATE(inscription)'));
		}elseif ($guestType == 'soiree') {
			return Functions::getFirstVals($DB->query('SELECT count(*) count, DATE(inscription) date FROM `guests` GROUP BY DATE(inscription)'));
		}elseif ($guestType == 'filles') {
			return Functions::getFirstVals($DB->query('SELECT count(*) count, DATE(inscription) date FROM `guests` WHERE sexe = 2 GROUP BY DATE(inscription)'));
		}elseif ($guestType == 'garcons') {
			return Functions::getFirstVals($DB->query('SELECT count(*) count, DATE(inscription) date FROM `guests` WHERE sexe = 1 GROUP BY DATE(inscription)'));
		}elseif ($guestType == 'etudiants') {
			return Functions::getFirstVals($DB->query('SELECT count(*) count, DATE(inscription) date FROM `guests` WHERE
				(promo >= 116 AND promo <= 121) OR
				(promo >= 2016 AND promo <= 2021) OR
				promo = "Erasmus"
				OR promo = "FC 2017" OR promo = "FC 2015" OR promo = "FC 2016" 
				GROUP BY DATE(inscription)'));
		}elseif ($guestType == 'permaIngesParents') {
			return Functions::getFirstVals($DB->query('SELECT count(*) count, DATE(inscription) date FROM `guests` WHERE
				promo = "Permanent" OR
				promo = "Ingenieur" OR
				promo = "Parent" GROUP BY DATE(inscription)'));
		}elseif ($guestType == 'arrival_dday') {
			return Functions::getFirstVals($DB->query('SELECT count(*) count, HOUR(arrival_time) h, MINUTE(arrival_time) m FROM `guests` WHERE
				arrived GROUP BY DATE(inscription)'));
		}
	}

	public static function sumEvolutions($array){
		$count = 0;
		foreach ($array as $k => $v) {
			$count += $v;
			$array[$k] = $count;
		}
		return $array;
	}

	public static function arrayCountFix($array){
		$count = 0;
		foreach ($array as $k => $v) {
			if ($count<$v)$count=$v;
			if($v == -1){
				$array[$k]=$count;
			}
		}
		return $array;
	}

	public static function getDaysInBetween($startDate=self::startDate, $endDate=self::endDate, $minusOne = true){
		if (!is_int($startDate)) {
			$startDate = strtotime($startDate);
		}
		if (!is_int($endDate)) {
			$endDate = strtotime($endDate);
		}
		if ($startDate>$endDate) {
			$trash=$startDate; $startDate=$endDate; $endDate=$startDate;
		}
		$daysBetween = array();
		for ($d=$startDate; $d < $endDate ; $d+=60*60*24) {
			if ($d<time() && $minusOne) {
				$daysBetween[date('Y-m-d',$d)] = -1;
			}else{
				$daysBetween[date('Y-m-d',$d)] = 0;
			}
		}
		return $daysBetween;
	}

	public static function getEvolution($startDate=self::startDate, $endDate=self::endDate, $guestType='all'){
		$GuestNbPerDays = self::sumEvolutions(self::getNbOfGuestsPerDay($guestType));
		if (preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $startDate) && preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $endDate)) {
			$daysBetween = self::getDaysInBetween($startDate,$endDate);
			return self::arrayCountFix(Functions::arrayMerge($daysBetween,$GuestNbPerDays));
		}else
			return $GuestNbPerDays;
	}

	public static function getDailyData($startDate=self::startDate, $endDate=self::endDate, $guestType='all'){
		$GuestNbPerDays = self::getNbOfGuestsPerDay($guestType);
		if (preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $startDate) && preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $endDate)) {
			$daysBetween = self::getDaysInBetween($startDate,$endDate,false);
			return Functions::arrayMerge($daysBetween,$GuestNbPerDays);
		}else
			return $GuestNbPerDays;
	}

	public static function getEvolutionOfBothIcamAndGuests($chartDomId='participantChart',$chartTitle='Evolution des Recettes',$startDate=self::startDate, $endDate=self::endDate){
		$EtudiantsicamEvolution          = self::getEvolution($startDate,$endDate,'etudiants');
		$PermanentsIngesParentsEvolution = self::getEvolution($startDate,$endDate,'permaIngesParents');
		$guestEvolution                  = self::getEvolution($startDate,$endDate,'guest');
		
		$daysBetween = self::getDaysInBetween($startDate,$endDate);
		foreach ($daysBetween as $k => $v) {
			$daysBetween[$k] = array('etudiants'=>$EtudiantsicamEvolution[$k],'permaIngesParents'=>$PermanentsIngesParentsEvolution[$k],'guest'=>$guestEvolution[$k]);
		}
		return self::getChartJsScript($chartDomId,$chartTitle,array('Etudiants Icam','Permanants Ingés Parents','Invités'),$daysBetween);
	}

	public static function getEvolutionOfGirls($chartDomId='optionsChart',$chartTitle='Evolution des Recettes',$startDate=self::startDate, $endDate=self::endDate){
		$garconsEvolution = self::getEvolution($startDate,$endDate,'garcons');
		$fillesEvolution  = self::getEvolution($startDate,$endDate,'filles');
		
		$daysBetween = self::getDaysInBetween($startDate,$endDate);
		foreach ($daysBetween as $k => $v) {
			$daysBetween[$k] = array('garcons'=>$garconsEvolution[$k],'filles'=>$fillesEvolution[$k]);
		}
		return self::getChartJsScript($chartDomId,$chartTitle,array('Hommes', 'Femmes'),$daysBetween);
	}

	public static function getEvolutionOfBuffetsAndRepas($chartDomId='GirlsChart',$chartTitle='Evolution des Recettes',$startDate=self::startDate, $endDate=self::endDate){
		$buffetEvolution        = self::getEvolution($startDate,$endDate,'buffet');
		$repasEvolution       = self::getEvolution($startDate,$endDate,'repas');
		$soireeSeuleEvolution = self::getEvolution($startDate,$endDate,'soiree');
		
		$daysBetween = self::getDaysInBetween($startDate,$endDate);
		foreach ($daysBetween as $k => $v) {
			$daysBetween[$k] = array('buffet'=>$buffetEvolution[$k],'repas'=>$repasEvolution[$k],'soiree'=>$soireeSeuleEvolution[$k]);
		}
		return self::getChartJsScript($chartDomId,$chartTitle,array('Buffets', 'Repas', 'Soirée seule'),$daysBetween);
	}

	public static function getEvolutionOfReceipts($chartDomId='receiptsChart',$chartTitle='Evolution des Recettes',$startDate=self::startDate, $endDate=self::endDate){
		$receiptsEvolution = self::getEvolution($startDate,$endDate,'receipts');
		return self::getChartJsScript($chartDomId,$chartTitle,array('Recettes'),$receiptsEvolution);
	}

	public static function getReceiptsPerDays($chartDomId='dailyReceiptsChart',$chartTitle='Recettes par jour',$startDate=self::startDate, $endDate=self::endDate){
		$receiptsEvolution = self::getDailyData($startDate,$endDate,'receipts');
		return self::getChartJsScript($chartDomId,$chartTitle,array('Recettes'),$receiptsEvolution);
	}

	static public function getChartJsScript($chartDomId,$chartTitle,$legend,$data){
		if (!is_array($data) && preg_match('/^\[\]$/', $data)) {
			$dataString = $data;
		}else{
			if (is_array($legend)) {
				if (count($legend) == count(current($data)))
					$legend = '["Date","'.implode('","', $legend).'"]';
				elseif (count($legend) == (1+count(current($data))) )
					$legend = '["'.implode('","', $legend).'"]';
				else
					echo "Erreur dans la légende...";
			}
			$dataString = "";
			foreach ($data as $k => $v){
				if (!is_array($v)) $dataString.="['$k',".$v."],
";				else $dataString.="['$k',".implode(",", $v)."],
";
			}
		}
		//['Date', 'Recettes'],

		$script = '
<script type="text/javascript">
  google.load("visualization", "1", {packages:["corechart"]});
  google.setOnLoadCallback(drawChart);
  function drawChart() {
    var data = google.visualization.arrayToDataTable([
      '.$legend.',
      '.$dataString.'
    ]);
    var options = {
      title: "'.$chartTitle.'",
      hAxis: {title: "Date",  titleTextStyle: {color: "red"}}
    };
    var chart = new google.visualization.AreaChart(document.getElementById("'.$chartDomId.'"));
    chart.draw(data, options);
  }
</script>
';
		return $script;
	}
}