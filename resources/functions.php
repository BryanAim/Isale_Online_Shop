<?php 

// function for picture upload directory
$upload_directory = "uploads";


//helper functions for fast building

function last_id(){

global $connection;

return mysqli_insert_id($connection);

}
// redirecting function
function redirect($location) {
    header("Location: $location");
};
// for querying from the database
function query($sql) {

    global $connection;
    return mysqli_query($connection, $sql);
};
// if connection doesnt exist
function confirm($result) {
    
    global $connection;
    if(!$result){
        die("QUERY FAILED ". mysqli_error($connection));
    };    
};

// to prevent sql injections
function escape_string($string){
    global $connection;
    return mysqli_real_escape_string($connection, $string);
};

function fetch_array($result) {
    return mysqli_fetch_array($result);
};

//function for setting the messages 
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
        //unset so that when you refresh its not there
        unset($_SESSION['message']);
    }
}





/*************************FRONT END FUNCTIONS************/
// function for getting products and display them on homepage
function getProducts() {
    $query = query("SELECT * FROM products");
    confirm($query);

    while($row = fetch_array($query)) {

        $product_image= displayImage($row['product_image']);
        // HERODOC
    $product = <<<DELIMITER
    <div class="col-sm-4 col-lg-4 col-md-4">
    <div class="thumbnail">

        <a href= "item.php?id={$row['product_id']}"><img style="height:150px"  src="../resources/{$product_image}" alt=""></a> 
        <div class="caption">
            <h4 class="pull-right"> Ksh {$row['product_price']}</h4>
            <h4><a href="item.php?id={$row['product_id']}">{$row['product_name']}</a>
            </h4>
            <p>{$row['short_description']}</p>
            <a class="btn btn-primary" target="_blank" href="../resources/cart.php?add={$row['product_id']}">Add to Cart</a>
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
       confirm($query);
        while ($row = fetch_array($query)) {
        
            $categories_list = <<<DELIMITER

    <a href='category.php?id={$row['category_id']}' class='list-group-item'>{$row['category_title']}</a>

DELIMITER;
          
    echo $categories_list;
        }
}

function getProductsInCategories() {
    $query = query("SELECT * FROM products WHERE product_category_id = " . escape_string($_GET['id']). " ");
    confirm($query);

    while($row = fetch_array($query)) {

        $product_image = displayImage($row['product_image']);
        // HERODOC
    $product = <<<DELIMITER
    <div class="col-md-3 col-sm-6 hero-feature">
        <div class="thumbnail">
        
            <img src="../resources/{$product_image}">
            <div class="caption">
                <h3>{$row['product_name']}</h3>
                
                <p>
                    <a href="../resources/cart.php?add={$row['product_id']}" class="btn btn-primary">Buy Now!</a> <a href="item.php?id={$row['product_id']}" class="btn btn-default more-info">More Info</a>
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
            <img src="../resources/uploads/{$row['product_image']}" alt="">
            <div class="caption">
                <h3>{$row['product_name']}</h3>
                
                <p>
                    <a href="../resources/cart.php?add={$row['product_id']}" class="btn btn-primary">Buy Now!</a> <a href="item.php?id={$row['product_id']}" class="btn btn-default more-info">More Info</a>
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
        confirm($query);

        if (mysqli_num_rows($query) ==0) {

            setMessage("Wrong Username or Password");
            redirect("login.php");

        } else {
            setMessage("Welcome to Admin Dashboard {$username} !");
            $_SESSION['username'] = $username;
            redirect("admin");
        }
    }

}

function customerLogin() {

    if(isset($_POST['submit'])){
        $email = escape_string($_POST['email']);
        $password = escape_string($_POST['password']);

        $query = query("SELECT * FROM customers WHERE customer_email ='{$email}' AND customer_password='{$password}' ");
        confirm($query);

        if (mysqli_num_rows($query) ==0) {

            setMessage("Wrong Email or Password");
            redirect("user-login.php");

        } else {
            $_SESSION['email'] = $email;
            redirect("../public/home.php");
        }
    }

}

function customerSignUp() {

    if(isset($_POST['submit'])){
        $fullName = escape_string($_POST['fullName']);
        $mobileNo = escape_string($_POST['mobile']);

        $email = escape_string($_POST['email']);
        $password = escape_string($_POST['password']);

        $query = query("INSERT INTO customers(customer_name, customer_number,customer_email, customer_password) VALUES('{$fullName}', '{$mobileNo}', '{$email}', '{$password}')");
// $last_id = last_id();
confirm($query);
        confirm($query);

       redirect("../public/home.php");
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
// mail function isnt so reliable though
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
            $length = strlen($name);

            $id = substr($name, 8, $length);

        $query = query("SELECT * FROM products WHERE product_id=" .escape_string($id) ." ");

        while ($row= fetch_array($query)) {

            // calculating total for each item in cart
            $subtotal = $row['product_price']*$value; 

            // item quantities

            $item_quantity+=$value;

           // $product_image= displayImage($row['product_image']);
            
        $product = <<<DELIMITER

        <tr>
            <td>{$row['product_name']} <br>
            <img width=100 src="../resources/uploads/{$row['product_image']}">
            
            </td>
            <td>Ksh {$row['product_price']}</td>
            <td>{$value}</td>
            <td>Ksh {$subtotal}</td>
            <td>
            <a class="btn btn-warning" href="../resources/cart.php?remove={$row['product_id']}"><span class="glyphicon glyphicon-minus"></span></a>
            
            <a class="btn btn-success" href="../resources/cart.php?add={$row['product_id']}"><span class="glyphicon glyphicon-plus"></span></a>
            <a class="btn btn-danger" href="../resources/cart.php?delete={$row['product_id']}"><span class="glyphicon glyphicon-remove"></span></a>
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

/****************** BACKEND FUNCTIONS ***********/



// function for displaying images, whose directory is defined at top

function displayImage($picture) {
    global $upload_directory;
    return $upload_directory . DS . $picture;
}

/*********** Admin Products***** */
function adminGetProducts() {
    $query = query("SELECT * FROM products");
    confirm($query);

    while($row = fetch_array($query)) {

        $category = showProductCategoryTitle($row['product_category_id']);

       $product_image= displayImage($row['product_image']);

        // HERODOC
    $product = <<<DELIMITER
    <tr>
        <td>{$row['product_id']}</td>
        <td> 
        <a href="index.php?edit_product&id={$row['product_id']}"><p>{$row['product_name']}</p></a>

            <div>

          <a href="index.php?edit_product&id={$row['product_id']}">  <img width='100' src="../../resources/{$product_image}" alt=""></a>

            </div>
        </td>
        <td>{$category}</td>
        <td>{$row['product_price']}</td>
        <td>{$row['product_quantity']}</td>
        <td> <a class="btn btn-danger" href="../../resources/templates/back/delete_product.php?id={$row['product_id']}"> <span class="glyphicon glyphicon-remove"></span> </a></td>
    </tr>

DELIMITER;
    echo $product;
    };

}

// Function that shows product category title. to relate category and products table
function showProductCategoryTitle($product_category_id) {
$category_query = query("SELECT * FROM categories WHERE category_id= '{$product_category_id}'");
confirm($category_query);

while ($category_row = fetch_array($category_query)) {
    return $category_row['category_title'];
}

}

/********** ADDING PRODUCT IN ADMIN *****/

function addProduct() {

if(isset($_POST['publish'])) {


$product_name           = escape_string($_POST['product_name']);
$product_category_id    = escape_string($_POST['product_category_id']);
$short_description      = escape_string($_POST['short_description']);
$product_description    = escape_string($_POST['product_description']);
$product_price          = escape_string($_POST['product_price']);
$product_quantity       = escape_string($_POST['product_quantity']);
$product_image          = escape_string($_FILES['file']['name']);
$image_temp_location    = escape_string($_FILES['file']['tmp_name']);

move_uploaded_file($image_temp_location  , UPLOAD_DIRECTORY . DS . $product_image);


$query = query("INSERT INTO products(product_name, product_category_id,short_description, product_description, product_price, product_quantity, product_image) VALUES('{$product_name}', '{$product_category_id}', '{$short_description}', '{$product_description}', '{$product_price}', '{$product_quantity}', '{$product_image}')");
$last_id = last_id();
confirm($query);

setMessage("New Product with id {$last_id} was Added");
redirect("index.php?products");
    
}


}

// Editing Products from admin page

function editProduct() {

if(isset($_POST['update'])) {


$product_name           = escape_string($_POST['product_name']);
$product_category_id    = escape_string($_POST['product_category_id']);
$short_description      = escape_string($_POST['short_description']);
$product_description    = escape_string($_POST['product_description']);
$product_price          = escape_string($_POST['product_price']);
$product_quantity       = escape_string($_POST['product_quantity']);
$product_image          = escape_string($_FILES['file']['name']);
$image_temp_location    = escape_string($_FILES['file']['tmp_name']);

// check if should update image
if (empty($product_image)) {
    $get_pic = query("SELECT product_image FROM products WHERE product_id=" . escape_string($_GET['id']). " ");
    confirm($get_pic);

    while ($pic = fetch_array($get_pic)) {
        $product_image= $pic['product_image'];
    }
}

move_uploaded_file($image_temp_location  , UPLOAD_DIRECTORY . DS . $product_image);


$query ="UPDATE products SET ";
$query .="product_name         = '{$product_name}'        , ";
$query .="product_category_id  = '{$produc_category_id}'  , ";
$query .="product_price        = '{$product_price}'       , ";
$query .="product_description  = '{$product_description}' , ";
$query .="short_description    = '{$short_description}'   , ";
$query .="product_quantity     = '{$product_quantity}'    , ";
$query .="product_image        = '{$product_image}'         ";
$query .="WHERE product_id=" . escape_string($_GET['id']);

$send_update_query= query($query);
confirm($send_update_query);

setMessage("Product has been updated!");
redirect("index.php?products");
    
}


}

// showing  categories function in admins' add product page
function showCategoriesAddProductPage(){
     $query =query("SELECT * FROM categories");
       confirm($query);

        while ($row = mysqli_fetch_array($query)) {
        
            $categories_option = <<<DELIMITER

    <option value="{$row['category_id']}">{$row['category_title']}</option>

DELIMITER;
          
    echo $categories_option;
        };
}

/************CATEGORIES IN ADMIN********* */

function showCategoriesInAdmin() {
    $category_query= query("SELECT * FROM categories");
    confirm($category_query);

    while ($row= fetch_array($category_query)) {
        $cat_id= $row['category_id'];
        $cat_name= $row['category_title'];

        $category = <<<DELIMITER

<tr>
    <td>{$cat_id}</td>
    <td>{$cat_name}</td>
   <td><a class="btn btn-danger" href="../../resources/templates/back/delete_category.php?id={$row['category_id']}"><span class="glyphicon glyphicon-remove"></span></a></td>

</tr>


DELIMITER;
echo $category;
    }

    
}

function addCategory() {

    if (isset($_POST['add_category'])) {
        $cat_name= escape_string($_POST['category_title']);

        if(empty($cat_name) || $cat_name == " ") {

echo "<p class='bg-danger'>THIS CANNOT BE EMPTY</p>";


} else {

        $insert_category = query("INSERT INTO categories(category_title)VALUES('{$cat_name}') ");
        confirm($insert_category);
        setMessage("New Category Created");

        }        
    }
}

/**********Admin Users ***********/
function displayUsers() {
    $users_query= query("SELECT * FROM users");
    confirm($users_query);

    while ($row= fetch_array($users_query)) {
        $user_id= $row['user_id'];
        $username= $row['username'];
        $email= $row['email'];
        $password= $row['password'];

        $user = <<<DELIMITER

<tr>
    <td>{$user_id}</td>
    <td>{$username}</td>
    <td>{$email}</td>
   <td><a class="btn btn-danger" href="../../resources/templates/back/delete_user.php?id={$row['user_id']}"><span class="glyphicon glyphicon-remove"></span></a></td>

</tr>


DELIMITER;
echo $user;
    }  
}


function addUser() {


if(isset($_POST['add_user'])) {


$username   = escape_string($_POST['username']);
$email      = escape_string($_POST['email']);
$password   = escape_string($_POST['password']);
// $user_photo = escape_string($_FILES['file']['name']);
// $photo_temp = escape_string($_FILES['file']['tmp_name']);


// move_uploaded_file($photo_temp, UPLOAD_DIRECTORY . DS . $user_photo);


$query = query("INSERT INTO users(username,email,password) VALUES('{$username}','{$email}','{$password}')");


setMessage("New User Created");

redirect("index.php?users");

}

}

/******************Slides Functions *******************/

function addSlides() {


if(isset($_POST['add_slide'])) {


$slide_name        = escape_string($_POST['slide_name']);
$slide_image        = escape_string($_FILES['file']['name']);
$slide_image_loc    = escape_string($_FILES['file']['tmp_name']);


if(empty($slide_name) || empty($slide_image)) {

echo "<p class='bg-danger'>This field cannot be empty</p>";


} else {



move_uploaded_file($slide_image_loc, UPLOAD_DIRECTORY . DS . $slide_image);

$query = query("INSERT INTO slides(slide_name, slide_image) VALUES('{$slide_name}', '{$slide_image}')");
confirm($query);
setMessage("Slide Added");
redirect("index.php?slides");
                }
        }
}



function getCurrentSlideInAdmin(){

$query = query("SELECT * FROM slides ORDER BY slide_id DESC LIMIT 1");
confirm($query);

while($row = fetch_array($query)) {

$slide_image = displayImage($row['slide_image']);

$slide_active_admin = <<<DELIMETER



    <img class="img-responsive" src="../../resources/{$slide_image}" alt="">



DELIMETER;

echo $slide_active_admin;
    }
}


function getActiveSlide() {

$query = query("SELECT * FROM slides ORDER BY slide_id DESC LIMIT 1");
confirm($query);



while($row = fetch_array($query)) {

$slide_image = displayImage($row['slide_image']);

$slide_active = <<<DELIMETER


 <div class="item active">
    <img class="slide-image" src="../resources/{$slide_image}" alt="">
</div>


DELIMETER;

echo $slide_active;
    }

}



function getSlides() {

$query = query("SELECT * FROM slides");
confirm($query);

while($row = fetch_array($query)) {

$slide_image = displayImage($row['slide_image']);

$slides = <<<DELIMETER


 <div class="item">
    <img class="slide-image" src="../resources/{$slide_image}" alt="">
</div>


DELIMETER;

echo $slides;

}

}


function getSlideThumbnails(){


$query = query("SELECT * FROM slides ORDER BY slide_id ASC ");
confirm($query);

while($row = fetch_array($query)) {

$slide_image = displayImage($row['slide_image']);

$slide_thumb_admin = <<<DELIMETER


<div class="col-xs-6 col-md-3 image_container">
    
    <a href="index.php?delete_slide_id={$row['slide_id']}">
        
         <img  class="img-responsive slide_image" src="../../resources/{$slide_image}" alt="">


    </a>

    <div class="caption">

    <p>{$row['slide_name']}</p>

    </div>
</div>


DELIMETER;

echo $slide_thumb_admin;
    }

}




?>

