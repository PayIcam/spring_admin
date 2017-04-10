<?php 
  require_once 'includes/_header.php';
  $Auth->allow('member');
  
  require_once 'class/Participant.class.php';
  // -------------------- Création de la liste des Invités -------------------- //
  require_once 'class/ListGuestsForEntrance.class.php';
  $ListGuests = new ListGuestsForEntrance(array('perPages'=>0));
  if ((isset($_GET['page']) && $_GET['page'] != $ListGuests->page) || (isset($_POST['page']) && $_POST['page'] != $ListGuests->page)) {
    header('Location:admin_liste_guests.php');exit;
  }

  // vérifions si il y a une demande de suppréssion de post
  Participant::check_delete();

  $title_for_layout = 'Liste des Invités au Spring';
  $required_script[] = 'admin_search_guest_entrees.js';
  include 'includes/header.php';
?>

<h1 class="page-header clearfix">
  <div class="pull-left">Entrées du Spring</div>
  <div class="pull-right">
    <a href="admin_liste_guests.php" class="btn btn-primary btn-large">Liste des invités</a>
  </div>
</h1>

<form id="form" action="admin_liste_guests.php" method="POST">
<div class="clearfix"><?php echo $ListGuests->getActionsGroupees(1); ?></div><br>
<table class="table table-bordered table-striped" id="guestsList">
    <thead>
      <?php echo $ListGuests->getTHead(); ?>
    </thead>
    <tbody id="resultat">
      <?php echo $ListGuests->getGuestAsTr(); ?>
    </tbody>
</table>
</form>

<hr>
<p><small>
  <em>Remarque :<br>
    - Vous pouvez éditer le numéro de bracelet au besoin en cliquant dessus ;) n'oubliez pas de valider l'invité tout de même</em><br>
    - Vous pouvez vous simplifier la vie si vs cherchez "Ant Gir" ou "Gir Ant" ou "iraud toi" vous trouverez bien Antoine Giraud<br>
  Bonnes entrées & bon Spring !! Antoine Giraud <em>115</em> ;)
</small></p>

<?php
  // Functions::tablesorter('guestsList','[5,1],[2,0]','8: {sorter: false}');

  include 'includes/footer.php';
?>