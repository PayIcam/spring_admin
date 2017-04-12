<?php
require_once 'includes/_header.php';
?><!DOCTYPE html>
<html lang="fr">

  <head>

    <meta charset="utf-8">
    <title><?php if(isset($title_for_layout)){echo $title_for_layout.' - ';} ?><?php echo WEBSITE_TITLE; ?></title> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Site internet du Spring pour les inscriptions">
    <meta name="author" content="Thibaut de Gouberville 118, Guillaume Dubois 119, Antoine Giraud 115">
    <link rel="stylesheet" href="css/jqueryui/style.css">
    <link href="css/bootstrap.css" rel="stylesheet">

    <style>
      body {
        padding-top: 110px; 
      }
    </style>

    <link href="css/bootstrap-responsive.css" rel="stylesheet">
    <link rel="stylesheet" href="css/Aristo/Aristo.css">
    <link rel="stylesheet" href="css/style.css">

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <link rel="shortcut icon" href="img/Art.ico">

    <script src="js/jquery.js"></script>
    <script src="js/jquery-ui.js"></script>
    <script src="js/bootstrap.js"></script>
    <script src="js/main.js"></script>

  </head>

  <body <?php if(isset($scrolSpy)){ echo ' data-spy="scroll" data-target=".subnav" data-offset="50"';} ?>>

    <div class="navbar navbar-inverse navbar-fixed-top">

      <div class="navbar-inner">

        <div class="container">

          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>

          <a class="brand" href="index.php"><?php echo WEBSITE_TITLE; ?></a>


          <div class="nav-collapse">

            <ul class="nav">

              <li<?php if(Functions::isPage('index')) echo ' class="active"'; ?>><a href="index.php"><i class="icon-home"></i></a></li>


              <?php if ($Auth->isAdmin()){ ?>

              <li class="dropdown<?php if(Functions::isPage('admin_liste_guests','admin_edit_guest','admin_import')) echo ' active'; ?>" id="admin">

                <a data-toggle="dropdown" class="dropdown-toggle" href="#">Inscriptions Spring <b class="caret"></b></a>

                <ul class="dropdown-menu">

                  <li><a href="admin_liste_guests.php"><i class="icon-book"></i> Liste des participants</a></li>
                  <li><a href="admin_edit_guest.php"><i class="icon-plus"></i> Ajouter un invité</a></li>

                </ul>

              </li>

              <?php } ?>


              <?php if ($Auth->isLogged() || Config::getDbConfig('authentification') == false){?>

              <li class="dropdown<?php if(Functions::isPage('admin_parametres_entrees','statistics_entrees','admin_liste_guests_arrived')) echo ' active'; ?>" id="admin">

                <a data-toggle="dropdown" class="dropdown-toggle" href="#">Jour du Spring <b class="caret"></b></a>

                <ul class="dropdown-menu">
                  
                  <?php if ($Auth->isAdmin()){ ?>
                  <li><a href="admin_parametres_entrees.php"><i class="icon-wrench"></i> Paramêtres jour-même</a></li>
                  <?php } ?>
                  <li class="divider"></li>

                  <li><a href="admin_liste_guests_arrived.php"><i class="icon-plus"></i> Entrées</a></li>

                </ul>

              </li>

              <?php } ?>

            </ul>


            <ul class="nav pull-right">

              <?php if ($Auth->isLogged() || Config::getDbConfig('authentification') == false){if($Auth->isLogged()){ ?>

              <li><a><em>Bonjour <?php echo $_SESSION['Auth']['prenom']?></em></a></li>  

              <?php } 

              if ($Auth->isAdmin()){ ?>

                <li class="divider-vertical"></li>
                <li class="dropdown<?php if(Functions::isPage('admin_liste_admins','admin_edit_admin','admin_parametres')) echo ' active'; ?>" id="admin">

                  <a data-toggle="dropdown" class="dropdown-toggle" href="#">Admin Site <b class="caret"></b></a>
                  <ul class="dropdown-menu">

                    <li><a href="admin_liste_admins.php"><i class="icon-user"></i> Gérer les utilisateurs</a></li>
                    <li><a href="admin_edit_admin.php"><i class="icon-plus"></i> Nouvel utilisateur</a></li>
                    <li class="divider"></li>
                    <li><a href="admin_parametres.php"><i class="icon-wrench"></i> Paramètres du Site</a></li>

                  </ul>
                </li>

              <?php } ?>

              <?php if($Auth->isLogged()){ ?>
              <li class="divider-vertical"></li>
              <li><a href="logout.php">Se Déconnecter</a></li>
              <?php }}
              if(!$Auth->isLogged()){ ?>
              <li class="divider-vertical"></li>
              <li><a href="connection.php">Se Connecter</a></li>
              <?php } ?>

            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>


    <div class="container">
      <?php echo Functions::flash(); ?>