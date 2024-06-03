<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: ../../index.php");
    exit;
}
?>
    <?php include_once "inc/head.php"; ?>  
    <?php include_once "inc/header.php"; ?>
    <div class="wrapper">
        <div class="container">
            <h2>Order Confirmation</h2>
            <p>Your order has been placed successfully. Thank you for shopping with us!</p>
            <a href="dashboard.php" class="btn btn-primary">Continue Shopping</a>
        </div>
    </div>
<?php include_once "inc/footer.php"; ?>  