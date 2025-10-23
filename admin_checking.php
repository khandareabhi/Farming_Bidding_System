<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "project1");

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header("Location: home_page.php");
    exit();
}

$sql = "SELECT * FROM products WHERE status = 'pending'";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<style>
    /* Modern Reset */
*,
*::before,
*::after {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

/* Base Styling */
body {
    font-family: 'Inter', system-ui, -apple-system, sans-serif;
    background: #f8fafc;
    color: #1e293b;
    line-height: 1.6;
    padding: 2rem 1rem;
}

/* Dashboard Container */
.dashboard-container {
    max-width: 1400px;
    margin: 0 auto;
    background: #ffffff;
    border-radius: 1rem;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

/* Header Styling */
.dashboard-header {
    background: linear-gradient(135deg, #2f855a, #38a169);
    padding: 2rem;
    color: white;
    margin-bottom: 2rem;
}

.dashboard-header h2 {
    font-size: 1.8rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

/* Table Styling */
.verification-table {
    width: 100%;
    border-collapse: collapse;
    background: white;
}

.verification-table thead {
    background: #f1f5f9;
}

.verification-table th {
    padding: 1rem;
    text-align: left;
    font-weight: 600;
    color: #1e293b;
    border-bottom: 2px solid #e2e8f0;
}

.verification-table td {
    padding: 1rem;
    border-bottom: 1px solid #e2e8f0;
}

/* Status Badges */
.status-badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    border-radius: 1rem;
    font-size: 0.875rem;
    font-weight: 500;
}

.status-pending {
    background: #fef3c7;
    color: #d97706;
}

/* Action Buttons */
.action-group {
    display: flex;
    gap: 0.5rem;
}

.btn {
    padding: 0.5rem 1rem;
    border: none;
    border-radius: 0.5rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-verify {
    background: #10b981;
    color: white;
}

.btn-reject {
    background: #ef4444;
    color: white;
}

.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.btn:active {
    transform: translateY(0);
}

/* Responsive Design */
@media (max-width: 1200px) {
    .dashboard-container {
        margin: 0 1rem;
    }
}

@media (max-width: 768px) {
    .verification-table {
        display: block;
        overflow-x: auto;
    }

    .dashboard-header {
        padding: 1.5rem;
    }

    .dashboard-header h2 {
        font-size: 1.5rem;
    }

    .verification-table th,
    .verification-table td {
        padding: 0.75rem;
        font-size: 0.875rem;
    }

    .btn {
        padding: 0.5rem 0.75rem;
        font-size: 0.875rem;
    }
}

@media (max-width: 480px) {
    .action-group {
        flex-direction: column;
    }

    .btn {
        width: 100%;
        justify-content: center;
    }
}

/* Data Visualization */
.data-highlight {
    color: #3b82f6;
    font-weight: 600;
}

/* Hover Effects */
.verification-table tbody tr:hover {
    background-color: #f8fafc;
}

/* Empty State Styling */
.empty-state {
    text-align: center;
    padding: 4rem;
    color: #64748b;
}

.empty-state p {
    margin-top: 1rem;
}
</style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Verify Products</title>
</head>
<body>

<?php include __DIR__ . '/includes/lang_selector.php'; ?>

    <div class="dashboard-container">
        <div class="dashboard-header">
            <h2>
                📋 Pending Product Verification
            </h2>
        </div>
        
        <table class="verification-table">
            <thead>
                <tr>
                    <th>Product ID</th>
                    <th>Product Name</th>
                    <th>Status</th>
                    <th>Timestamp</th>
                    <th>Min Bid</th>
                    <th>Time Limit</th>
                    <th>Quantity</th>
                    <th>Farmer Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td class="data-highlight"><?php echo htmlspecialchars($row['p_id']); ?></td>
                    <td><?php echo htmlspecialchars($row['p_name']); ?></td>
                    <td><span class="status-badge status-pending">Pending</span></td>
                    <td><?php echo htmlspecialchars($row['timestamp']); ?></td>
                    <td>₹<?php echo htmlspecialchars($row['min_bidding']); ?></td>
                    <td><?php echo htmlspecialchars($row['time_limit']); ?> hrs</td>
                    <td><?php echo htmlspecialchars($row['quantity']); ?> kg</td>
                    <td><?php echo htmlspecialchars($row['farmer_email']); ?></td>
                    <td>
                        <div class="action-group">
                            <form action="verify_product.php" method="POST">
                                <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($row['p_id']); ?>">
                                <button type="submit" name="action" value="Verify" class="btn btn-verify">✅ Verify</button>
                                <button type="submit" name="action" value="Reject" class="btn btn-reject">❌ Reject</button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php
mysqli_close($conn);
?>
