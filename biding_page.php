<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "project1");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch products with active bids and their highest bid info
$sql = "SELECT p.*, 
            (SELECT MAX(bid_amount) FROM bill WHERE product_id = p.p_id) AS max_bid,
            (SELECT bidder_email FROM bill WHERE product_id = p.p_id ORDER BY bid_amount DESC LIMIT 1) AS highest_bidder
        FROM products p 
        WHERE p.status = 'verified' AND p.bid_end_time > NOW()";

$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bidding Page</title>
    <style>
        :root {
            --primary: #2ecc71;
            --secondary: #27ae60;
            --accent: #e67e22;
            --dark: #2c3e50;
            --light: #ecf0f1;
        }
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #d1f2eb, #a9dfbf);
            margin: 0;
            padding: 2rem 1rem;
            min-height: 100vh;
        }
        h2 {
            text-align: center;
            font-size: 2.5rem;
            color: var(--dark);
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
            transition: all 0.3s ease;
            border: 2px solid var(--primary);
        }
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);
        }
        .product-img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 1rem;
        }
        .product-name {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--dark);
        }
        .price {
            font-size: 1.25rem;
            color: var(--primary);
            font-weight: 600;
        }
        .bid-info {
            background: var(--light);
            padding: 1rem;
            border-radius: 0.75rem;
        }
        .time-left {
            color: var(--secondary);
            font-weight: 700;
            animation: pulse 1.5s infinite;
        }
        input[type="number"] {
            width: 100%;
            padding: 0.75rem;
            border-radius: 0.75rem;
            font-size: 1rem;
        }
        input[type="submit"] {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            padding: 1rem;
            border-radius: 0.75rem;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s;
        }
        input[type="submit"]:hover {
            transform: translateY(-2px);
        }
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
 @keyframes blink {
    0% { opacity: 1; }
    50% { opacity: 0; }
    100% { opacity: 1; }
}

.time-left {
    color: var(--secondary);
    font-weight: 700;
    animation: blink 1.5s infinite;
}

@keyframes blink-placeholder {
    0% { opacity: 1; }
    50% { opacity: 0; }
    100% { opacity: 1; }
}

input[type="number"]::placeholder {
    animation: blink-placeholder 1.5s infinite;
}

  </style>
</head>
<body>

<?php include __DIR__ . '/includes/lang_selector.php'; ?>

    <h2>Available Products for Bidding</h2>
    <div class="container">
        <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <div class="product-card">
                    <img src="<?php echo htmlspecialchars($row['photo1']); ?>" alt="Product Image" class="product-img">
                    <div class="product-name"><?php echo htmlspecialchars($row['p_name']); ?></div>
                    <div class="price">Price: ₹<?php echo htmlspecialchars($row['min_bidding']); ?>/ton</div>
                    
                    <div class="bid-info">
                        <strong>Highest Bid:</strong> ₹<?php echo $row['max_bid'] ? $row['max_bid'] : "No bids yet"; ?><br>
                        <strong>Highest Bidder:</strong> <?php echo $row['highest_bidder'] ? $row['highest_bidder'] : "No bids yet"; ?>
                    </div>

                    <!-- Countdown Timer -->
                    <div class="bid-duration">
                        <strong>Time Left:</strong> <span class="time-left" data-endtime="<?php echo htmlspecialchars($row['bid_end_time']); ?>"></span>
                    </div>

                    <!-- Ensure bidding is still active -->
                    <?php if (strtotime($row['bid_end_time']) > time()): ?>
                        <form action="place_bid.php" method="POST">
                            <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($row['p_id']); ?>">
                            <?php 
                                $starting_bid = $row['max_bid'] ? $row['max_bid'] + 1 : $row['min_bidding']; 
                            ?>
                            <input type="number" name="bid_amount" min="<?php echo $starting_bid; ?>"  required   placeholder="Enter Bid Here">
                            <input type="submit" value="Bid Now">
                        </form>
                    <?php else: ?>
                        <p style="color: red; font-weight: bold;">Bidding Ended</p>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p style="text-align:center; color:red; font-weight:bold;">No verified products available for bidding.</p>
        <?php endif; ?>
    </div>

    <script>
        function updateCountdown() {
            const timers = document.querySelectorAll(".time-left");
            timers.forEach(timer => {
                const endTime = new Date(timer.dataset.endtime).getTime();
                const now = new Date().getTime();
                const timeDiff = endTime - now;

                if (timeDiff <= 0) {
                    timer.innerHTML = "<span style='color:red; font-weight:bold;'>Bidding Ended</span>";
                } else {
                    const days = Math.floor(timeDiff / (1000 * 60 * 60 * 24));
                    const hours = Math.floor((timeDiff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const minutes = Math.floor((timeDiff % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((timeDiff % (1000 * 60)) / 1000);
                    timer.innerHTML = `<span style='color:green;'>${days}d ${hours}h ${minutes}m ${seconds}s</span>`;
                }
            });
        }
        setInterval(updateCountdown, 1000);
        updateCountdown();
    </script>

</body>
</html>

<?php
mysqli_close($conn);
?>
