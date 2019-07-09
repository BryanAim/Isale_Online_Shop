<!-- helper functions for fast building -->
<?php 
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
        <a class="btn btn-primary" target="_blank" href="http://maxoffsky.com/code-blog/laravel-shop-tutorial-1-building-a-review-system/">Add to Cart</a>
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






?>

