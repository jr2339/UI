<?php

//helper functions


function set_message($msg){
    if(!empty($msg)){
        $_SESSION['message'] = $msg;
    }
    else{
        $msg = "";
    }
}


function display_message(){
    if(isset($_SESSION['message'])){


        echo $_SESSION['message'];
        unset($_SESSION['message']);
    }

}


function redirect($location){

    header("Location: $location");

}


function query($sql){
    //from config file
    global $connection;
    return mysqli_query($connection,$sql);

}


function confirm($result){
    global $connection;
    if(!$result){
        die("QUERY FAILED".mysqli_error($connection));
    }

}


function escape_string($string){
    global $connection;
    return mysqli_real_escape_string($connection,$string);
}




function fetch_array($result){

    return mysqli_fetch_array($result);
}


//===========================================FRONT END FUNCTIONS===================================//
//get products

function get_products(){

    $query = query("SELECT * FROM products");
    //check our query works
    confirm($query);

    while($row = fetch_array($query)){
        $product = <<<DELIMETER
        <div class="col-sm-4 col-lg-4 col-md-4">
            <div class="thumbnail">
               <a href="item.php?id={$row['product_id']}"><img src="{$row['product_image']}" alt=""></a>
                <div class="caption">
                    <h5 class="pull-right">&#36;{$row['product_price']}</h5>
                    <h5><a href="item.php?id={$row['product_id']}">{$row['product_title']}</a>
                    </h5>
                    <p>See more snippets like this online store item at <a target="_blank" href="http://www.bootsnipp.com">Bootsnipp - http://bootsnipp.com</a>.</p>
                    <a class="btn btn-primary" target="_blank" href="cart.php?add={$row['product_id']}">Add to cart</a>
                </div>
            </div>
        </div>

DELIMETER;
        echo $product;
    }
}



//get categories
function get_categories(){
    $query = query("SELECT * FROM categories");

    confirm($query);

    while($row = fetch_array($query)){
        $categories = <<<DELIMETER
        <a href='category.php?id={$row['cat_id']}' class='list-group-item'>{$row['cat_title']}</a>

DELIMETER;
    echo $categories;
    }
}





function get_product_in_cat_page(){

    $query = query("SELECT * FROM products WHERE product_category_id =" . escape_string($_GET['id']) . " ");
    //check our query works
    confirm($query);

    while($row = fetch_array($query)){
        $product = <<<DELIMETER
        <div class="col-md-3 col-sm-6 hero-feature">
            <div class="thumbnail">
                <img src="{$row['product_image']}" alt="">
                <div class="caption">
                    <h4>{$row['product_title']}</h4>
                    <p>{$row['short_desc']}</p>
                    <p>
                        <a href="#" class="btn btn-primary">Buy Now!</a> <a href="item.php?id={$row['product_id']}" class="btn btn-default">More Info</a>
                    </p>
                </div>
            </div>
         </div>

DELIMETER;
        echo $product;
    }
}



function get_product_in_shop_page(){

    $query = query("SELECT * FROM products");
    //check our query works
    confirm($query);

    while($row = fetch_array($query)){
        $product = <<<DELIMETER
        <div class="col-md-3 col-sm-6 hero-feature">
            <div class="thumbnail">
                <img src="{$row['product_image']}" alt="">
                <div class="caption">
                    <h4>{$row['product_title']}</h4>
                    <p>{$row['short_desc']}</p>
                    <p>
                       <a href="item.php?id={$row['product_id']}" class="btn btn-default">More Info</a>

                    </p>
                </div>
            </div>
         </div>

DELIMETER;
        echo $product;
    }
}


function login_user(){
    if(isset($_POST['submit'])){

       $username =  escape_string($_POST['username']);
       $password =  escape_string($_POST['password']);

       $query = query("SELECT * FROM users WHERE username ='{$username}' AND password = '{$password}'");
       confirm($query);

       if(mysqli_num_rows($query) == 0){

            set_message("Your password or Username are not correct");
            redirect("login.php");
       }
        else{
            set_message("Welcome to Admin {username}");
            redirect("admin");
        }
    }
}




function send_message(){
    if(isset($_POST['submit'])){

        $to         =   "jr2339@nau.edu";
        $name  =  $_POST['name'];
        $subject    =  $_POST['subject'];
        $email      =  $_POST['email'];
        $message    =  $_POST['message'];

        $headers ="From: {$name} {$email}";

        $result = mail($to,$subject,$message,$headers);

        if(!$result){
            set_message("Sorry, we couldn't send your message");
            redirect("contact.php");
        }else{
            set_message("Your message has been sent");
            redirect("contact.php");
        }


    }
}



//===========================================BACK END FUNCTIONS===================================//







?>