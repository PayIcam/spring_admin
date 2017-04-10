<?php 
  require_once 'includes/_header.php';
  $Auth->allow('member');
  
  require_once 'class/Participant.class.php';
  // -------------------- Création de la liste des Invités -------------------- //
  require_once 'class/ListGuests.class.php';
  $ListGuests = new ListGuests($_POST);
  if ((isset($_GET['page']) && $_GET['page'] != $ListGuests->page) || (isset($_POST['page']) && $_POST['page'] != $ListGuests->page)) {
    header('Location:admin_liste_guests.php');exit;
  }

  // debug($ListGuests,'$ListGuests');

  // vérifions si il y a une demande de suppréssion de post
  Participant::check_delete();

  $title_for_layout = 'Liste des participants';
  $required_script[] = 'admin_search_guest.js';
  include 'includes/header.php';
?>

<h1 class="page-header clearfix">
  <div class="pull-left"><img src="img/icons/contact.png" alt=""> Liste des participants au Spring Festival</div>
  <div class="pull-right">
    <a id="export" href="export_liste_participants.php" class="btn btn-primary btn-large" onlick="">Exporter</a>
    <a href="admin_edit_guest.php" class="btn btn-primary btn-large">Ajouter un invité</a>
  </div>
</h1>
<?php 
  require_once 'includes/Forms.class.php'; if(!isset ($form)){$form = new form();}
  $form->set($ListGuests->getListFormParams());
  if (!isset($form->data['options']['selectGuests']))
    $form->data['options']['selectGuests'] = 1;
  if (!isset($form->data['options']['selectAllPromos']))
    $form->data['options']['selectAllPromos'] = 1;
  if (isset($form->data['options']['selectAllPromos'],$form->data['options']['selectGuests']) && $form->data['options']['selectGuests'] == 0 && $form->data['options']['selectAllPromos'] == 0 && empty($form->data['options']['promo'])) {
    $form->data['options']['selectGuests'] = 1;
    $form->data['options']['selectAllPromos'] = 1;
  }
  if (isset($form->data['options']['selectAllPromos'],$form->data['options']['promo']) && $form->data['options']['selectAllPromos'] == 0 && count($form->data['options']['promo']) == count(Participant::$promos))
    $form->data['options']['promo'] = array();
  elseif (isset($form->data['options']['selectAllPromos'],$form->data['options']['promo']) && $form->data['options']['selectAllPromos'] == 1)
    $form->data['options']['promo'] = Participant::$promos;
?>
<div id="post"></div>
<form id="form" action="admin_liste_guests.php" method="POST">
  <?php echo $form->input('page', 'hidden', array('value'=>$ListGuests->page)); ?>
  <div class="clearfix"><?php echo $ListGuests->getActionsGroupees(1); ?></div>
  <div class="pagination"><?php echo $ListGuests->getPagination(); ?></div>
  <?php if ($Auth->isAdmin()): ?>
  <div class="well" id="FormRechercheAvancee" style="display:none;">
    <h2 class="page-header" style="margin:10px auto;">Recherche Avancée</h2>
    <div class="row-fluid">
      <div class="span4">
        <h3>Promos :</h3>
        <p><?php echo $form->input('options[selectGuests]','avec les invités <em>(des promos ci-dessous)</em>',array('type'=>'checkbox','id'=>'selectGuests','checkboxNoClassControl'=>1,'selected'=>'selected')); ?></p>
        <p><?php echo $form->input('options[selectAllPromos]','Toutes les promos',array('type'=>'checkbox','id'=>'selectAllPromos','checkboxNoClassControl'=>1,'selected'=>'selected')); ?></p>
        <p><?php
            echo $form->simpleSelect('options[promo][]',array('data'=>Participant::$promos,'multiple'=>'multiple','id'=>'selectPromos','style'=>"height:200px;",'selected'=>((isset($form->data['options']['selectAllPromos']) && $form->data['options']['selectAllPromos'] == 1) || Participant::getPromosCount() <= count($form->data['options']['promo']))?'all':$form->data['options']['promo']) );
        ?></p>
      </div>
      <div class="span4">
        <h3>Options :</h3>
        <!-- <p><?php echo $form->input('options[promMissingOnes]','Afficher les personnes non inscrites des promos',array('class'=>'buttons-radio','type'=>'checkbox','checkboxNoClassControl'=>1)); ?></p> -->
        <p><?php echo $form->input('options[noBracelet]','Afficher les sans bracelets',array('class'=>'buttons-radio','type'=>'checkbox','checkboxNoClassControl'=>1)); ?></p>
        <p><?php echo $form->input('options[doublons]','Afficher les doublons de bracelets',array('class'=>'buttons-radio','type'=>'checkbox','checkboxNoClassControl'=>1)); ?></p>
        <p><?php echo $form->input('options[monsieurx]','Afficher les monsieurX ...',array('class'=>'buttons-radio','type'=>'checkbox','checkboxNoClassControl'=>1)); ?></p>
      </div>
      <div class="span4">
        <h3>Autres options :</h3>
        <p><?php echo $form->simpleSelect('options[formule][]',array('data'=>Participant::$formule,'multiple'=>'multiple','style'=>"height:61px;",'selected'=>((isset($form->data['options']['formule']) && count(Participant::$formule) > count($form->data['options']['formule']))?$form->data['options']['formule']:'all')) ); ?></p>
        <p><?php echo $form->simpleSelect('options[paiement][]',array('data'=>Participant::$paiement,'multiple'=>'multiple','style'=>"height:61px;",'selected'=>((isset($form->data['options']['paiement']) && count(Participant::$paiement) > count($form->data['options']['paiement']))?$form->data['options']['paiement']:'all')) ); ?></p>
        <p><?php echo $form->simpleSelect('options[sexe][]',array('data'=>Participant::$sexe,'multiple'=>'multiple','style'=>"height:46px;",'selected'=>((isset($form->data['options']['sexe']) && count(Participant::$sexe) > count($form->data['options']['sexe']))?$form->data['options']['sexe']:'all')) ); ?></p>
      </div>
    </div>
    <div class="form-actions" style="margin-bottom: 0;text-align: center;">
      <div class="row-fluid">
        <div class="span10">
          <button class="btn btn-primary btn-large" type="submit" style="width: 100%;">Rechercher</button> 
        </div>
        <div class="span2">
          <a href="admin_liste_guests.php" id="resetSearchAdvancedForm" class="btn btn-large"  style="width: 100%;">Reset</a> 
        </div>
      </div>
    </div>
  </div>
  <?php endif ?>
  <table class="table table-bordered table-striped" id="guestsList">
      <thead>
        <?php echo $ListGuests->getTHead(); ?>
      </thead>
      <tbody id="resultat">
        <?php echo $ListGuests->getGuestAsTr(); ?>
      </tbody>
      <?php if ($ListGuests->countGuests > 10): ?>
      <tfoot>
        <?php echo $ListGuests->getTHead(); ?>
      </tfoot>
      <?php endif ?>
  </table>
  <?php if ($ListGuests->countGuests > 10): ?>
    <div class="pagination"><?php echo $ListGuests->getPagination(); ?></div>
    <div class="clearfix"><?php echo $ListGuests->getActionsGroupees(2); ?></div>
  <?php endif ?>
</form>

<?php
  Functions::tablesorter('guestsList','[8,1],[4,0]','0: {sorter: false},11: {sorter: false}');

  include 'includes/footer.php';
?>