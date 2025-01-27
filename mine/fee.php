<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
}

if(isset($_POST['pay'])){
   
   // Clean and sanitize payment form inputs
   $ncard = filter_var($_POST['ncard'], FILTER_SANITIZE_STRING);
   $nnumber = filter_var($_POST['nnumber'], FILTER_SANITIZE_STRING);
   $month = filter_var($_POST['month'], FILTER_SANITIZE_STRING);
   $year = filter_var($_POST['year'], FILTER_SANITIZE_STRING);
   $cvv = filter_var($_POST['cvv'], FILTER_SANITIZE_NUMBER_INT);

   // Initialize cart total calculation
   $cart_total = 0;
   $cart_products = [];

   $cart_query = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
   $cart_query->execute([$user_id]);
   if($cart_query->rowCount() > 0){
      while($cart_item = $cart_query->fetch(PDO::FETCH_ASSOC)){
         $cart_products[] = $cart_item['name'].' ( '.$cart_item['quantity'].' )';
         $sub_total = ($cart_item['price'] * $cart_item['quantity']);
         $cart_total += $sub_total;
      };
   };

   $total_products = implode(', ', $cart_products);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Checkout</title>

   <!-- Font Awesome CDN link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- Custom CSS file link -->
   <link rel="stylesheet" href="css/style.css">

   <style>
      * {
         margin: 0;
         padding: 0;
         box-sizing: border-box;
         font-family: 'Poppins', sans-serif;
      }

      body {
         background-color: #f7f7f7;
         
      }

      .form-container {
         background: #fff;
         border-radius: 8px;
         padding: 30px;
         box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
         max-width: 600px;
         margin: auto;
      }

      .form-container h3 {
         text-align: center;
         margin-bottom: 20px;
         font-size: 24px;
         color: #333;
      }

      .form-container .inputBox {
         margin-bottom: 20px;
      }

      .form-container .inputBox span {
         display: block;
         margin-bottom: 8px;
         font-weight: bold;
         color: #333;
      }

      .form-container .inputBox input {
         width: 100%;
         padding: 10px 15px;
         border: 1px solid #ccc;
         border-radius: 6px;
         font-size: 16px;
         color: #333;
         transition: all 0.3s;
      }

      .form-container .inputBox input:focus {
         border-color: #4CAF50;
         outline: none;
      }

      .form-container .btn {
         background-color: #4CAF50;
         color: white;
         border: none;
         padding: 12px 30px;
         cursor: pointer;
         border-radius: 6px;
         width: 100%;
         font-size: 18px;
         transition: background-color 0.3s;
      }

      .form-container .btn:hover {
         background-color: #45a049;
      }

      .form-container .inputBox img {
         height: 50px;
         margin-top: 10px;
         display: block;
         margin-bottom: 10px;
      }

      .form-container .btn.disabled {
         background-color: #ccc;
         cursor: not-allowed;
      }

      .grand-total {
         text-align: center;
         font-size: 18px;
         margin-top: 20px;
         color: #333;
         font-weight: bold;
      }

   </style>
</head>
<body>

<?php include 'header.php'; ?>



  

<section class="checkout-orders">

   <div class="form-container">

      <h3>Payment</h3>

      <form action="success.html" method="POST">
         <div class="inputBox">
            <span>Cards Accepted:</span>
            <marquee><img src="imgcards.png" alt="Credit Cards"></marquee>
         </div>

         <div class="inputBox">
            <span>Name On Card:</span>
            <input type="text" name="ncard" placeholder="Mr. Jacob Aiden" class="box" required>
         </div>

         <div class="inputBox">
            <span>Credit Card Number:</span>
            <input type="number" name="nnumber" placeholder="1111 2222 3333 4444" class="box" required>
         </div>

         <div class="inputBox">
            <span>Exp. Month:</span>
            <input type="text" name="month" placeholder="August" class="box" required>
         </div>

         <div class="inputBox">
            <span>Exp. Year:</span>
            <input type="number" name="year" placeholder="2025" class="box" required>
         </div>

         <div class="inputBox">
            <span>CVV:</span>
            <input type="number" name="cvv" placeholder="123" class="box" required>
         </div>

         <input type="submit" name="submit" class="btn" value="SUBMIT">
      </form>

   </div>

</section>

<?php include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>
