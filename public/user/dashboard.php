<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: ../../index.php");
    exit;
}

// Include config file
require_once "../../db/config.php";

// Define the SQL query
$sql = "SELECT * FROM products";

$products = [];

try {
    // Prepare the statement
    $stmt = $pdo->prepare($sql);

    // Execute the statement
    $stmt->execute();
    
    // Check if any rows are returned
    if ($stmt->rowCount() > 0) {
        // Fetch all the results
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $products[] = $row;
        }
    } else {
        echo "No products found.";
    }
} catch (PDOException $e) {
    echo "ERROR: Could not execute $sql. " . $e->getMessage();
}


// Close the connection
unset($pdo);
 
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        body{ font: 14px sans-serif; text-align: center; }
        .wrapper{ 
            margin: 80px auto;
        }
        table tr td:last-child{
            width: 120px;
        }

        header {
            background: #333;
            padding: 20px 0;

            color : #fff;
        }

    </style>
</head>
<body>

    <?php if($_SESSION['role'] == 1): ?>

    <h1 class="my-5">Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Welcome to our site.</h1>
    <p>
        <a href="./reset.php" class="btn btn-warning">Reset Your Password</a>
        <a href="./logout.php" class="btn btn-danger ml-3">Sign Out of Your Account</a>
    </p>
    <div class="wrapper">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="mt-5 mb-3 clearfix">
                        <h2 class="pull-left">Product Details</h2>
                        <a href="../inventory/create.php" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Add New Product</a>
                    </div>
                     

                     <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Product Name</th>
                                <th>Product Description</th>
                                <th>Retail Price</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($products as $key => $product) :  ?>
                            <tr>
                                <td><?= $product['product_id'] ?></td>
                                <td><?= $product['product_name'] ?></td>
                                <td><?= $product['product_details'] ?></td>
                                <td><?= $product['product_retail_price'] ?></td>
                                <td>

                                    <a href="../inventory/read.php?product_id=<?= $product['product_id'] ?>" class="mr-3" title="View Record" data-toggle="tooltip"><span class="fa fa-eye"></span></a>
                                    <a href="../inventory/update.php?product_id=<?= $product['product_id'] ?>" class="mr-3" title="Update Record" data-toggle="tooltip"><span class="fa fa-pencil"></span></a>
                                    <a href="../inventory/delete.php?product_id=<?= $product['product_id'] ?>" title="Delete Record" data-toggle="tooltip"><span class="fa fa-trash"></span></a>

                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    
                </div>
            </div>        
        </div>
    </div>

    <?php else: ?>
        <header>
            <div class="container">
                <div class="d-flex justify-content-between">
                    <div class="">
                        Site Logo
                    </div>
                    <div class="">
                        <a class="text-white" href="./logout.php">Logout</a>
                    </div>
                </div>
            </div>
        </header>
        <div class="wrapper">

            <div class="container">
                <h2>Shop Now</h2>
                <p>Shop Now: Your One-Stop Destination for Instant Happiness!</p>
                <div class="row mt-4">
                    <?php foreach ($products as $key => $product) :  ?>
                        <div class="col-md-4">

                            <div class="card mx-2">
                                <div class="card-header"> 
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <?= $product['product_name'] ?> 
                                        </div>
                                        <div>
                                            ₱<?= $product['product_retail_price'] ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body"><?= $product['product_details'] ?></div>
                                <div class="card-footer d-flex justify-content-between">
                                    <a class="btn btn-sm btn-success" href="">Buy Now</a>
                                    <a class="btn btn-sm btn-primary" href="">Add to Cart</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

        </div>


    <?php endif; ?>

</body>
</html>