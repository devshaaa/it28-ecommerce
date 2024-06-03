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
    <?php include_once "inc/head.php"; ?>  

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
        <?php include_once "inc/header.php"; ?>  

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
                                    <form method="post" action="buy_now.php">
                                        <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">
                                        <button type="submit" class="btn btn-sm btn-success">Buy Now</button>
                                    </form>
                                    <form method="post" action="add_to_cart.php">
                                        <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">
                                        <button type="submit" class="btn btn-sm btn-primary">Add to Cart</button>
                                    </form>
                                </div>

                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

        </div>


    <?php endif; ?>

<?php include_once "inc/footer.php"; ?>  