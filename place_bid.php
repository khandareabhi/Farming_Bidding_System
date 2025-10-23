<?php
session_start();
$conn = new mysqli("localhost", "root", "", "project1");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ✅ Check if the user is logged in
if (!isset($_SESSION['a']) || !isset($_SESSION['a']['email'])) {
    echo "<script>
        alert('You need to log in first to place a bid!');
        window.location.href='a.php#line-299';
    </script>";
    exit();
}

$user = $_SESSION['a'];
$bidder_email = $user['email']; // Get logged-in bidder email

// ✅ Check if the user has the role "Bidder"
$sql = "SELECT role FROM registration WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $bidder_email);
$stmt->execute();
$stmt->bind_result($role);
$stmt->fetch();
$stmt->close();

if ($role !== 'Bidder') {
    echo "<script>
        alert('Only users with a bidder role can place a bid!');
        window.location.href='a.php';
    </script>";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = $_POST['product_id'];
    $bid_amount = $_POST['bid_amount'];

    // ✅ Fetch the farmer_email, product_name, and min_bidding from the products table
    $sql = "SELECT farmer_email, p_name, min_bidding FROM products WHERE p_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $stmt->bind_result($farmer_email, $product_name, $min_bidding);
    $stmt->fetch();
    $stmt->close();

    // ✅ Get the current highest bid
    $sql = "SELECT MAX(bid_amount) FROM bill WHERE product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $stmt->bind_result($current_highest_bid);
    $stmt->fetch();
    $stmt->close();

    // If no bid exists, use the minimum bidding amount
    if ($current_highest_bid === null) {
        $current_highest_bid = $min_bidding;
    }

    // ✅ Ensure the bid amount is higher than the current highest bid
    if ($bid_amount <= $current_highest_bid) {
        echo "<script>
            alert('Your bid must be higher than the current highest bid!');
            window.location.href='bidder_dashboard.php';
        </script>";
        exit();
    }

    // ✅ Insert bid into `bill` table with `farmer_email` and `product_name`
    $bid_time = date('Y-m-d H:i:s');
    $sql = "INSERT INTO bill (product_id, farmer_email, product_name, bidder_email, bid_amount, bid_time) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssds", $product_id, $farmer_email, $product_name, $bidder_email, $bid_amount, $bid_time);
    $stmt->execute();
    $stmt->close();

    // ✅ Remove all lower bids (including b_id) for this product
    $sql = "DELETE FROM bill WHERE product_id = ? AND bid_amount < ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("id", $product_id, $bid_amount);
    $stmt->execute();
    $stmt->close();

    echo "<script>
        alert('Bid placed successfully at $bid_time! You are the highest bidder.');
        window.location.href='bidder_dashboard.php';
    </script>";

    $conn->close();
}
?>
