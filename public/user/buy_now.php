<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: ../../index.php");
    exit;
}

require_once "../../db/config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Calculate the total amount
    $total_amount = 0;
    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        $stmt = $pdo->prepare("SELECT product_retail_price FROM products WHERE product_id = ?");
        $stmt->execute([$product_id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($product) {
            $total_amount += $product['product_retail_price'] * $quantity;
        }
    }

    try {
        // Begin a transaction
        $pdo->beginTransaction();

        // Insert the order into the orders table
        $stmt = $pdo->prepare("INSERT INTO orders (user_id, total_amount) VALUES (?, ?)");
        $stmt->execute([$_SESSION['id'], $total_amount]);
        $order_id = $pdo->lastInsertId();

        // Insert each product into the order_items table
        foreach ($_SESSION['cart'] as $product_id => $quantity) {
            $stmt = $pdo->prepare("SELECT product_retail_price FROM products WHERE product_id = ?");
            $stmt->execute([$product_id]);
            $product = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($product) {
                $price = $product['product_retail_price'];
                $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
                $stmt->execute([$order_id, $product_id, $quantity, $price]);
            }
        }

        // Commit the transaction
        $pdo->commit();

        // Redirect to a confirmation page
        header("location: order_confirmation.php");
        exit;
    } catch (PDOException $e) {
        // Rollback the transaction if something failed
        $pdo->rollBack();
        error_log("PDO Error: " . $e->getMessage());
        echo "Something went wrong. Please try again later.";
    }
}

unset($pdo);
?>