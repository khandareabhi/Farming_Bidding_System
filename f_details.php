<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "project1");

// Check database connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// ✅ Ensure user is logged in
if (!isset($_SESSION['a']) || !isset($_SESSION['a']['email'])) {
    echo "<script>
        alert('You need to log in first!');
        window.location.href='a.php';
    </script>";
    exit();
}

$user_email = $_SESSION['a']['email'];

// ✅ Get user role
$sql = "SELECT role FROM registration WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_email);
$stmt->execute();
$stmt->bind_result($role);
$stmt->fetch();
$stmt->close();

// ✅ Query based on user role
if ($role === 'Farmer') {
    // 🚜 Show only sold products for farmers
    $sql = "SELECT product_id, product_name, bidder_email, bid_amount AS final_price, bid_time AS bill_date 
            FROM bill WHERE product_id IN (SELECT p_id FROM products WHERE status = 'sold' AND farmer_email = ?)";
} else {
    // 🏆 Show only won products for bidders
    $sql = "SELECT product_id, product_name, farmer_email, bid_amount AS final_price, bid_time AS bill_date 
            FROM bill WHERE bidder_email = ?";
}

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_email);
$stmt->execute();
$result = $stmt->get_result();

// ✅ Check for SQL errors
if (!$result) {
    die("SQL Error: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bill Details</title>
    <style>
    /* Crop Theme Styles */
    :root {
        --primary: #2e7d32;    /* Deep green */
        --secondary: #388e3c;  /* Medium green */
        --accent: #8bc34a;     /* Fresh green */
        --light: #f1f8e9;      /* Light beige-green */
        --dark: #1b5e20;       /* Dark green */
    }

    body {
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
        background: linear-gradient(135deg, #f0fff4, #e6ffed);
        color: #2d3439;
        margin: 0;
        padding: 2rem 1rem;
        min-height: 100vh;
    }

    h2 {
        text-align: center;
        font-size: 2.5rem;
        color: var(--dark);
        margin: 2rem 0;
        position: relative;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
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
        background-color: var(--light);
    }

    .view-btn {
        background: var(--secondary);
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 0.5rem;
        cursor: pointer;
        font-weight: 600;
        transition: transform 0.2s ease;
    }

    .view-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
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
        margin: 1rem auto;
        transition: transform 0.2s ease;
    }

    .print-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    @media (max-width: 768px) {
        h2 {
            font-size: 2rem;
        }

        table {
            font-size: 0.875rem;
        }

        th, td {
            padding: 0.75rem;
        }
    }

    @media (max-width: 480px) {
        h2 {
            font-size: 1.75rem;
        }

        body {
            padding: 1rem;
        }
    }

    @media print {
        body {
            background: white;
            padding: 0;
        }

        table {
            box-shadow: none;
            border: 2px solid #ddd;
        }

        .print-btn {
            display: none;
        }

        th {
            background: #fff !important;
            color: #000 !important;
            border-bottom: 2px solid #000;
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

<h2>🌾 Agricultural Bill Details</h2>
    <button class="print-btn" onclick="printBill()">
        🖨️ Print Bill Details
    </button>

    <div class="container">
    <table>
        
    <tr>
        <th>Product ID</th>
        <th>Product Name</th>
        <th><?php echo ($role === 'Farmer') ? "Bidder Email" : "Farmer Email"; ?></th>
        <th>Final Price (₹)</th>
        <th>Bill Date</th>
        <th>Action</th> 
    </tr>

    <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?php echo htmlspecialchars($row['product_id']); ?></td>
        <td><?php echo htmlspecialchars($row['product_name']); ?></td>
        <td><?php echo htmlspecialchars(($role === 'Farmer') ? $row['bidder_email'] : $row['farmer_email']); ?></td>
        <td>₹<?php echo htmlspecialchars($row['final_price']); ?></td>
        <td><?php echo htmlspecialchars($row['bill_date']); ?></td>
        <td>
            <!-- ✅ View Bill Button -->
            <form action="view_bill_farm.php" method="GET">
                <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>">
                <button type="submit" class="view-btn">View Bill</button>
            </form>
        </td>
    </tr>
    <?php endwhile; ?>

</table>

    </div>


<script>
function printBill() {
    window.print();
}
</script>
</body>
</html>

<?php
mysqli_close($conn);
?>
