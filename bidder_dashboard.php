<?php
session_start();

if (!isset($_SESSION['a'])) {
    header("Location: home_page.php"); // Redirect to home page if not logged in
    exit();
}

$user = $_SESSION['a'];

if (!is_array($user)) {
    die('Error: User data is not an array.');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bidder Dashboard</title>
    <style>
    /* Modern Dashboard Styles */
    body {
        font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        background: linear-gradient(135deg, #1abc9c, #16a085);
        min-height: 100vh;
        margin: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        color: #2d3439;
    }

    .dashboard-container {
        background: #ffffff;
        padding: 2.5rem;
        border-radius: 1.5rem;
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 600px;
        margin: 2rem;
        position: relative;
    }

    .dashboard-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        padding-bottom: 1.5rem;
        border-bottom: 2px solid #f1f5f9;
    }

    .main-heading {
        font-size: 2rem;
        background: linear-gradient(135deg, #16a085, #1abc9c);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
        font-weight: 700;
        letter-spacing: -0.5px;
        text-align: center;
        flex-grow: 1;
    }

    .main-heading::after {
        content: '';
        display: block;
        width: 60px;
        height: 3px;
        background: #16a085;
        margin: 0.75rem auto 0;
        border-radius: 2px;
    }

    .user-info {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
        margin: 2rem 0;
    }

    .detail-item {
        background: #f8fafc;
        padding: 1.25rem;
        border-radius: 0.75rem;
        border: 1px solid #e2e8f0;
        text-align: left;
    }

    .detail-label {
        display: block;
        font-size: 0.9rem;
        color: #64748b;
        margin-bottom: 0.5rem;
        font-weight: 500;
    }

    .detail-value {
        font-size: 1.1rem;
        color: #1e293b;
        font-weight: 600;
    }

    .action-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 2rem;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        padding: 0.9rem 1.75rem;
        border-radius: 0.75rem;
        font-weight: 600;
        transition: all 0.2s ease;
        text-decoration: none;
        gap: 0.75rem;
    }

    .btn-bid {
        background: #27ae60;
        color: white;
    }

    .btn-account {
        background: #3498db;
        color: white;
    }

    .btn-logout {
        background: #e74c3c;
        color: white;
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.15);
    }

    @media (max-width: 768px) {
        .dashboard-container {
            padding: 1.75rem;
            margin: 1rem;
        }

        .user-info {
            grid-template-columns: 1fr;
        }

        .action-bar {
            flex-direction: column;
            gap: 1rem;
        }

        .btn {
            width: 100%;
            justify-content: center;
        }

        .main-heading {
            font-size: 1.8rem;
        }
    }

    @media (max-width: 480px) {
        .main-heading {
            font-size: 1.6rem;
        }

        .detail-value {
            font-size: 1rem;
        }
    }
</style>
</head>
<body>

<?php include __DIR__ . '/includes/lang_selector.php'; ?>

    <div class="dashboard-container">
        <div class="dashboard-header">
            <h1 class="main-heading">BIDDER DASHBOARD</h1>
        </div>

        <div class="user-info">
            <div class="detail-item">
                <span class="detail-label">Email</span>
                <span class="detail-value"><?php echo htmlspecialchars($user['email']); ?></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Full Name</span>
                <span class="detail-value"><?php echo htmlspecialchars($user['full_name']); ?></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">City</span>
                <span class="detail-value"><?php echo htmlspecialchars($user['city']); ?></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Pincode</span>
                <span class="detail-value"><?php echo htmlspecialchars($user['pincode']); ?></span>
            </div>
        </div>

        <div class="action-bar">
            <a href="biding_page.php" class="btn btn-bid">🏷️ Bid to Product</a>
            <a href="account_details.php" class="btn btn-account">📦 Purchased Products</a>
            <a href="logout.php" class="btn btn-logout">🚪 Logout</a>
        </div>
    </div>
</body>
</html>
