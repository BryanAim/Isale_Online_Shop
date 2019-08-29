<?php require_once("../resources/config.php"); ?>

<!-- modularised header here -->
<?php include(TEMPLATE_FRONT . DS . "header.php"); ?>

    <!-- Page Content -->
    <div class="container">

      <header>
            <h1 class="text-center">User Login</h1>
            <h2 class="text-center">
            <?php 
            displayMessage();
            ?>
            </h2>
        <div class="col-sm-4 col-sm-offset-5">         
            <form class="" action="" method="post" enctype="multipart/form-data">

            <?php
            customerLogin();
            ?>
                <div class="form-group"><label for="">
                    Email<input type="email" name="email" placeholder="example@email.com" class="form-control" required></label>
                </div>
                 <div class="form-group"><label for="password">
                    Password<input type="password" name="password" placeholder="Your Password" class="form-control" required></label>
                </div>

                <div class="form-group">
                  <input type="submit" name="submit" class="btn btn-primary" >
                </div>
                <div class="form-group">
                  <p>New Here? <a href="../public/user-sign-up.php">Sign up</a></p>
                </div>
            </form>
        </div>  


    </header>


        </div>

    </div>
    <!-- /.container -->

    <div class="container">



        <!-- footer here -->
    <?php include(TEMPLATE_FRONT . DS . "footer.php"); ?>