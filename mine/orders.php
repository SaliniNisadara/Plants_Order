<?php
@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
}

// Handle Delete Request via AJAX
if (isset($_POST['delete_order'])) {
   $delete_id = $_POST['delete_id'];
   $delete_order = $conn->prepare("DELETE FROM `orders` WHERE id = ?");
   $delete_order->execute([$delete_id]);

   // Respond with a success message
   echo json_encode(['status' => 'success']);
   exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Orders</title>

   <!-- Font Awesome CDN link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- Custom CSS file link -->
   <link rel="stylesheet" href="css/style.css">

   <style>
      .button-row {
         display: flex;
         gap: 10px;
         align-items: center;
      }

      .btn-delete {
         background-color: var(--red);
         color: white;
         border: none;
         padding: 10px 40px;
         cursor: pointer;
         margin-left: 200px;
         display: block;
         border-radius: .5rem;
         color: var(--white);
         font-size: 2rem;
         text-transform: capitalize;
         text-align: center;
      }

      .btn-checkout {
         background-color: var(--green);
         color: white;
         border: none;
         padding: 10px 40px;
         cursor: pointer;
         margin-left: 10px;
         display: block;
         border-radius: .5rem;
         color: var(--white);
         font-size: 2rem;
         text-transform: capitalize;
         text-align: center;
      }

      .btn-checkout:hover,
      .btn-delete:hover {
         background-color: var(--black);
      }
   </style>

</head>
<body>

<?php include 'header.php'; ?>

<section class="placed-orders">

   <h1 class="title">Placed Orders</h1>

   <div class="box-container" id="order-items">

   <?php
      $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE user_id = ?");
      $select_orders->execute([$user_id]);
      if($select_orders->rowCount() > 0){
         while($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)){
   ?>
   <div class="box" id="order-item-<?= $fetch_orders['id']; ?>">
      <p>Placed on: <span><?= $fetch_orders['placed_on']; ?></span></p>
      <p>Name: <span><?= $fetch_orders['name']; ?></span></p>
      <p>Number: <span><?= $fetch_orders['number']; ?></span></p>
      <p>Email: <span><?= $fetch_orders['email']; ?></span></p>
      <p>Address: <span><?= $fetch_orders['address']; ?></span></p>
      <p>Payment Method: <span><?= $fetch_orders['method']; ?></span></p>
      <p>Your Orders: <span><?= $fetch_orders['total_products']; ?></span></p>
      <p>Total Price: <span>Rs<?= $fetch_orders['total_price']; ?>/-</span></p>
      <p>Payment Status: <span style="color:<?= ($fetch_orders['payment_status'] == 'pending') ? 'red' : 'green'; ?>"><?= $fetch_orders['payment_status']; ?></span></p>

      <form method="post" class="button-row">
         <!-- Button to trigger the delete -->
         <button type="button" class="btn-delete" onclick="deleteOrder(<?= $fetch_orders['id']; ?>)">Delete</button>
         <!--button type="submit" name="checkout" class="btn-checkout">Checkout</button-->
		  <a href="fee.php" class="btn-checkout">Checkout</a>
      </form>

   </div>
   <?php
      }
   } else {
      echo '<p class="empty">No orders placed yet!</p>';
   }
   ?>

   </div>

</section>

<?php include 'footer.php'; ?>

<script src="js/script.js"></script>

<script>
   function deleteOrder(orderId) {
      if (confirm('Are you sure you want to delete this order?')) {
         var xhr = new XMLHttpRequest();
         xhr.open('POST', 'orders.php', true);
         xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
         xhr.onload = function() {
            if (xhr.status === 200) {
               var response = JSON.parse(xhr.responseText);
               if (response.status === 'success') {
                  // Remove the order item from the DOM
                  document.getElementById('order-item-' + orderId).remove();
                  alert('Order deleted successfully!');
               }
            }
         };
         xhr.send('delete_order=true&delete_id=' + orderId);
      }
   }
</script>

</body>
</html>
