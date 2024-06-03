<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: ../../index.php");
    exit;
}

require_once "../../db/config.php";

$user_id = $_SESSION['id'];

// Fetch user's orders
$sql = "SELECT * FROM orders WHERE user_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

    <?php include_once "inc/head.php"; ?>  
    <?php include_once "inc/header.php"; ?>
    <div class="wrapper">
        <div class="container">
            <h2>My Orders</h2>
            <?php if (count($orders) > 0): ?>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Order Date</th>
                            <th>Total Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                            <tr>
                                <td><?= $order['order_id'] ?></td>
                                <td><?= $order['order_date'] ?></td>
                                <td><?= $order['total_amount'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No orders found.</p>
            <?php endif; ?>
            <a href="dashboard.php" class="btn btn-primary">Back to Home</a>
        </div>
    </div>
<?php include_once "inc/footer.php"; ?>  