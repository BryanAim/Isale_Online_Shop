<?php 
//helper functions for fast building

//function for setting the message 
function setMessage($msg){
if (!empty($msg)) {
    $_SESSION['message'] = $msg;
} else {
    $msg = "";
}
}

// display message function
function displayMessage() {
    if (isset($_SESSION['message'])) {
        echo $_SESSION['message'];
        unset($_SESSION['message']);
    }
}


// redirecting function
function redirect($location) {
    header("Location: $location");
};

function query($sql) {

    global $connection;
    return mysqli_query($connection, $sql);
};
// if connection doesnt exist
function confirm($result) {
    
    global $connection;
    if($result){
        die("QUERY FAILED ". mysqli_error($connection));
    };
    
};
function escape_string($string){
    global $connection;
    return mysqli_real_escape_string($connection, $string);
};

function fetch_array($result) {
    return mysqli_fetch_array($result);
};


/*************************FRONT END FUNCTIONS************/
// function for getting products
function getProducts() {
    $query = query("SELECT * FROM products");
    // confirm($query);

    while($row = fetch_array($query)) {
        // HERODOC
    $product = <<<DELIMITER
    <div class="col-sm-4 col-lg-4 col-md-4">
    <div class="thumbnail">

        <a href= "item.php?id={$row['product_id']}"><img src="../resources/images/320x150.jpg" alt=""></a> 
        <div class="caption">
            <h4 class="pull-right"> Ksh {$row['product_price']}</h4>
            <h4><a href="item.php?id={$row['product_id']}">{$row['product_name']}</a>
            </h4>
            <p>{$row['short_description']}</p>
            <a class="btn btn-primary" target="_blank" href="cart.php?add={$row['product_id']}">Add to Cart</a>
        </div>
    </div>
    </div>

DELIMITER;
    echo $product;
    };
}

// get categories function
function getCategories(){
     $query =query("SELECT * FROM categories");
    //    confirm($query);
        while ($row = mysqli_fetch_array($query)) {
        
            $categories_list = <<<DELIMITER

    <a href='category.php?id={$row['category_id']}' class='list-group-item'>{$row['category_title']}</a>

DELIMITER;
          
    echo $categories_list;
        };
}

function getProductsInCategories() {
    $query = query("SELECT * FROM products WHERE product_category_id = " . escape_string($_GET['id']). " ");
    // confirm($query);

    while($row = fetch_array($query)) {
        // HERODOC
    $product = <<<DELIMITER
    <div class="col-md-3 col-sm-6 hero-feature">
        <div class="thumbnail">
            <img src="{$row['product_image']}" alt="">
            <div class="caption">
                <h3>{$row['product_name']}</h3>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
                <p>
                    <a href="#" class="btn btn-primary">Buy Now!</a> <a href="item.php?id={$row['product_id']}" class="btn btn-default">More Info</a>
                </p>
            </div>
        </div>
    </div>

DELIMITER;
    echo $product;
    };
}



function getProductsInShop() {
    $query = query("SELECT * FROM products");


    while($row = fetch_array($query)) {
        // HERODOC
    $product = <<<DELIMITER
    <div class="col-md-3 col-sm-6 hero-feature">
        <div class="thumbnail">
            <img src="{$row['product_image']}" alt="">
            <div class="caption">
                <h3>{$row['product_name']}</h3>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
                <p>
                    <a href="#" class="btn btn-primary">Buy Now!</a> <a href="item.php?id={$row['product_id']}" class="btn btn-default">More Info</a>
                </p>
            </div>
        </div>
    </div>

DELIMITER;
    echo $product;
    };
}


function userLogin() {

    if(isset($_POST['submit'])){
        $username = escape_string($_POST['username']);
        $password = escape_string($_POST['password']);

        $query = query("SELECT * FROM users WHERE username ='{$username}' AND password='{$password}' ");

        if (mysqli_num_rows($query) ==0) {

            setMessage("Wrong Username or Password");
            redirect("login.php");
        } else {
            redirect("admin");
        }
    }

}

// for sending message from contact form
function sendMessage(){
    if(isset($_POST['submit'])){
        $to       ="bryanaim00@gmail.com";
        $fromName =$_POST['name'];
        $subject  =$_POST['subject'];
        $email    =$_POST['email'];
        $message  =$_POST['message'];

        $headers ="From: {$fromName} {$email} ";

        $result = mail($to, $subject, $message, $headers);

        if (!$result) {
            setMessage("Sorry, we could not send your email");
            redirect("contact.php");
        } else {
            setMessage("Your Email has been sent!");
            redirect("contact.php");
        }
    }
}
    // display products in cart
function cart() {
    $total=0;
    $item_quantity=0;
    foreach ($_SESSION as $name => $value) {

        if ($value>0) {

        if (substr($name, 0, 8)=="product_") {
        //to get the length of the string 
            $length = strlen($name)-8;

            $id = substr($name, 8, $length);

        $query = query("SELECT * FROM products WHERE product_id=" .escape_string($id) ." ");

        while ($row= fetch_array($query)) {

            // calculating total for each item in cart
            $subtotal = $row['product_price']*$value; 

            // item quantities

            $item_quantity+=$value;
            
        $product = <<<DELIMITER

        <tr>
            <td>{$row['product_name']}</td>
            <td>Ksh {$row['product_price']}</td>
            <td>{$value}</td>
            <td>Ksh {$subtotal}</td>
            <td>
            <a class="btn btn-warning" href="cart.php?remove={$row['product_id']}"><span class="glyphicon glyphicon-minus"></span></a>
            
            <a class="btn btn-success" href="cart.php?add={$row['product_id']}"><span class="glyphicon glyphicon-plus"></span></a>
            <a class="btn btn-danger" href="cart.php?delete={$row['product_id']}"><span class="glyphicon glyphicon-remove"></span></a>
            </td> 
            
        </tr>   
DELIMITER;

    echo $product;
    }

    $_SESSION['item_total'] = $total += $subtotal;
        $_SESSION['item_quantity']= $item_quantity;
    
        }
    }


    }
 

}


?>

