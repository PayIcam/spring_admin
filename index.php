<?php 
    require_once 'includes/_header.php';
    $Auth->allow('member');
    
    $title_for_layout = 'Administration';
    include 'includes/header.php';
    require_once 'class/ProgressBars.class.php';
    $PBars = new ProgressBars();
?>

    <div class="hero-unit">
        <h1>Spring Festival <small><strong>Administration</strong></small></h1> <!--titre en haut-->
        <div class="numbers">
            <div class="bloc" id="days">
            </div>
            <div class="bloc" id="hours">
            </div>
            <div class="bloc" id="minutes">
            </div>
            <div class="bloc last" id="seconds">
            </div>
        </div>
    </div>

    <div class="row-fluid privatePage clearfix">
        <div class="span6 well pull-left">
            <img src="img/logospring.jpg" alt="logo du spring">
        </div>
        <div class="span6 well pull-right">
            <h1 class="page-header">Contenu du site</h1>
            <table class="table">
                <!-- <caption><h2>Contenu du site</h2></caption> -->
                <tbody>
                    <tr>
                      <td><strong><?= $PBars->totalGuests; ?></strong></td>
                      <td><em rel="tooltip" title="Progression du jour">(+<?= $PBars->progressionGuest; ?>)</em></td>
                      <td><?= $PBars->getGuests(); ?></td>
                      <td>Total d'invités au Spring</td>
                    </tr>
                    <tr>
                      <td><strong><?= $PBars->Icam; ?></strong></td>
                      <td><em rel="tooltip" title="Nouveaux Icams du jour">(+<?= $PBars->progressionIcam; ?>)</em></td>
                      <td><?= $PBars->getIcamAndTheirGuests(); ?></td>
                      <td>Les Icams au Spring</td>
                    </tr>
                    <?php if ($Auth->isAdmin()): ?>
                    <tr>
                      <td><strong rel="tooltip"><?= $PBars->recettes; ?> €</strong></td>
                      <td><em rel="tooltip" title="Recettes du jour">(+<?= $PBars->progressionRecettes; ?>)</em></td>
                      <td></td>
                      <td>Recettes totales du Spring</td>
                    </tr>
                    <?php endif ?>
                    <?php if ($Auth->isAdmin()): ?>
                    <tr>
                        <td><strong><?= $DB->findCount('administrateurs'); ?></strong></td>
                        <td></td>
                        <td>Administrateurs</td>
                    </tr>
                    <?php endif ?>
                </tbody>
            </table>
        </div>
    </div>
<script src="js/countdown.js"></script>
<?php include 'includes/footer.php'; ?>