<?php
session_start();
$conn = new mysqli("localhost", "root", "", "project1");

if (!isset($_GET['product_id'])) {
    echo "<script>
        alert('Invalid request!');
        window.location.href='farmer_dashboard.php';
    </script>";
    exit();
}

$product_id = $_GET['product_id'];

// ✅ Fetch bill details
$sql = "SELECT bill.*, products.p_name ,products.bid_end_time
        FROM bill 
        JOIN products ON bill.product_id = products.p_id 
        WHERE bill.product_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$bill = $result->fetch_assoc();

if (!$bill) {
    echo "<script>
        alert('Bill not found!');
        window.location.href='farmer_dashboard.php';
    </script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bill Details</title>
    <style>
    /* Modern Base Styles */
    :root {
        --primary:rgb(3, 222, 21);
        --secondary: #10b981;
        --accent: #f59e0b;
        --dark: #1e293b;
        --light: #f8fafc;
    }

    body {
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
        background: linear-gradient(135deg, #f0f4ff, #e6f0ff);
        color: var(--dark);
        margin: 0;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem 1rem;
    }

    .bill-container {
        background: white;
        border-radius: 1.5rem;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        padding: 2.5rem;
        max-width: 600px;
        width: 100%;
        margin: 2rem auto;
        position: relative;
        border-top: 6px solid var(--primary);
    }

    h2 {
        text-align: center;
        color: var(--primary);
        font-size: 2rem;
        margin-bottom: 2rem;
        position: relative;
    }

    h2::after {
        content: '';
        display: block;
        width: 60px;
        height: 3px;
        background: var(--secondary);
        margin: 1rem auto;
        border-radius: 2px;
    }

    .bill-details {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .bill-details p {
        margin: 0;
        padding: 1rem;
        background: var(--light);
        border-radius: 0.75rem;
        display: flex;
        flex-direction: column;
    }

    .bill-details strong {
        color: var(--primary);
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }

    .bill-details span {
        font-size: 1.1rem;
        font-weight: 500;
        color: var(--dark);
    }

    .print-btn {
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: white;
        border: none;
        padding: 1rem 2rem;
        border-radius: 0.75rem;
        cursor: pointer;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin: 0 auto;
        transition: transform 0.2s ease;
    }

    .print-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    @media (max-width: 768px) {
        .bill-container {
            padding: 1.5rem;
        }

        .bill-details {
            grid-template-columns: 1fr;
        }

        h2 {
            font-size: 1.75rem;
        }
    }

    @media print {
        body {
            background: white;
            padding: 0;
        }

        .bill-container {
            box-shadow: none;
            border: none;
            padding: 0;
        }

        .print-btn {
            display: none;
        }
    }

    /* Animation */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .bill-container {
        animation: fadeIn 0.6s ease-out;
    }
</style>
</head>
<body>

<?php include __DIR__ . '/includes/lang_selector.php'; ?>

<div class="bill-container">
    <h2>Bill Details</h2>
    <div class="bill-details">
        <p>
            <strong>Product Name</strong>
            <span><?php echo htmlspecialchars($bill['p_name']); ?></span>
        </p>
        <p>
            <strong>Final Price</strong>
            <span>₹<?php echo htmlspecialchars($bill['bid_amount']); ?></span>
        </p>
        <p>
            <strong>Bill Date</strong>
            <span><?php echo htmlspecialchars($bill['bid_end_time']); ?></span>
        </p>
        <p>
            <strong>Farmer Email</strong>
            <span><?php echo htmlspecialchars($bill['farmer_email']); ?></span>
        </p>
        <p>
            <strong>Bidder Email</strong>
            <span><?php echo htmlspecialchars($bill['bidder_email']); ?></span>
        </p>
    </div>
    <button class="print-btn" onclick="printBill()">
        🖨️ Print Bill
    </button>
</div>

<script>
function printBill() {
    window.print();
}
</script>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
