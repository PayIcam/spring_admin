    <hr>

    <footer class="footer">
        <div class="clearfix">
            <!-- <a class="marker span" href="#PriceTable" role="button" data-toggle="modal">Tableau des prix</a> -->
            <!-- <a class="marker span" href="#Tuto" role="button" data-toggle="modal">Tutoriel des ventes</a> -->
            <a class="pull-right" href="#">Back to top</a>
        </div>
    </footer>
    <?php include 'includes/modalPriceTable.php'; ?>
    <?php include 'includes/modalTuto.php'; ?>
    
</div> <!-- /container -->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    
    <?php if (!empty($required_script)): ?>
        <?php foreach ($required_script as $v): ?>
            <script src="js/<?php echo $v; ?>"></script>
        <?php endforeach ?>
    <?php endif ?>
  </body>
</html>