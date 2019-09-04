<?php require_once("../resources/config.php"); ?>

<!-- modularised. header here -->
<?php include(TEMPLATE_FRONT . DS . "header.php"); ?>

    <!-- Page Content -->
    <div class="container">

        <div class="row">

<!-- side nav/  categories here -->
   <?php include(TEMPLATE_FRONT . DS . "side_nav.php"); ?>  
          <div class="col-md-9">

                <div class="row carousel-holder">

                    <div class="col-md-12">
<!-- sliders here -->
            <?php
            include(TEMPLATE_FRONT . DS . "slider.php");
            ?>

                    </div>

                </div>

                <div class="row">

                <?php
                getProducts();
                ?>
                    

                </div>

            </div>

        </div>

    </div>
    <!-- /.container -->
    
<!-- footer here -->
    <?php include(TEMPLATE_FRONT . DS . "footer.php"); ?>
