<?php 
	require_once 'includes/_header.php';
	if(!$Auth->isAdmin()){	// sécuriser l'accès
	    Functions::setFlash('<strong>Identification requise</strong> Vous ne pouvez accéder à cette page.','error');
	    header('Location:connection.php');exit;
	}
	require_once 'class/ListGuests.class.php';
	$dataForm = array();
	if (isset($_GET['options'],$_GET['action'],$_GET['recherche1']))
		$dataForm = $_GET;
	else
		$dataForm = $_POST;
	$dataForm['perPages'] = 3000;
	$dataForm['export'] = true;
  	$ListGuests = new ListGuests($dataForm);
  	$ListGuests->exportParticipantsList();
?>