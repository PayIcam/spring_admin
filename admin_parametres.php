<?php
  require_once 'includes/_header.php';
  $Auth->allow('admin');

	$maintenance = Config::getDbConfig('maintenance');
	$inscriptions = Config::getDbConfig('inscriptions');
	$modifications_places = Config::getDbConfig('modifications_places');
	$websitename = Config::getDbConfig('websitename');
	$quota_soirees = Config::getDbConfig('quota_soirees');

	if (isset($_POST['edition'])) {
		// debug($_POST); // On a inséré un champ hidden (caché) afin de savoir si on a envoyé ou non le formulaire.
		// on s'occupe de la checkbox maintenance.
		if (isset($_POST['maintenance']) && $maintenance != true) {
			Config::setDbConfig('maintenance',true);
			$maintenance = true;
		}elseif(!isset($_POST['maintenance']) && $maintenance == true){
			Config::setDbConfig('maintenance',false);
			$maintenance = false;
		}
		//
		if (isset($_POST['inscriptions']) && $inscriptions != true) {
			Config::setDbConfig('inscriptions',true);
			$inscriptions = true;
		}elseif(!isset($_POST['inscriptions']) && $inscriptions == true){
			Config::setDbConfig('inscriptions',false);
			$inscriptions = false;
		}
		//
		if (isset($_POST['modifications_places']) && $modifications_places != true) {
			Config::setDbConfig('modifications_places',true);
			$modifications_places = true;
		}elseif(!isset($_POST['modifications_places']) && $modifications_places == true){
			Config::setDbConfig('modifications_places',false);
			$modifications_places = false;
		}
		// websitename
		if (!empty($_POST['websitename']) && $_POST['websitename'] != $websitename) {
			Config::setDbConfig('websitename',$_POST['websitename']);
			$websitename = $_POST['websitename'];
		}
		if (!empty($_POST['quota_soirees']) && $_POST['quota_soirees'] != $quota_soirees) {
			Config::setDbConfig('quota_soirees',$_POST['quota_soirees']);
			$quota_soirees = $_POST['quota_soirees'];
		}
	}

$res = $DB->query("SELECT count(*) id FROM guests");
$counts = array_pop($res);

?>
<?php $title_for_layout = 'Paramètres'; ?>
<?php include 'includes/header.php'; ?>
<h1 class="page-header"><img src="img/icons/gear_48.png" alt="Paramètres"> Paramétrer le site</h1>
<form action="admin_parametres.php" method="POST" class="form-horizontal">
	<input type="hidden" name="edition" value="true" />
	<fieldset>
		<legend>Général</legend>
		<div class="control-group">
			<label class="control-label" for="maintenance">Maintenance du site</label>
			<div class="controls">
				<label class="checkbox">
					<input id="maintenance" type="checkbox" name="maintenance" value="true" <?php if(isset($maintenance) && $maintenance == true){echo 'checked="checked"';} ?>>
				</label>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="inscriptions">Les inscriptions du Spring sont elles ouvertes</label>
			<div class="controls">
				<label class="checkbox">
					<input id="inscriptions" type="checkbox" name="inscriptions" value="true" <?php if(isset($inscriptions) && $inscriptions == true){echo 'checked="checked"';} ?>>
				</label>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="modifications_places">Les modifications des places du Spring sont elles modifiables par les icam ?</label>
			<div class="controls">
				<label class="checkbox">
					<input id="modifications_places" type="checkbox" name="modifications_places" value="true" <?php if(isset($modifications_places) && $modifications_places == true){echo 'checked="checked"';} ?>>
				</label>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="websitename">Nom du site</label>
			<div class="controls">
				<label class="checkbox">
					<input id="websitename" type="text" name="websitename" value="<?php if(!empty($websitename)){echo $websitename;} ?>" >
				</label>
			</div>
		</div>
	</fieldset>
	<div class="row-fluid">
		<fieldset class="span6">
			<legend>Quotas type de places</legend>
			<table class="table table-bordered">
				<thead>
					<tr>
						<th>Place</th>
						<th>Actuellement</th>
						<th>Quotas</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><label for="quota_soirees">Soirées</label></td>
						<td><?= $counts['id'] ?></td>
						<td><input class="span6" id="quota_soirees" type="text" name="quota_soirees" value="<?php if(!empty($quota_soirees)){echo $quota_soirees;} ?>" ></td>
					</tr>
				</tbody>
			</table>
		</fieldset>
	</div>

    <div class="control-group">
        <div class="controls">
            <button class="btn btn-primary" type="submit">Sauvegarder</button>
        </div>
    </div>
</form>
<?php include 'includes/footer.php'; ?>