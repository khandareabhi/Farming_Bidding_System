<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "project1");

// Fetch products with active bids
$sql = "SELECT * FROM products WHERE status = 'verified' AND bid_end_time > NOW()";
$result = mysqli_query($conn, $sql);

// Function to calculate remaining bid duration
function timeLeft($endTime) {
    $now = new DateTime();
    $end = new DateTime($endTime);
    $diff = $now->diff($end);

    if ($diff->invert) {
        return "<span class='blink-text' style='color:red; font-weight:bold;'>Bidding Ended</span>";
    }

    return sprintf(
        "<span class='blink-text' style='color:green;'>%d days %dh %dm %ds left</span>",
        $diff->d, $diff->h, $diff->i, $diff->s
    );
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bidding Page</title>
    <style>
    /* Modern Base Styles */
    :root {
        --primary: #2ecc71;
        --secondary: #27ae60;
        --accent: #e67e22;
        --dark: #2c3e50;
        --light: #ecf0f1;
    }

    body {
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
        background: linear-gradient(135deg, #d1f2eb, #a9dfbf);
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

    .container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2rem;
        max-width: 1400px;
        margin: 0 auto;
        padding: 1rem;
    }

    .product-card {
        background: white;
        border-radius: 1.5rem;
        box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
        padding: 1.5rem;
        position: relative;
        overflow: hidden;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 2px solid var(--primary);
    }

    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);
    }

    .product-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 5px;
        background: linear-gradient(90deg, var(--primary), var(--secondary));
    }

    .product-img {
        width: 100%;
        height: 200px;
        object-fit: cover;
        border-radius: 1rem;
        margin-bottom: 1rem;
        border: 3px solid var(--light);
        transition: transform 0.3s ease;
    }

    .product-img:hover {
        transform: scale(1.05);
    }

    .product-name {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 0.5rem;
    }

    .price {
        font-size: 1.25rem;
        color: var(--primary);
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .bid-info {
        background: var(--light);
        padding: 1rem;
        border-radius: 0.75rem;
        margin-bottom: 1.5rem;
    }

    .bid-duration {
        font-size: 1rem;
        color: var(--dark);
        font-weight: 500;
    }

    .time-left {
        color: var(--secondary);
        font-weight: 700;
        margin-top: 0.5rem;
        display: block;
        animation: pulse 1.5s infinite;
    }

    form {
        display: grid;
        gap: 1rem;
    }

    input[type="number"] {
        width: 100%;
        padding: 0.75rem;
        border: 2px solid #e2e8f0;
        border-radius: 0.75rem;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    input[type="number"]:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(46, 204, 113, 0.2);
        outline: none;
    }

    input[type="submit"] {
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: white;
        border: none;
        padding: 1rem;
        border-radius: 0.75rem;
        font-weight: 600;
        cursor: pointer;
        transition: transform 0.2s ease;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    input[type="submit"]:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .status-badge {
        position: absolute;
        top: 1rem;
        right: 1rem;
        background: var(--accent);
        color: white;
        padding: 0.25rem 1rem;
        border-radius: 999px;
        font-size: 0.875rem;
        font-weight: 600;
    }

    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }

    @media (max-width: 768px) {
        h2 {
            font-size: 2rem;
        }

        .container {
            grid-template-columns: 1fr;
        }

        .product-card {
            margin: 0 1rem;
        }
    }

    @media (max-width: 480px) {
        h2 {
            font-size: 1.75rem;
        }

        .product-img {
            height: 160px;
        }

        input[type="submit"] {
            padding: 0.75rem;
        }
    }
</style>
</head>
<body>

<?php include __DIR__ . '/includes/lang_selector.php'; ?>

    <h2>Available Products for Bidding</h2>
    <div class="container">
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <div class="product-card">
            <img src="<?php echo htmlspecialchars($row['photo1']); ?>" alt="Product Image" class="product-img">
            <div class="product-name"><?php echo htmlspecialchars($row['p_name']); ?></div>
            <div class="price">Price: ₹<?php echo htmlspecialchars($row['min_bidding']); ?>/ton</div>
            <div class="bid-duration">Bid Duration End:  <?php echo  $endTime = date('Y-m-d H:i:s', strtotime($row['bid_end_time'])); ?></div>

            <!-- Ensure bidding is still active -->
            <?php if (strtotime($row['bid_end_time']) > time()): ?>
                <form action="place_bid.php" method="POST">
                    <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($row['p_id']); ?>">
                    <?php 
                        $starting_bid = $row['current_bid'] ? $row['current_bid'] + 1 : $row['min_bidding']; 
                    ?>
                    <input type="number" name="bid_amount" min="<?php echo $starting_bid; ?>" required>
                    <input type="submit" value="Bid Now">
                </form>
            <?php else: ?>
                <p style="color: red; font-weight: bold;">Bidding Ended</p>
            <?php endif; ?>
        </div>
        <?php endwhile; ?>
    </div>

</body>
</html>

<?php
mysqli_close($conn);
?>
