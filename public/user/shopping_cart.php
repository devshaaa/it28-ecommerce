<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: ../../index.php");
    exit;
}

require_once "../../db/config.php";

$cart_products = [];

if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
    $placeholders = rtrim(str_repeat('?,', count($_SESSION['cart'])), ',');
    $sql = "SELECT * FROM products WHERE product_id IN ($placeholders)";
    $stmt = $pdo->prepare($sql);
    
    try {
        $stmt->execute(array_keys($_SESSION['cart']));
        $cart_products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("PDO Error: " . $e->getMessage());
    }
}

unset($pdo);
?>
    <?php include_once "inc/head.php"; ?>  
    <?php include_once "inc/header.php"; ?>
    <div class="wrapper">
        <div class="container">
            <h2>Your Shopping Cart</h2>
            <?php if (count($cart_products) > 0): ?>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Product Name</th>
                            <th>Product Description</th>
                            <th>Retail Price</th>
                            <th>Quantity</th>
                            <th>Subtotal</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
                    $total = 0;
                    foreach ($cart_products as $product): 
                        $quantity = $_SESSION['cart'][$product['product_id']];
                        $subtotal = $product['product_retail_price'] * $quantity;
                        $total += $subtotal;
                    ?>
                        <tr>
                            <td><?= htmlspecialchars($product['product_id']) ?></td>
                            <td><?= htmlspecialchars($product['product_name']) ?></td>
                            <td><?= htmlspecialchars($product['product_details']) ?></td>
                            <td><?= htmlspecialchars($product['product_retail_price']) ?></td>
                            <td>
                                <form method="post" action="update_cart.php" style="display: inline;">
                                    <input type="hidden" name="product_id" value="<?= htmlspecialchars($product['product_id']) ?>">
                                    <input type="number" name="quantity" value="<?= $quantity ?>" min="1" style="width: 60px;">
                                    <button type="submit" class="btn btn-sm btn-primary">Update</button>
                                </form>
                            </td>
                            <td><?= $subtotal ?></td>
                            <td>
                                <form method="post" action="remove_from_cart.php" style="display: inline;">
                                    <input type="hidden" name="product_id" value="<?= htmlspecialchars($product['product_id']) ?>">
                                    <button type="submit" class="btn btn-sm btn-danger">Remove</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="5">Total</td>
                            <td><?= $total ?></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
                <a href="checkout.php" class="btn btn-success">Proceed to Checkout</a>
            <?php else: ?>
                <p>Your cart is empty. <a href="index.php">Shop now</a></p>
            <?php endif; ?>
        </div>
    </div>
<?php include_once "inc/footer.php"; ?>  