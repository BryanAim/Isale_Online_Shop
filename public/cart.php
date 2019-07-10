<?php require_once("../resources/config.php"); ?>

<?php
// Add to cart

  if(isset($_GET['add'])) {


    $query = query("SELECT * FROM products WHERE product_id=" . escape_string($_GET['add']). " ");
    

    while($row = fetch_array($query)) {


      if($row['product_quantity'] != $_SESSION['product_' . $_GET['add']]) {

        $_SESSION['product_' . $_GET['add']]+=1;
        redirect("../public/checkout.php");


      } else {


        setMessage("We only have " . $row['product_quantity'] . " " . "{$row['product_name']}" . " available");
        redirect("../public/checkout.php");



      }


    }

  }

// remove from cart
  if(isset($_GET['remove'])) {

    $_SESSION['product_' . $_GET['remove']]--;

    if($_SESSION['product_' . $_GET['remove']] < 1) {

      unset($_SESSION['item_total']);
      unset($_SESSION['item_quantity']);
      redirect("../public/checkout.php");

    } else {

      redirect("../public/checkout.php");

     }


  }

// delete from cart
 if(isset($_GET['delete'])) { 

  $_SESSION['product_' . $_GET['delete']] = '0';
  unset($_SESSION['item_total']);
  unset($_SESSION['item_quantity']);

  redirect("../public/checkout.php");


 }
?>