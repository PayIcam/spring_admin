<?php 
  require_once 'includes/_header.php';
  $Auth->allow('member');


  if (!empty($_POST['import'])) {
    $_POST['import'] = explode("\n", $_POST['import']);
    $import = array();
    $present     = array();
    $nonExistant = array();
    foreach ($_POST['import'] as $value) {if(empty($value))continue;
      $invite = explode(';', $value);
      $invite = array(
        'nom'    => ucfirst(strtolower($invite[0])),
        'prenom' => ucfirst(strtolower($invite[1])),
        'promo'  => $invite[2]
      );
      if (empty($invite['promo'])) continue;
      $import[] = $invite;
      $id = current($DB->queryFirst('SELECT id FROM guests WHERE nom = :nom AND prenom = :prenom',array('nom'=>$invite['nom'],'prenom'=>$invite['prenom'])));
      if (empty($id)) $nonExistant[] = $invite;
      else $present[] = $invite;
    }
  }

  $title_for_layout = 'Check People';
  include 'includes/header.php';

?>
<h1 class="page-header clearfix">
  <div class="pull-left"><img src="img/icons/contact.png" alt=""> Checking de masse</div>
</h1>
<?php 
  if (!empty($_POST['import'])) {
    debug($import ,'$import');
    debug($present ,'$present');
    debug($nonExistant ,'$nonExistant');
  }
?>
<form action="admin_check_students.php" method="POST">
  <textarea name="import" placeholder="nom; prenom; promo" style="width:100%;height:400px"></textarea>
  <div class="form-actions">
      <button class="btn btn-primary" type="submit">Submit</button>
  </div>
</form>

<?php
  include 'includes/footer.php';
?>