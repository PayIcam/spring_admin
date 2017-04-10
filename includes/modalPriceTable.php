<!-- Modal -->
<div id="PriceTable" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">Tableaux des prix</h3>
  </div>
  <div class="modal-body">
    <table class="table table-condensed table-bordered">
      <thead>
        <tr>
          <th></th>
          <th></th>
          <th colspan="4">Prix ICAM</th>
          <th colspan="4">Prix invité</th>
        </tr>
        <tr>
          <th>Promo</th>
          <th>Nb invités</th>
          <th>Soirée (Pumpkin)</th>
          <th>Soirée (Autre)</th>
          <th>Conférence</th>
          <th>Diner</th>
          <th>Soirée (Pumpkin)</th>
          <th>Soirée (Autre)</th>
          <th>Conférence</th>
          <th>Diner</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach (Participant::$prixParPromo as $key => $value): ?>
          <tr>
            <td><strong><?= $key ?></strong></td>
            <td><?= $value['nbInvites'] ?></td>
            <td <?php if ($value['prixIcam']['soiree'] === NULL){echo 'class="none"';} ?>>
              <?= ($value['prixIcam']['soiree'] > 0)? $value['prixIcam']['soiree'] - 2 : ($value['prixIcam']['soiree'] === 0?'0':'') ?>
            </td>
            <td <?php if ($value['prixIcam']['soiree'] === NULL){echo 'class="none"';} ?>>
              <?= $value['prixIcam']['soiree'] ?>
            </td>
            <td <?php if ($value['prixIcam']['buffet'] === NULL){echo 'class="none"';} ?>>
              <?= (($value['prixIcam']['buffet'] !== NULL)?'+':'') . $value['prixIcam']['buffet'] ?>
            </td>
            <td <?php if ($value['prixIcam']['repas'] === NULL){echo 'class="none"';} ?>>
              <?= $value['prixIcam']['repas'] ?>
            </td>
            <td <?php if ($value['prixInvite']['soiree'] === NULL){echo 'class="none"';} ?>>
              <?= ($value['prixInvite']['soiree'] > 0)? $value['prixInvite']['soiree'] - 2 : ($value['prixInvite']['soiree'] === 0?'0':'') ?>
            </td>
            <td <?php if ($value['prixInvite']['soiree'] === NULL){echo 'class="none"';} ?>>
              <?= $value['prixInvite']['soiree'] ?>
            </td>
            <td <?php if ($value['prixInvite']['buffet'] === NULL){echo 'class="none"';} ?>>
              <?= (($value['prixInvite']['buffet'] !== NULL)?'+':'') . $value['prixInvite']['buffet'] ?>
            </td>
            <td <?php if ($value['prixInvite']['repas'] === NULL){echo 'class="none"';} ?>>
              <?= $value['prixInvite']['repas'] ?>
            </td>
          </tr>
        <?php endforeach ?>
      </tbody>
    </table>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
  </div>
</div>
  </div>
</div>