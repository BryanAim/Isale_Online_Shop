<?php require_once("../resources/config.php"); ?>

<!-- modularised header here -->
<?php include(TEMPLATE_FRONT . DS . "header.php"); ?>
    <!-- Page Content -->
    <div class="container">

        <!-- Jumbotron Header -->
        <header> 
        <h1 class="text-center">Shop</h1>
        </header>

        <hr>

        <!-- Title -->
        <div class="row">
            <div class="col-lg-12">
                
            </div>
        </div>
        <!-- /.row -->

        <!-- Page Features -->
        <div class="row text-center">
<?php

getProductsInShop();
?>

        </div>
        <!-- /.row -->

        <hr>

<!-- footer here -->
    <?php include(TEMPLATE_FRONT . DS . "footer.php"); ?>
