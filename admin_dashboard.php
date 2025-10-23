<?php
session_start();

if (!isset($_SESSION['a'])) {
    header("Location: home_page.php"); // Redirect to home page if not logged in
    exit();
}

// Assuming 'a' contains user data like 'UserID', 'Username', and 'Password'
$user = $_SESSION['a'];

// Check if $user is an array
if (!is_array($user)) {
    die('Error: User data is not an array.');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
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
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    color: #2d3439;
    line-height: 1.6;
}

/* Dashboard Card */
.dashboard-container {
    background: #ffffff;
    padding: 2.5rem;
    border-radius: 1.5rem;
    box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.1);
    width: 100%;
    max-width: 720px;
    margin: 2rem;
    position: relative;
    overflow: hidden;
}

/* Header Section */
.dashboard-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2.5rem;
    padding-bottom: 1.5rem;
    border-bottom: 2px solid #f1f5f9;
}

/* Main Heading */
.main-heading {
    font-size: 2.2rem;
    background: linear-gradient(135deg, #3b82f6, #2563eb);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
    font-weight: 700;
    letter-spacing: -0.5px;
}

.main-heading::after {
    content: '';
    display: block;
    width: 60px;
    height: 3px;
    background: #3b82f6;
    margin-top: 0.75rem;
    border-radius: 2px;
}

/* User Info Section */
.account-details {
    text-align: center;
    margin: 2.5rem 0;
}

.detail-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.25rem;
    max-width: 500px;
    margin: 0 auto;
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

/* Action Buttons */
.action-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 2.5rem;
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

.btn-logout {
    background: #ef4444;
    color: white;
    margin-left: auto;
}

.btn-admin {
    background: #3b82f6;
    color: white;
}

.btn-account {
    position: absolute;
    left: 50%;
    transform: translateX(-50%);
    background: #22c55e;
    color: white;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.15);
}

.btn:active {
    transform: translateY(0);
}

/* Responsive Design */
@media (max-width: 768px) {
    .dashboard-container {
        padding: 1.75rem;
        margin: 1rem;
    }

    .dashboard-header {
        flex-direction: column;
        gap: 1rem;
    }

    .main-heading {
        font-size: 1.8rem;
    }

    .detail-grid {
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

    .btn-account {
        position: static;
        transform: none;
        order: -1;
    }
}

@media (max-width: 480px) {
    .main-heading {
        font-size: 1.6rem;
    }

    .detail-value {
        font-size: 1rem;
    }

    .btn {
        padding: 0.75rem 1.25rem;
        font-size: 0.9rem;
    }
}

/* Utility Classes */
.text-gradient {
    background: linear-gradient(135deg, #3b82f6, #2563eb);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
}
</style>
</head>
<body>

<?php include __DIR__ . '/includes/lang_selector.php'; ?>

    <div class="dashboard-container">
        <header class="dashboard-header">
            <h1 class="main-heading">ADMIN DASHBOARD</h1>
            <a href="logout.php" class="btn btn-logout">
                <svg aria-hidden="true" class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                Logout
            </a>
        </header>

        <section class="account-details">
            <div class="detail-grid">
                <div class="detail-item">
                    <span class="detail-label">Email Address</span>
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
        </section>

        <div class="action-bar">
            <a href="admin_checking.php" class="btn btn-admin">
                📝 Update State
            </a>
            <a href="account_details_admin.php" class="btn btn-account">
                👤 Account Details
            </a>
        </div>
    </div>
</body>
</html>
