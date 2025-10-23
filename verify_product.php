<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "project1");

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header("Location: home_page.php");
    exit();
}

if (isset($_POST['product_id']) && isset($_POST['action'])) {
    $productID = $_POST['product_id'];
    $action = $_POST['action'];

    if ($action === 'Verify') {
        // Retrieve the actual bid_end_time from the database
        $query = "SELECT bid_end_time FROM products WHERE p_id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $productID);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $bidEndTime);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);

        // Ensure there's a valid bid end time; otherwise, set it to 24 hours from now
        if (empty($bidEndTime)) {
            $bidEndTime = date('Y-m-d H:i:s', strtotime('+1 days'));
        }

        $status = 'verified';
    } elseif ($action === 'Reject') {
        $status = 'rejected';
        $bidEndTime = NULL;
    } else {
        die("Invalid action.");
    }

    $sql = "UPDATE products SET status = ?, bid_end_time = ? WHERE p_id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt === false) {
        die("Prepare failed: " . htmlspecialchars(mysqli_error($conn)));
    }

    mysqli_stmt_bind_param($stmt, "ssi", $status, $bidEndTime, $productID);
    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_affected_rows($stmt) > 0) {
        echo "<script>
            alert('Product status updated successfully and added to bidding process.');
            window.location.href='admin_dashboard.php';
        </script>";
    } else {
        echo "<script>
            alert('Failed to update product status.');
            window.history.back();
        </script>";
    }

    mysqli_stmt_close($stmt);
}

mysqli_close($conn);
?>
