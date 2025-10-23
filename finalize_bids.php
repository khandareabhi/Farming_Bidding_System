<?php
$conn = new mysqli("localhost", "root", "", "project1");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ✅ Select products where bidding time has ended and status is still "verified"
$sql = "SELECT * FROM products WHERE status = 'verified' AND bid_end_time <= NOW()";
$result = $conn->query($sql);

if (!$result) {
    die("Error fetching expired products: " . $conn->error);
}

while ($row = $result->fetch_assoc()) {
    $product_id = $row['p_id'];

    // ✅ Get the highest bid for this product
    $bid_sql = "SELECT bidder_email, bid_amount FROM bill WHERE product_id = ? ORDER BY bid_amount DESC LIMIT 1";
    $bid_stmt = $conn->prepare($bid_sql);

    if (!$bid_stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $bid_stmt->bind_param("i", $product_id);
    $bid_stmt->execute();
    $bid_result = $bid_stmt->get_result();

    if ($bid_result->num_rows > 0) {
        $bid = $bid_result->fetch_assoc();
        $bidder_email = $bid['bidder_email'];
        $bid_amount = $bid['bid_amount'];

        // ✅ Insert a new bill only if not already recorded
        $check_bill_sql = "SELECT * FROM bill WHERE product_id = ?";
        $check_bill_stmt = $conn->prepare($check_bill_sql);
        $check_bill_stmt->bind_param("i", $product_id);
        $check_bill_stmt->execute();
        $check_bill_result = $check_bill_stmt->get_result();

        if ($check_bill_result->num_rows == 0) {
            $bill_sql = "INSERT INTO bill (product_id, farmer_email, bidder_email, bid_amount, bill_time) 
                         VALUES (?, ?, ?, ?, NOW())";
            $bill_stmt = $conn->prepare($bill_sql);

            if (!$bill_stmt) {
                die("Prepare failed: " . $conn->error);
            }

            $bill_stmt->bind_param("issd", $product_id, $row['farmer_email'], $bidder_email, $bid_amount);

            if (!$bill_stmt->execute()) {
                die("Bill insertion failed: " . $bill_stmt->error);
            }

            $bill_stmt->close();
        }

        // ✅ Update product status to "sold"
        $update_sql = "UPDATE products SET status = 'sold' WHERE p_id = ?";
        $update_stmt = $conn->prepare($update_sql);

        if (!$update_stmt) {
            die("Prepare failed: " . $conn->error);
        }

        $update_stmt->bind_param("i", $product_id);

        if (!$update_stmt->execute()) {
            die("Product status update failed: " . $update_stmt->error);
        }

        $update_stmt->close();
    } else {
        // If no bid was placed, mark the product as "unsold"
        $update_sql = "UPDATE products SET status = 'rejected' WHERE p_id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("i", $product_id);
        $update_stmt->execute();
        $update_stmt->close();
    }
}

echo "Bidding process checked and products updated.";

$conn->close();
?>
