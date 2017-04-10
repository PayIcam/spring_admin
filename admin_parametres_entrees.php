<?php 
  require_once 'includes/_header.php';
  $Auth->allow('admin');

	require_once 'class/Participant.class.php';

	if (!empty($_GET['reset_entrees'])) {
		Participant::resetEntrees();
		Functions::setFlash('Entrées Réinitialisées avec succés');
		// header('Location:admin_parametres_entrees.php');exit;
	}
	if (!empty($_GET['reset_price'])) {
		Participant::updateAllDataBase();
		Functions::setFlash('Entrées Réinitialisées avec succés');
		// header('Location:admin_parametres_entrees.php');exit;
	}
	
?>
<?php $title_for_layout = 'Paramètres du soir même du Spring'; ?>
<?php include 'includes/header.php'; ?>
<h1 class="page-header"><img src="img/icons/gear_48.png" alt="Paramètres"> Paramêtrer les entrées</h1>

<p>
	<a href="admin_parametres_entrees.php?reset_entrees=1" class="btn btn-danger btn-large" id="ResetEntrees">Réinitialiser les entrées pour le soir même</a>
</p>

<p>
	<a href="admin_parametres_entrees.php?reset_price=1" class="btn btn-info btn-large" id="ResetPrice">Réinitialiser les prix de tout le monde</a>
</p>

<script type="text/javascript">
	(function($){
	    $('#ResetEntrees').click(function(event) {
	    	return confirm('Êtes-vous sûr de vouloir réinitialiser les entrées ?\nCesi aura pour cause d\'effacer les entrées actuelles.');
	    });
	    $('#ResetPrice').click(function(event) {
	    	return confirm('Êtes-vous sûr de vouloir réinitialiser les prix ?');
	    });
	})(jQuery);
</script>

<?php include 'includes/footer.php'; ?>