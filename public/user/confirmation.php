<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: ../../index.php");
    exit;
}

$product_id = $_GET['product_id'] ?? null;
?>
    <?php include_once "inc/head.php"; ?>  
    <?php include_once "inc/header.php"; ?>
    <div class="wrapper">
        <div class="container">
            <h2>Order Confirmation</h2>
            <?php if ($product_id): ?>
                <p>Your order for product ID <?= htmlspecialchars($product_id) ?> has been placed successfully.</p>
                <a href="dashboard.php" class="btn btn-primary">Continue Shopping</a>
            <?php else: ?>
                <p>Invalid product ID.</p>
            <?php endif; ?>
        </div>
    </div>
<?php include_once "inc/footer.php"; ?>  