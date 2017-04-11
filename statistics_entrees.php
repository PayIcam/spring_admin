<?php 
    require_once 'includes/_header.php';
    $Auth->allow('member');
    
    $title_for_layout = "Statistiques des Entrées";
    require_once 'class/StatsCharts.class.php';
    require_once 'class/ProgressBars.class.php';
    $PBars = new ProgressBars();

?>

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

<?php 
include 'includes/footer.php';
?>