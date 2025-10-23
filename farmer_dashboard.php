<?php
session_start();

if (!isset($_SESSION['a'])) {
    header("Location: a.php"); // Redirect if not logged in
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
    <title>Farmer Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #e0f7fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            display: flex;
            width: 80%;
            height: 80vh;
            background: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border-radius: 10px;
            overflow: hidden;
            transition: all 0.5s ease;
        }

        .dashboard {
            width: 100%;
            background-color: #00796b;
            color: white;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            transition: width 0.5s ease;
        }

        .dashboard h2 {
            margin-bottom: 20px;
            font-size: 24px;
        }

        table {
            width: 80%;
            border-collapse: collapse;
            margin-top: 10px;
            background: white;
            color: black;
            border-radius: 5px;
            overflow: hidden;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background: #ffeb3b;
            color: black;
        }

        .btn {
            padding: 10px 15px;
            background-color: #ffeb3b;
            color: #333;
            border: none;
            cursor: pointer;
            margin: 10px;
            border-radius: 5px;
            font-size: 16px;
            width: 150px;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #fbc02d;
        }

        .dashboard.move-left {
            width: 50%;
        }

        .form-container {
            width: 50%;
            padding: 30px;
            background-color: white;
            display: none;
            text-align: center;
            transition: opacity 0.5s ease;
        }

        .form-container h2 {
            color: #333;
            margin-bottom: 20px;
        }

        .form-container label {
            font-size: 16px;
            display: block;
            text-align: left;
            margin-bottom: 5px;
        }

        .form-container input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }

        .form-container.show {
            display: block;
        }
    </style>
</head>
<body>

<?php include __DIR__ . '/includes/lang_selector.php'; ?>

<div class="container">
    <div class="dashboard" id="dashboard">
        <h2>WELCOME, FARMER DASHBOARD</h2>

        <table>
            <tr>
                <th>Email</th>
                <td><?php echo htmlspecialchars($user['email']); ?></td>
            </tr>
            <tr>
                <th>Full Name</th>
                <td><?php echo htmlspecialchars($user['full_name']); ?></td>
            </tr>
            <tr>
                <th>City</th>
                <td><?php echo htmlspecialchars($user['city']); ?></td>
            </tr>
            <tr>
                <th>Pincode</th>
                <td><?php echo htmlspecialchars($user['pincode']); ?></td>
            </tr>
        </table>

        <button class="btn" onclick="openForm()">Add Product</button>
        <a href="f_details.php" class="btn">Bill Details</a>
        <a href="logout.php" class="btn">Logout</a>
    </div>

    <div class="form-container" id="formContainer">
        <h2>Add Product</h2>
        <form action="product_added.php" method="post" enctype="multipart/form-data">
            <label>Product Name:</label>
            <input type="text" name="p_name" required>

            <label>Minimum Bid:</label>
            <input type="number" name="min_bidding" required>

            <label>Time Limit:</label>
            <input type="datetime-local" id="time_limit" name="time_limit" required>

            <label>Quantity:</label>
            <input type="number" name="quantity" required>

            <label>Photo of Product</label>
            <input type="file" name="photo1" accept="image/*" required>

            <button type="submit" class="btn">Submit</button>
            <button type="button" class="btn" onclick="closeForm()">Cancel</button>
        </form>
    </div>
</div>

<script>
    function openForm() {
        document.getElementById('dashboard').classList.add('move-left');
        document.getElementById('formContainer').classList.add('show');
        setTimeLimit(); // Ensure the time limit is set when opening the form
    }

    function closeForm() {
        document.getElementById('dashboard').classList.remove('move-left');
        document.getElementById('formContainer').classList.remove('show');
    }

    function setTimeLimit() {
        let inputField = document.getElementById("time_limit");
        let now = new Date();

        let formattedNow = now.toISOString().slice(0, 16); // Get current datetime in required format

        inputField.setAttribute("min", formattedNow); // Only restrict past times
        inputField.removeAttribute("max"); // Remove the 2-hour limit
    }

    document.addEventListener("DOMContentLoaded", setTimeLimit);
</script>

</body>
</html>
