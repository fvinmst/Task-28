<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php"); 
    exit;
}

include 'connect.php';

$limit = 11; 

$totalDataQuery = $conn->query("SELECT COUNT(*) FROM dataorang");
$totalData = $totalDataQuery->fetchColumn();
$totalPages = ceil($totalData / $limit);

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $limit; 

//sorting
$columns = array('ID', 'Nama', 'nik');
$sort = isset($_GET['sort']) && in_array($_GET['sort'], $columns) ? $_GET['sort'] : 'ID';
$order = isset($_GET['order']) && $_GET['order'] == 'desc' ? 'DESC' : 'ASC';

$stmt = $conn->prepare("SELECT * FROM dataorang ORDER BY $sort $order LIMIT :limit OFFSET :offset");
$stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$dataorangRecords = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>fvin</title>
    <style>
        body {
    font-family: 'Arial', sans-serif;
    background-color: #f0f4f8;
    margin: 0;
    padding: 0;
}

h1 {
    text-align: center;
    color: #345771;
    margin-top: 20px;
    font-size: 36px;
    font-weight: bold;
    text-transform: uppercase;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
}

table {
    border-collapse: collapse;
    width: 80%;
    margin: 20px auto;
    background-color: white;
    border-radius: 10px;
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

th, td {
    padding: 15px;
    text-align: left;
    font-size: 16px;
}

th {
    background-color: #345771;
    color: white;
    font-weight: bold;
    text-transform: uppercase;
}

th a {
    text-decoration: none;
    color: white;
    transition: color 0.3s ease;
}

th a:hover {
    color: #ffcc00; /* Warna kuning saat hover */
}

table tr:nth-child(odd) {
    background-color: #f8f8f8;
}

table tr:nth-child(even) {
    background-color: #e0e7ee;
}

table tr:hover {
    background-color: #f5f5f5;
    cursor: pointer;
}

.morgenasi {
    margin-top: 20px;
    text-align: center;
}

.morgenasi a, .morgenasi strong {
    margin: 0 5px;
    padding: 10px 20px;
    background-color: #345771;
    color: white;
    text-decoration: none;
    font-size: 18px;
    border-radius: 5px;
    transition: background-color 0.3s ease, transform 0.2s;
}

.morgenasi a:hover {
    background-color: #ffcc00;
    transform: translateY(-2px); /* Sedikit efek mengangkat */
}

.morgenasi strong {
    background-color: #ffcc00;
    color: #345771;
    font-weight: bold;
}

.button {
    text-align: center;
    margin: 20px 0;
}

.button a {
    padding: 12px 25px;
    background-color: #ffcc00;
    color: #345771;
    text-decoration: none;
    font-size: 18px;
    border-radius: 30px;
    transition: background-color 0.3s ease, transform 0.2s;
}

.button a:hover {
    background-color: #e6b800;
    transform: translateY(-2px); /* Efek hover */
}

    </style>
</head>
<body>
    <h1>Human Resources</h1>
    <div class="button"><a href="logout.php">LOGOUT</a>
    </div>
    <table>
        <thead>
            <tr>
                <th><a href="index.php?sort=ID&order=<?php echo (isset($_GET['order']) && $_GET['order'] == 'asc') ? 'desc' : 'asc'; ?>">ID</a></th>
                <th><a href="index.php?sort=Nama&order=<?php echo (isset($_GET['order']) && $_GET['order'] == 'asc') ? 'desc' : 'asc'; ?>">Nama</a></th>
                <th><a href="index.php?sort=NIK&order=<?php echo (isset($_GET['order']) && $_GET['order'] == 'asc') ? 'desc' : 'asc'; ?>">NIK</a></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($dataorangRecords as $row): ?>
                <tr>
                    <td><?php echo $row['ID']; ?></td>
                    <td><?php echo $row['Nama']; ?></td>
                    <td><?php echo $row['nik']; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <?php   
    echo '<div class="morgenasi">';
    // b4math
    if ($page > 1) {
        echo '<a href="index.php?page=' . ($page - 1) . '">&lt;</a>';
    }
    // exist
    for ($i = 1; $i <= $totalPages; $i++) {
        if ($i == $page) {
            echo '<strong>' . $i . '</strong>';
        } else {
            echo '<a href="index.php?page=' . $i . '">' . $i . '</a>';        
        }
    }
    // aftermath
    if ($page < $totalPages) {
        echo '<a href="index.php?page=' . ($page + 1) . '">&gt;</a>';
    }    
    echo '</div>';  
    ?>
</body>
</html>