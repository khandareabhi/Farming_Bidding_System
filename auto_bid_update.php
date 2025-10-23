<?php
$conn = new mysqli("localhost", "root", "", "project1");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get all products where bidding time has ended and is still "verified"
$sql = "SELECT * FROM products WHERE status = 'verified' AND bid_end_time <= NOW()";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    $product_id = $row['p_id'];

    // Get the highest bid for this product
    $bid_sql = "SELECT * FROM bill WHERE product_id = ? ORDER BY bid_amount DESC LIMIT 1";
    $bid_stmt = $conn->prepare($bid_sql);
    $bid_stmt->bind_param("i", $product_id);
    $bid_stmt->execute();
    $bid_result = $bid_stmt->get_result();
    
    if ($bid_result->num_rows > 0) {
        $bid = $bid_result->fetch_assoc();
        $bidder_email = $bid['bidder_email'];
        $bid_amount = $bid['bid_amount'];

        // Insert a new bill for the highest bidder
        $bill_sql = "INSERT INTO bill (product_id, farmer_email, bidder_email, bid_amount, bill_time) VALUES (?, ?, ?, ?, NOW())";
        $bill_stmt = $conn->prepare($bill_sql);
        $bill_stmt->bind_param("issd", $product_id, $row['farmer_email'], $bidder_email, $bid_amount);
        $bill_stmt->execute();
        $bill_stmt->close();

        // Update product status to "sold"
        $update_sql = "UPDATE products SET status = 'sold' WHERE p_id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("i", $product_id);
        $update_stmt->execute();
        $update_stmt->close();
    }
}

$conn->close();
?>
