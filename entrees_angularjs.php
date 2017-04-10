<?php 
  require_once 'includes/_header.php';
  $Auth->allow('member');

  $title_for_layout = 'Gestion des entrées le soir même - AngularJS';
  $required_script[] = 'lib/lodash.min.js';
  $required_script[] = 'lib/angular.js';
  $required_script[] = 'lib/angular-sanitize.min.js';
  $required_script[] = 'app.js';
  $required_script[] = 'controllers/PageCtrl.js';
  $required_script[] = 'controllers/GuestsCtrl.js';
  $required_script[] = 'components/alert/alert.js';
  $required_script[] = 'components/modal/modal.js';
  include 'includes/header.php';
?>

<div ng-app="app" ng-controller="PageCtrl">
  <alert ng-repeat="alert in alerts" type="{{alert.type}}" dismiss-on-timeout="{{alert.timeout}}" close="closeAlert($index)">{{alert.msg}}</alert>


  <h1 class="page-header clearfix">
    <div class="pull-left">Entrées du Spring</div>
    <div class="pull-right">
      <a href="admin_liste_guests.php" class="btn btn-primary btn-large">Liste des invités</a>
    </div>
  </h1>

  <div ng-controller="GuestsCtrl">
    
    <form>
      <div class="clearfix">
        <div class="pull-left form-search" style="margin-left:15px;">
          <div class="input-append">
            <input class="input-medium search-query span6" ng-model="keyword" placeholder="#bracelet OU prenom nom OU nom prenom" type="text" ng-change="getGuests()">
            <a class="btn" ng-click="getGuests()">Search</a>
          </div>
          <small ng-show="loader" class="loader" style="margin-left:10px;"><img src="img/icons/spinner.gif" alt="loader"></small>
        </div>
        <p class="pull-right">
          <em><span class="guestCount" title="nombre d'utilisateurs affichés">{{countSqlReturnedGuests}} / {{countGuests}}</span> invités</em>
        </p>
      </div><br>
    </form>
    <table class="table table-bordered table-striped" id="guestsList">
        <thead>
          <tr>
            <th>Id</th>
            <th>Bracelet</th>
            <th>Prenom</th>
            <th>Nom</th>
            <!-- <th>Formule</th> -->
            <th>Promo</th>
            <!-- <th>Nb Invités</th> -->
            <th>Inscription</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody id="resultat">
          <tr ng-class="{'warning':guest.is_icam==0}" ng-repeat="guest in guests">
            <td>
              <span ng-class="{1:'badge-success', 0:'badge-info'}[guest.is_icam]" class="badge" title="{{{1:'Icam', 0:'Invité'}[guest.is_icam]}}">
                <span class="hidden">{{guest.invitor.id}}</span>
                {{guest.id}}
              </span>
            </td>
            <td>
              <span ng-class="{1:'badge-success', 0:'badge-info'}[guest.is_icam]" class="span_bracelet_id badge" title="{{{1:'Icam', 0:'Invité'}[guest.is_icam]}}" style="cursor:pointer;"
                id="span_bracelet_id_for{{guest.id}}" data-braceletid="{{guest.bracelet_id}}" data-guestid="{{guest.id}}" >
                {{guest.bracelet_id}}
              </span>
            </td>
            <td>{{guest.prenom}}</td>
            <td>{{guest.nom}}</td>
  <!--           <td>{{getFormuleSoiree(guest)}}</td> -->
            <td>{{guest.promo}}</td>
            <!-- <td>{{getCountGuest(guest)}}</td> -->
            <td>{{guest.inscription}}</td>
          </tr>
        </tbody>
    </table>
  </div>

  <hr>
  <p><small>
    <em>Remarque :<br>
      - Vous pouvez éditer le numéro de bracelet au besoin en cliquant dessus ;) n'oubliez pas de valider l'invité tout de même</em><br>
      - Vous pouvez vous simplifier la vie si vs cherchez "Ant Gir" ou "Gir Ant" ou "iraud toi" vous trouverez bien Antoine Giraud<br>
    Bonnes entrées & bon Spring !! Antoine Giraud <em>115</em> ;)
  </small></p>
</div>
<?php
  // Functions::tablesorter('guestsList','[5,1],[2,0]','8: {sorter: false}');

  include 'includes/footer.php';
?>