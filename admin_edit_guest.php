<?php
	require_once 'includes/_header.php';
	$Auth->allow('member');
	if (isset($_GET['id'],$_POST['id']) && $_GET['id'] != $_POST['id']) {
		$_GET['id'] = $_POST['id'];
	}

	require_once 'class/Participant.class.php';

	if (isset($_GET['id']) && $_GET['id'] != -1 && Participant::isGuest($_GET['id'])) {
		$Guest = new Participant($_GET['id']);
		if ($Guest->is_icam == 0) { // On redirige vers la page d'édition de l'icam qui invite
			$Guest = null;
			$Guest = new Participant(Participant::findIcamGarantId($_GET['id']));
		}
		// Cas où on édite un User
		$title_for_layout = 'Editer l\'invité #'.$Guest->id;
		$img  = 'img/icons/user_edit.png';
	}else if (isset($_GET['id']) && $_GET['id'] != -1 && !Participant::isGuest($_GET['id'])){
		// Cas où l'id donnée ne corresponds à aucun utilisateur
		Functions::setFlash('<strong>Erreur :</strong> Cet id ne correspond à aucun invité','error');
		header('Location:admin_liste_guests.php');exit;
	}else if ((isset($_GET['id']) && $_GET['id'] == -1)){
		$Guest = new Participant();
		// Cas de l'ajout d'un nouvel utilisateur
		$_GET["id"] = -1;
		$title_for_layout = 'Ajouter un nouvel invité';
		$img  = 'img/icons/user+.png';
	}else{
		header('Location:admin_edit_guest.php?id=-1');exit;
	}
	if (empty($_POST['is_icam'])) $_POST['is_icam']=0;
	if (isset ($_POST['id'],$_POST['nom'],$_POST['prenom'],$_POST['email'],$_POST['is_icam'],$_POST['promo'])) {
		require_once 'includes/Forms.class.php';
		$form = new form();
		$validate = array(
			'prenom' => array('rule'=>'notEmpty','message' => 'Entrez votre prénom'),
			'nom'    => array('rule'=>'notEmpty','message' => 'Entrez votre nom'),
			'email'  => array('rule'=>'([a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4})?','message' => 'Email non valide')
	    );
	    $form->setValidates($validate);

	    $d = $Guest->checkForm($_POST); // $_POST for invite table : 'id','prenom','nom','email','repas','promo','telephone','is_icam'
	    $form->set($d);

	    if ($form->validates($d)) { // fin pré-traitement
	    	if (!empty($_POST['date']) && !is_int($_POST['date']) && preg_match('/[0-9]{4}-[0-9]{2}-[0-9]{2}/', $_POST['date'])) {
	    		$Guest->date = strtotime($_POST['date']);
	    	}

	        $Guest->save();
	        if ($Guest->is_icam == 1) {
	        	$Guest->saveInvites($_POST['invites']);
	        }
	    	header('Location:admin_edit_guest.php?id='.$Guest->id);exit;
	    }
	}

	include 'includes/header.php';
	require_once 'includes/Forms.class.php'; if(!isset ($form)){$form = new form();}
	$form->set($Guest->getAttrIdGuest());

?>

<h1 class="page-header"><img src="<?php echo $img; ?>" alt=""> <?php echo $title_for_layout; ?></h1>

<?php // debug($Guest,'$Guest'); ?>

<form action="admin_edit_guest.php?id=<?= $_GET["id"] ?>" method="post" class="form-horizontal">

    <fieldset>
        <legend>Icam / Permanent :</legend>

        <div class="row">
        	<div >
        		<?php echo $form->input('id', 'hidden', array('value'=>$_GET["id"])); ?>
        		<?php echo $form->input('is_icam', 'hidden', array('value'=>true)); ?>
        	    <?php if($Auth->isAdmin())
			    	echo $form->input('inscription','Date d\'inscription : ', array('maxlength'=>"20",'class'=>'datetimepicker'));
			    	else echo $form->input('inscription', 'hidden');?>
        	    <?php echo $form->input('nom','Nom : ', array('maxlength'=>"155")); ?>
        	    <?php echo $form->input('prenom','Prénom : ', array('maxlength'=>"155"/*, 'required'=>'1'*/)); ?>
        		<?php echo $form->select('sexe', 'Homme/Femme : ', array('data'=>Participant::$sexe)); ?>
        		<?php echo $form->select('paiement', 'Paiement : ', array('data'=>Participant::$paiement)); ?>
        	    <?php echo $form->select('promo','Promotion : ', array('data'=>Participant::$promos)); ?>
        	    <?php echo $form->input('email','Email : ', array('maxlength'=>'255')); ?>
        	    <?php echo $form->input('telephone','Telephone : ', array('maxlength'=>'255')); ?>
        	    <?php echo $form->input('bracelet_id','Numero du bracelet : ', array('maxlength'=>'4','class'=>'input-mini bracelet_id')); ?>

        	</div>
        </div>

    </fieldset>

    <fieldset class="isIcam">

    	<?php $nb = ((count($Guest->invites)>2)?count($Guest->invites):2);
    	 for ($i=0; $i < $nb; $i+=2) { ?>
			<div class="row">
				<?php echo Participant::getGuestForm($i); ?>
				<?php if ($i+1 < $nb) echo Participant::getGuestForm($i+1); ?>
			</div>
		<?php } ?>

    </fieldset>

    <div class="form-actions">
        <button class="btn btn-primary" type="submit">Sauvegarder</button>
        &nbsp;
        <button class="btn" type="reset">Annuler</button>
    </div>

</form>

<script src="js/admin_edit_guest.js"></script>

<?php include 'includes/footer.php'; ?>