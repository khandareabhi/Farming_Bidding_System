<?php
session_start();
$conn = new mysqli("localhost", "root", "", "project1");

// ✅ Fetch all registered users (Farmers & Bidders)
$sql_users = "SELECT email, full_name, city, pincode, state, country, role, photo FROM registration";
$result_users = $conn->query($sql_users);

// ✅ Fetch verified products
$sql_verified = "SELECT p_id, p_name, farmer_email FROM products WHERE status='verified'";
$result_verified = $conn->query($sql_verified);

// ✅ Fetch sold products
$sql_sold = "SELECT products.p_id, products.p_name, products.farmer_email, 
                    bill.bidder_email, bill.bid_amount AS final_price 
             FROM products 
             JOIN bill ON products.p_id = bill.product_id 
             WHERE products.status='sold'";
$result_sold = $conn->query($sql_sold);

// ✅ Fetch rejected products
$sql_rejected = "SELECT p_id, p_name, farmer_email FROM products WHERE status='rejected'";
$result_rejected = $conn->query($sql_rejected);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    

    <style>
    :root {
        --primary-color: rgb(31, 220, 110);
        --secondary-color: #3498db;
        --accent-color:rgb(31, 220, 110);
        --light-bg: #f9f9f9;
        --hover-bg: #f1f1f1;
    }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: #f0f3f5;
        margin: 0;
        padding: 20px;
        line-height: 1.6;
    }

    .container {
        width: 95%;
        max-width: 1200px;
        margin: 20px auto;
        background: #fff;
        padding: 30px;
        border-radius: 15px;
        box-shadow: 0 5px 25px rgba(0,0,0,0.1);
    }

    h2 {
        text-align: center;
        color: var(--primary-color);
        font-size: 2.5em;
        margin-bottom: 40px;
        padding-bottom: 15px;
        border-bottom: 3px solid var(--accent-color);
    }

    h3 {
        color: var(--secondary-color);
        margin: 40px 0 25px;
        font-size: 1.8em;
        position: relative;
        padding-left: 15px;
    }

    h3::before {
        content: '';
        position: absolute;
        left: 0;
        bottom: 2px;
        height: 3px;
        width: 50px;
        background: var(--accent-color);
    }

    .table-wrapper {
        overflow-x: auto;
        margin-bottom: 40px;
        border-radius: 10px;
        box-shadow: 0 2px 15px rgba(0,0,0,0.1);
    }

    table {
        width: 100%;
        border-collapse: collapse;
        background: white;
        min-width: 600px;
    }

    th, td {
        padding: 15px;
        text-align: center;
        border-bottom: 1px solid #e0e0e0;
    }

    th {
        background: var(--primary-color);
        color: white;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.9em;
        letter-spacing: 0.5px;
    }

    tr:nth-child(even) {
        background-color: var(--light-bg);
    }

    tr:hover {
        background-color: var(--hover-bg);
        transition: background 0.3s ease;
    }

    td img {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #ddd;
        transition: transform 0.3s ease;
    }

    td img:hover {
        transform: scale(1.1);
    }

    @media (max-width: 768px) {
        .container {
            padding: 15px;
            width: 97%;
        }

        h2 {
            font-size: 2em;
        }

        h3 {
            font-size: 1.5em;
        }

        th, td {
            padding: 12px;
            font-size: 0.9em;
        }
    }

    p {
            color: #1a73e8;
            font-size: 24px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
    
        form {
            max-width: 400px;
            margin: 0 auto 30px;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
    
        select {
            width: 100%;
            padding: 12px 15px;
            font-size: 16px;
            border: 2px solid #1a73e8;
            border-radius: 6px;
            background-color: white;
            color: #202124;
            appearance: none;
            transition: all 0.3s ease;
        }
    
        select:hover {
            border-color: #1557b0;
            box-shadow: 0 2px 8px rgba(26,115,232,0.2);
        }
    
        select:focus {
            outline: none;
            border-color: #1557b0;
            box-shadow: 0 3px 12px rgba(26,115,232,0.3);
        }
    
        #dsp {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            overflow-x: auto;
        }

        /* Add this to your existing CSS */
option {
    font-family: 'Montserrat', sans-serif;
    font-size: 1em;
    padding: 15px 20px;
    margin: 5px 0;
    background: #ffffff;
    color: var(--primary-color);
    transition: all 0.2s ease;
    cursor: pointer;
    border-bottom: 1px solid #eee;
}

option:hover {
    background: var(--secondary-color) !important;
    color: white !important;
    transform: translateX(5px);
}

option:checked {
    background: var(--accent-color) !important;
    color: white !important;
    font-weight: 600;
    position: relative;
}

option:checked::after {
    content: "✔";
    position: absolute;
    right: 15px;
    color: white;
}

select option {
    font-size: 0.95em;
    letter-spacing: 0.5px;
    border-radius: 4px;
}

select:focus option:checked {
    background: var(--accent-color);
    color: white;
}

/* Fancy dropdown animation */
select {
    transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

select:hover {
    transform: scale(1.02);
}

/* Dropdown scrollbar styling (for webkit browsers) */
select::-webkit-scrollbar {
    width: 8px;
}

select::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}

select::-webkit-scrollbar-thumb {
    background: var(--secondary-color);
    border-radius: 4px;
}

select::-webkit-scrollbar-thumb:hover {
    background: var(--accent-color);
}

        @media screen and (max-width: 600px) {
            body {
                padding: 20px 10px;
            }
            
            p {
                font-size: 20px;
            }
            
            form {
                max-width: 100%;
                padding: 15px;
            }
            
            select {
                font-size: 14px;
                padding: 10px 12px;
            }
            
        }
    
        /* Initial message styling */
        #dsp > b {
            display: block;
            text-align: center;
            padding: 30px;
            color: #5f6368;
            font-weight: normal;
        }

    @media (max-width: 480px) {
        body {
            padding: 10px;
        }

        .container {
            padding: 10px;
        }

        th, td {
            padding: 10px;
        }

        td img {
            width: 40px;
            height: 40px;
        }
    }

    .status-indicator {
        display: inline-block;
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 0.9em;
    }

    .verified { background: #e8f5e9; color: #2e7d32; }
    .sold { background: #fff3e0; color: #ef6c00; }
    .rejected { background: #ffebee; color: #c62828; }
</style>
<script>
        function show(str) {
            if (str == "") {
                document.getElementById("dsp").innerHTML = "";
                return;
            } else {
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {
                        document.getElementById("dsp").innerHTML = this.responseText;
                    }
                }
            };
            xmlhttp.open("GET", "ad_product.php?q=" + str, true);
            xmlhttp.send();
        }
    </script>
</head>
<body>

<?php include __DIR__ . '/includes/lang_selector.php'; ?>

<div class="container">
    <h2>Admin Dashboard</h2>
    <p>CHECK THE PRODUCT DETAILS</p>
    <form>
        <select onchange="show(this.value)">
            <option value="">select the productsp</option>
            <option value="rejected">rejected products</option>
            <option value="sold">sold products</option>
            <option value="verified">verified products</option>
          
        </select>
    </form>
    <div id="dsp"><b>THE PRODUCT DETAILS :</b></div>

    <!-- ✅ Registered Users -->
    <h3>Registered Users</h3>
    <table>
        <tr>
            <th>Email</th>
            <th>Name</th>
            <th>City</th>
            <th>Pincode</th>
            <th>State</th>
            <th>Country</th>
            <th>Role</th>
            <th>Photo</th>
        </tr>
        <?php while ($user = $result_users->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($user['email']); ?></td>
                <td><?php echo htmlspecialchars($user['full_name']); ?></td>
                <td><?php echo htmlspecialchars($user['city']); ?></td>
                <td><?php echo htmlspecialchars($user['pincode']); ?></td>
                <td><?php echo htmlspecialchars($user['state']); ?></td>
                <td><?php echo htmlspecialchars($user['country']); ?></td>
                <td><?php echo htmlspecialchars(ucfirst($user['role'])); ?></td>
                <td>
                    <?php if (!empty($user['photo'])): ?>
                        <img src="<?php echo htmlspecialchars($user['photo']); ?>" alt="User Photo" width="50">
                    <?php else: ?>
                        <span style="color: grey;">No Photo</span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>


   
     



</body>
</html>

<?php
$conn->close();
?>