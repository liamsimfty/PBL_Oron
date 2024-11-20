<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .product-box {
            border: 1px solid #ccc;
            padding: 20px;
            text-align: center;
            display: inline-block;
            width: 200px;
            margin: 10px;
        }
        .product-box img {
            width: 150px;
            height: 200px;
        }
    </style>
</head>
<body>
    <?php
    include '../connection/connection.php';
    // Start session
    session_start();

    // Check if user is logged in
    $isLoggedIn = isset($_SESSION['username']);

    // Fetch product data from the database
    $sql = "SELECT name, current_price, discount FROM products";
    $result = $conn->query($sql);
    ?>

    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <!-- Navbar content -->
        </div>
    </nav>

    <input type="text" placeholder="Search.." style="display: block; margin: 0 auto; text-align: center;">

    <div class="container">
        <div class="row">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="col-md-3 product-box">';
                    echo '<img src="images/placeholder.jpg" alt="' . $row["name"] . '">';
                    echo '<h3>' . $row["name"] . '</h3>';
                    if ($row["discount"] > 0) {
                        $originalPrice = $row["current_price"];
                        $discountedPrice = $row["current_price"] - ($row["current_price"] * ($row["discount"] / 100));
                        echo '<p><strike>$' . number_format($originalPrice, 2) . '</strike> -' . $row["discount"] . '% = $' . number_format($discountedPrice, 2) . '</p>';
                    } else {
                        echo '<p>$' . number_format($row["current_price"], 2) . '</p>';
                    }
                    echo '<button>Add to Cart</button>';
                    echo '</div>';
                }
            } else {
                echo "No products found.";
            }
            $conn->close();
            ?>
        </div>
    </div>
</body>
</html>