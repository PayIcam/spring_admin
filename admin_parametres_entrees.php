<?php 
  require_once 'includes/_header.php';
  $Auth->allow('admin');

	require_once 'class/Participant.class.php';
	require_once 'class/StatsCharts.class.php';
    require_once 'class/ProgressBars.class.php';
    $PBars = new ProgressBars();

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
<?php $title_for_layout = 'Paramètres du jour même du Spring'; ?>
<?php include 'includes/header.php'; ?>
<h1 class="page-header"><?php echo $title_for_layout; ?></h1>

<table class="table">
    <!-- <caption><h2>Contenu du site</h2></caption> -->
    <tbody>
        <tr>
          <td><strong><?php echo $PBars->totalGuestsArrived.' / '.$PBars->totalGuests; ?></strong></td>
          <td><?php echo $PBars->getGuestsArrival(200); ?></td>
          <td>Invités arrivés au Spring</td>
        </tr>
    </tbody>
</table>
<p>
	<a href="admin_parametres_entrees.php?reset_entrees=1" class="btn btn-danger btn-large" id="ResetEntrees">Réinitialiser les entrées pour le jour même</a>
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