<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
};

if(isset($_POST['add_to_wishlist'])){

   $pid = $_POST['pid'];
   $pid = filter_var($pid, FILTER_SANITIZE_STRING);
   $p_name = $_POST['p_name'];
   $p_name = filter_var($p_name, FILTER_SANITIZE_STRING);
   $p_price = $_POST['p_price'];
   $p_price = filter_var($p_price, FILTER_SANITIZE_STRING);
   $p_image = $_POST['p_image'];
   $p_image = filter_var($p_image, FILTER_SANITIZE_STRING);

   $check_wishlist_numbers = $conn->prepare("SELECT * FROM `wishlist` WHERE name = ? AND user_id = ?");
   $check_wishlist_numbers->execute([$p_name, $user_id]);

   $check_cart_numbers = $conn->prepare("SELECT * FROM `cart` WHERE name = ? AND user_id = ?");
   $check_cart_numbers->execute([$p_name, $user_id]);

   if($check_wishlist_numbers->rowCount() > 0){
      $message[] = 'already added to wishlist!';
   }elseif($check_cart_numbers->rowCount() > 0){
      $message[] = 'already added to cart!';
   }else{
      $insert_wishlist = $conn->prepare("INSERT INTO `wishlist`(user_id, pid, name, price, image) VALUES(?,?,?,?,?)");
      $insert_wishlist->execute([$user_id, $pid, $p_name, $p_price, $p_image]);
      $message[] = 'added to wishlist!';
   }

}

if(isset($_POST['add_to_cart'])){

   $pid = $_POST['pid'];
   $pid = filter_var($pid, FILTER_SANITIZE_STRING);
   $p_name = $_POST['p_name'];
   $p_name = filter_var($p_name, FILTER_SANITIZE_STRING);
   $p_price = $_POST['p_price'];
   $p_price = filter_var($p_price, FILTER_SANITIZE_STRING);
   $p_image = $_POST['p_image'];
   $p_image = filter_var($p_image, FILTER_SANITIZE_STRING);
   $p_qty = $_POST['p_qty'];
   $p_qty = filter_var($p_qty, FILTER_SANITIZE_STRING);

   $check_cart_numbers = $conn->prepare("SELECT * FROM `cart` WHERE name = ? AND user_id = ?");
   $check_cart_numbers->execute([$p_name, $user_id]);

   if($check_cart_numbers->rowCount() > 0){
      $message[] = 'already added to cart!';
   }else{

      $check_wishlist_numbers = $conn->prepare("SELECT * FROM `wishlist` WHERE name = ? AND user_id = ?");
      $check_wishlist_numbers->execute([$p_name, $user_id]);

      if($check_wishlist_numbers->rowCount() > 0){
         $delete_wishlist = $conn->prepare("DELETE FROM `wishlist` WHERE name = ? AND user_id = ?");
         $delete_wishlist->execute([$p_name, $user_id]);
      }

      $insert_cart = $conn->prepare("INSERT INTO `cart`(user_id, pid, name, price, quantity, image) VALUES(?,?,?,?,?,?)");
      $insert_cart->execute([$user_id, $pid, $p_name, $p_price, $p_qty, $p_image]);
      $message[] = 'added to cart!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>home page</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
 
<style>


.ban{
   width: 100vw;
   
   overflow:hidden;
   position: relative;
   /*padding-top:56.25%;*/
}
.ban video{
   position:absolute;
   left: 0;
   Background-size:center;
   top: 0;
   bottom:0;
   z-index: -999; 
   object-fit: cover;
  
}


.home {
	padding: 0;
	margin: 0;
}
   



</style> 

</head>
<body>
  
<?php include 'header.php'; ?>




 <!--video width="320" height="240" autoplay>
  <source src="movie.mp4" type="video/mp4">
  <source src="movie.ogg" type="video/ogg">
Your browser does not support the video tag.
</video --> 
<section class="home">
<div class ="ban">
<video autoplay loop muted plays-inline>
<source src="plant14.mp4" type="video/mp4">
</video>
</div>
</section>
<!--img src="C:\xampp\htdocs\grocery store\images\two.png">
<div class="home-bg">

   <section class="home">

      <div class="content">
	  
	  
 

         
         <h3>Sri Lankan Largest Aquatic Plant Store</h3>
         <!--p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Iusto natus culpa officia quasi, accusantium explicabo?</p-->
         <!--a href="about.php" class="btn">about us</a-->
		 

<section class="home-category">

   <h1 class="title">shop by category</h1>

   <div class="box-container">

      <div class="box">
         <img src="images/image1.jpg" alt="">
			<h2>Water Plants</h2>
        		
					<p>Freshwater plants offer vibrant colors, diverse textures, and natural filtration, helping to maintain water quality while providing essential hiding spots and habitats for your fish.</p>
						<a href="category.php?category=water" class="btn">Water Plants</a>
				
      </div>
	  
	  

      <!--div class="box">
         <img src="images/image2.webp" alt="">
         <h1>Carpet Plants</h1>
         <p>Transform your aquarium floor into a lush, green carpet with our selection of carpet plants. These aquatic beauties spread rapidly across the substrate, creating a dense, vibrant mat that enhances the visual appeal of your tank while providing a natural, comfortable habitat for your fish. </p>
         <a href="category.php?category=carpetplants" class="btn">Carpet Plants</a>
      </div-->
	  <div class="box">
         <img src="images/image2.webp" alt="">
         <h2><b>Carpet Plants</b></h2>
         <p>Transform your aquarium floor into a lush, green carpet with our selection of carpet plants. These aquatics spread rapidly across the substrate,  
		  comfortable habitat for fish. </p>
         <a href="category.php?category=carpet" class="btn">Carpet Plants</a>
      </div>

      <!--div class="box">
         <img src="images/image4.webp" alt="">
         <h1>Waterweeds</h1>
         <p>your aquarium with our captivating waterweeds, perfect for adding dynamic greenery to your aquatic environment.
		 you're designing a lush, densely planted tank or seeking to add a touch of natural beauty, our waterweeds offer both aesthetic appeal and functional benefits for a thriving aquatic ecosystem.</p>
         <a href="category.php?category=waterweeds" class="btn">Waterweeds</a>
      </div-->
	   <div class="box">
         <img src="images/image4.webp" alt="">
         <h2> Waterweeds</h2>
         <p> Our captivating waterweeds, perfect for adding dynamic greenery to your tank.These offer both aesthetic appeal and functional benefits for a thriving aquatic ecosystem.</p>
         <a href="category.php?category=weeds" class="btn"> Waterweeds</a>
      </div>

      <div class="box">
         <img src="images/furti2.jpg" alt="">
         <h2>Fertilizer & Aqua Soil</h2>
         <p>Fertilising will result in optimal growth and beautiful plant colours inside aquarium.The main nutrient is CO2, which is also the main inhibitor of growth in the plant aquarium.</p>
         <a href="category.php?category=furtilizer" class="btn">Fertilizer & Soil</a>
      </div>
	  
	 
   </div>

</section>

<section class="products">

   <h1 class="title">latest products</h1>

   <div class="box-container">

   <?php
      $select_products = $conn->prepare("SELECT * FROM `products` LIMIT 6");
      $select_products->execute();
      if($select_products->rowCount() > 0){
         while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){ 
   ?>
   <form action="" class="box" method="POST">
      <div class="price">Rs.<span><?= $fetch_products['price']; ?></span>/-</div>
      <a href="view_page.php?pid=<?= $fetch_products['id']; ?>" class="fas fa-eye"></a>
      <img src="uploaded_img/<?= $fetch_products['image']; ?>" alt="">
      <div class="name"><?= $fetch_products['name']; ?></div>
      <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
      <input type="hidden" name="p_name" value="<?= $fetch_products['name']; ?>">
      <input type="hidden" name="p_price" value="<?= $fetch_products['price']; ?>">
      <input type="hidden" name="p_image" value="<?= $fetch_products['image']; ?>">
      <input type="number" min="1" value="1" name="p_qty" class="qty">
      <input type="submit" value="add to wishlist" class="option-btn" name="add_to_wishlist">
      <input type="submit" value="add to cart" class="btn" name="add_to_cart">
   </form>
   <?php
      }
   }else{
      echo '<p class="empty">no products added yet!</p>';
   }
   ?>

   </div>

</section>



<?php include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>