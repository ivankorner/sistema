
<?php
session_start();
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['csv_file']) && $_FILES['csv_file']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['csv_file']['tmp_name'];
        $fileName = $_FILES['csv_file']['name'];
        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

        if ($fileExtension === 'csv') {
            // Conexión a la base de datos
            $host = 'localhost';
            $dbname = 'nu';
            $username = 'root';
            $password = '';

            try {
                $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // Leer el archivo CSV
                $file = fopen($fileTmpPath, 'r');
                $header = fgetcsv($file); // Leer la primera fila como encabezado

                while (($row = fgetcsv($file)) !== false) {
                    // Mapear los datos del CSV a las columnas de la base de datos
                    $name = $row[0];
                    $instrumento = $row[1];
                    $year = $row[2];
                    $descripcion = $row[3];
                    $file_path = $row[4]; // Ruta del archivo (si aplica)

                    // Insertar los datos en la base de datos
                    $sql = "INSERT INTO datos (name, instrumento, year, descripcion, file_path) 
                            VALUES (:name, :instrumento, :year, :descripcion, :file_path)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([
                        ':name' => $name,
                        ':instrumento' => $instrumento,
                        ':year' => $year,
                        ':descripcion' => $descripcion,
                        ':file_path' => $file_path,
                    ]);
                }

                fclose($file);
                $message = 'Datos importados exitosamente.';
            } catch (PDOException $e) {
                $message = 'Error al importar los datos: ' . $e->getMessage();
            }
        } else {
            $message = 'Por favor, suba un archivo CSV válido.';
        }
    } else {
        $message = 'Por favor, seleccione un archivo para importar.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Importar Datos desde CSV</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Importar Datos desde CSV</h1>

        <?php if (!empty($message)): ?>
            <div class="alert alert-info"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <form action="" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="csv_file" class="form-label">Seleccione un archivo CSV:</label>
                <input type="file" name="csv_file" id="csv_file" class="form-control" accept=".csv" required>
            </div>
            <button type="submit" class="btn btn-primary">Importar</button>
        </form>

        <a href="busqueda.php" class="btn btn-secondary mt-3">Ir a la Búsqueda</a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>