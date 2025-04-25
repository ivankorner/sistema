<?php
session_start();
$isLoggedIn = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;

// Conexión a la base de datos
$host = 'localhost';
$dbname = 'nu';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error al conectar a la base de datos: " . $e->getMessage());
}

// Construir la consulta de búsqueda
$sql = "SELECT * FROM datos WHERE 1=1";
$params = [];

// Búsqueda general
if (!empty($_GET['global_search'])) {
    $globalSearch = '%' . $_GET['global_search'] . '%';
    $sql .= " AND (name LIKE :global_search 
                OR instrumento LIKE :global_search 
                OR year LIKE :global_search)";
    $params[':global_search'] = $globalSearch;
}

// Búsqueda específica por campos
if (!empty($_GET['name'])) {
    $sql .= " AND name LIKE :name";
    $params[':name'] = '%' . $_GET['name'] . '%';
}
if (!empty($_GET['instrumento'])) {
    $sql .= " AND instrumento = :instrumento";
    $params[':instrumento'] = $_GET['instrumento'];
}
if (!empty($_GET['year'])) {
    $sql .= " AND year = :year";
    $params[':year'] = $_GET['year'];
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados de Búsqueda</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
        }
        .container {
            margin-top: 5%;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004085;
        }
        .table {
            margin-top: 2rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="mb-4">Resultados de Búsqueda</h1>
        <?php if (empty($results)): ?>
            <p class="text-muted">No se encontraron resultados.</p>
        <?php else: ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Número de Instrumento</th>
                        <th>Tipo de Instrumento</th>
                        <th>Año</th>
                        <th>Archivo</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($results as $row): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['instrumento']); ?></td>
                            <td><?php echo htmlspecialchars($row['year']); ?></td>
                            <td>
                                <a href="<?php echo htmlspecialchars($row['file_path']); ?>" target="_blank" class="btn btn-primary btn-sm">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
        
        <a href="index.php" class="btn btn-secondary mt-3">Volver a la Búsqueda</a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>