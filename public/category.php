<?php require_once("../resources/config.php"); ?>

<!-- modularised header here -->
<?php include(TEMPLATE_FRONT . DS . "header.php"); ?>
    <!-- Page Content -->
    <div class="container">

        <!-- Jumbotron Header -->
        <header class="jumbotron hero-spacer text-center">
            <h1>Isale Homestore</h1>
            <p>Home of your favourite Home Items.</p>
            <!-- <p><a class="btn btn-primary btn-large">Call to action!</a>
            </p> -->
        </header>

        <hr>

        <!-- Title -->
        <div class="row">
            <div class="col-lg-12">
                <h3>Latest Products</h3>
            </div>
        </div>
        <!-- /.row -->

        <!-- Page Features -->
        <div class="row text-center">
<?php

getProductsInCategories();
?>

        </div>
        <!-- /.row -->


<!-- footer here -->
    <?php include(TEMPLATE_FRONT . DS . "footer.php"); ?>
