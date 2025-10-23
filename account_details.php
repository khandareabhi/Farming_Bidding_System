<?php
session_start();
$conn = new mysqli("localhost", "root", "", "project1");

// ✅ Ensure the user is logged in
if (!isset($_SESSION['a']) || !isset($_SESSION['a']['email'])) {
    echo "<script>
        alert('You need to log in first!'); 
        window.location.href='a.php';
    </script>";
    exit();
}

$bidder_email = $_SESSION['a']['email'];

// ✅ Fetch bills for the logged-in bidder with the farmer's email, only for sold products
$sql = "SELECT bill.product_id, products.p_name, products.farmer_email, 
               bill.bid_amount AS final_price, products.bid_end_time AS bill_date
        FROM bill
        JOIN products ON bill.product_id = products.p_id
        WHERE bill.bidder_email = ? AND products.status = 'sold'";  // ✅ Only sold products

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $bidder_email);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Purchased Products</title>
    <style>
    /* Modern Base Styles */
    :root {
        --primary:rgb(1, 233, 5);
        --secondary:rgb(2, 199, 156);
        --accent: #f59e0b;
        --dark: #1e293b;
        --light: #f8fafc;
    }

    body {
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
        background: linear-gradient(135deg, #f0f4ff, #e6f0ff);
        color: var(--dark);
        margin: 0;
        padding: 2rem 1rem;
        min-height: 100vh;
    }

    h2 {
        text-align: center;
        font-size: 2.5rem;
        color: var(--primary);
        margin: 2rem 0;
        position: relative;
        letter-spacing: -0.5px;
    }

    h2::after {
        content: '';
        display: block;
        width: 80px;
        height: 4px;
        background: var(--secondary);
        margin: 1rem auto;
        border-radius: 2px;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        background: white;
        border-radius: 1rem;
        overflow: hidden;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        margin: 2rem 0;
    }

    th, td {
        padding: 1rem;
        text-align: center;
        border-bottom: 1px solid #e2e8f0;
    }

    th {
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: white;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.875rem;
    }

    tr:hover {
        background-color: #f8fafc;
    }

    .view-btn {
        background: linear-gradient(135deg, var(--secondary), var(--primary));
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 0.5rem;
        cursor: pointer;
        font-weight: 600;
        transition: transform 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .view-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .view-btn::before {
        content: '📄';
        filter: brightness(0) invert(1);
    }

    .empty-state {
        text-align: center;
        padding: 4rem;
        background: white;
        border-radius: 1rem;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        margin: 2rem auto;
        max-width: 600px;
    }

    .empty-state p {
        font-size: 1.25rem;
        color: #64748b;
        margin-top: 1rem;
    }

    @media (max-width: 768px) {
        h2 {
            font-size: 2rem;
        }

        table {
            border-radius: 0.75rem;
        }

        th, td {
            padding: 0.75rem;
            font-size: 0.875rem;
        }

        .view-btn {
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
        }
    }

    @media (max-width: 480px) {
        h2 {
            font-size: 1.75rem;
        }

        body {
            padding: 1rem;
        }

        .empty-state {
            padding: 2rem;
        }

        .empty-state p {
            font-size: 1rem;
        }
    }

    /* Animation */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    table {
        animation: fadeIn 0.6s ease-out;
    }
</style>
</head>
<body>

<?php include __DIR__ . '/includes/lang_selector.php'; ?>

<h2>My Purchased Products</h2>

<?php if ($result->num_rows > 0): ?>  <!-- ✅ Only show table if there are results -->
<div class="container">
    <table>
        <!-- table content -->
       
    <tr>
        <th>Product ID</th>
        <th>Product Name</th>
        <th>Farmer Email</th>
        <th>Final Price (₹)</th>
        <th>Bill Date</th>  <!-- ✅ Fetching from products.bid_end_time -->
        <th>Action</th>  <!-- ✅ New column for View Bill -->
    </tr>

    <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?php echo htmlspecialchars($row['product_id']); ?></td>
        <td><?php echo htmlspecialchars($row['p_name']); ?></td>
        <td><?php echo htmlspecialchars($row['farmer_email']); ?></td>
        <td>₹<?php echo htmlspecialchars($row['final_price']); ?></td>
        <td><?php echo htmlspecialchars($row['bill_date']); ?></td>  <!-- ✅ Now showing bid_end_time -->
        <td>
            <!-- ✅ View Bill Button -->
            <form action="view_bill.php" method="GET">
                <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>">
                <button type="submit" class="view-btn">View Bill</button>
            </form>
        </td>
    </tr>
    <?php endwhile; ?>


    </table>
</div>
<?php else: ?>
<div class="empty-state">
    <h3>📭 No Purchases Yet</h3>
    <p>Your purchased products will appear here once you win bids</p>
</div>
<?php endif; ?>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
