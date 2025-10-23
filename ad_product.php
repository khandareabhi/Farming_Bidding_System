<?php
// $q = intval($_GET['q']);
$q = $_REQUEST['q'];

$server="localhost";
$user= "root";
$pass="";
$db="project1";

$con=mysqli_connect($server,$user,$pass,$db);

echo '<!DOCTYPE html>
<html>
<head>
    <title>Product Details</title>
    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
    }

    body {
        background: #f0f2f5;
        padding: 20px;
    }

    h2 {
        color: #1a73e8;
        margin-bottom: 25px;
        text-align: center;
        font-size: 28px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        overflow-x: auto;
        box-shadow: 0 1px 3px rgba(0,0,0,0.12);
        border-radius: 8px;
        background: white;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        background: white;
    }

    th, td {
        padding: 15px;
        text-align: left;
        border-bottom: 1px solid #e0e0e0;
    }

    th {
        background-color:rgb(68, 230, 24);
        color: white;
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    tr:nth-child(even) {
        background-color: #f8f9fa;
    }

    tr:hover {
        background-color: #f1f3f4;
        transform: scale(1.02);
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    td {
        color: #202124;
    }

    /* Previous styles remain the same */
    
    /* Status color coding */
    .status-rejected {
        background-color: #ffebee;
        color: #c62828;
        padding: 6px 12px;
        border-radius: 20px;
        display: inline-block;
        font-weight: 600;
    }

    .status-verified {
        background-color: #e8f5e9;
        color: #2e7d32;
        padding: 6px 12px;
        border-radius: 20px;
        display: inline-block;
        font-weight: 600;
    }

    .status-sold {
        background-color: #e3f2fd;
        color: #1565c0;
        padding: 6px 12px;
        border-radius: 20px;
        display: inline-block;
        font-weight: 600;
    }

    /* Modified table row hover effect */
    tr:hover td:not(:nth-child(3)) {
        background-color: #f5f5f5;
    }

    /* Keep status cell color visible on hover */
    tr:hover td:nth-child(3) {
        transform: scale(1.05);
        transition: transform 0.2s ease;
    }

    @media screen and (max-width: 600px) {
        th, td {
            padding: 12px;
            font-size: 14px;
        }
        
        h2 {
            font-size: 22px;
        }
        
        .container {
            border-radius: 4px;
        }
    }
    </style>
</head>
<body>

<?php include __DIR__ . '/includes/lang_selector.php'; ?>

<div class="container">';

$sql="select * from products where status='".$q."'";
$re=mysqli_query($con,$sql);

echo "<h2>Product Details</h2>
<table>
  <tr>
  <th>Product ID</th>
  <th>Product Name</th>
  <th>Timestamp</th>
  <th>quantity</th>

  <th>farmer_email</th>
  <th>min_bidding</th>
  <th>Status</th>
  </tr>
";

while($row = mysqli_fetch_array($re)){
  echo "<tr>";
  echo "<td>".$row['p_id']."</td>";
  echo "<td>".$row['p_name']."</td>";
  echo "<td>".$row['timestamp']."</td>";
 
  echo "<td>".$row['quantity']."</td>";
  echo "<td>".$row['farmer_email']."</td>";
  echo "<td>".$row['min_bidding']."</td>";
  echo "<td class='status-".$row['status']."'>".$row['status']."</td>";
  echo "</tr>";
}

echo "</table>
</div>
</body>
</html>";

mysqli_close($con);
?>