<?php
// Conexión a la base de datos
$host = 'localhost';
$dbname = 'nu';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error al conectar con la base de datos: " . $e->getMessage());
}

// Procesar el formulario
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $instrumento = isset($_POST['instrumento']) ? trim($_POST['instrumento']) : '';
    $year = isset($_POST['year']) ? trim($_POST['year']) : '';
    $descripcion = isset($_POST['descripcion']) ? trim($_POST['descripcion']) : '';
    $pdfFile = $_FILES['pdf_file'] ?? null;
    $anexos = $_FILES['anexos'] ?? null;

    if (!empty($name) && !empty($instrumento) && !empty($year) && !empty($descripcion) && $pdfFile) {
        // Verificar si el archivo principal es un PDF
        if ($pdfFile['type'] === 'application/pdf') {
            // Guardar el archivo principal
            $uploadDir = 'uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $filePath = $uploadDir . basename($pdfFile['name']);
            if (move_uploaded_file($pdfFile['tmp_name'], $filePath)) {
                // Insertar los datos principales en la base de datos
                $sql = "INSERT INTO datos (name, instrumento, year, descripcion, file_path) 
                        VALUES (:name, :instrumento, :year, :descripcion, :file_path)";
                $stmt = $pdo->prepare($sql);

                try {
                    $stmt->execute([
                        ':name' => $name,
                        ':instrumento' => $instrumento,
                        ':year' => $year,
                        ':descripcion' => $descripcion,
                        ':file_path' => $filePath,
                    ]);
                    $expedienteId = $pdo->lastInsertId();

                    // Procesar anexos si existen
                   // Procesar anexos si existen
                   if ($anexos && $anexos['error'][0] === UPLOAD_ERR_OK) {
                    foreach ($anexos['name'] as $index => $anexoName) {
                        $anexoPath = $uploadDir . basename($anexos['name'][$index]);
                        if (move_uploaded_file($anexos['tmp_name'][$index], $anexoPath)) {
                            $sqlAnexo = "INSERT INTO anexos (expediente_id, file_path) VALUES (:expediente_id, :file_path)";
                            $stmtAnexo = $pdo->prepare($sqlAnexo);
                            $stmtAnexo->execute([
                                ':expediente_id' => $expedienteId,
                                ':file_path' => $anexoPath,
                            ]);
                        }
                    }
                }

                    $message = 'Datos cargados exitosamente.';
                } catch (PDOException $e) {
                    $message = 'Error al cargar los datos: ' . $e->getMessage();
                }
            } else {
                $message = 'Error al subir el archivo principal.';
            }
        } else {
            $message = 'Por favor, suba un archivo PDF válido.';
        }
    } else {
        $message = 'Por favor, complete todos los campos.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carga de Datos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
        }
        .navbar {
            background-color: #343a40;
        }
        .navbar-brand {
            color: #ffffff;
            font-weight: bold;
        }
        .navbar-brand:hover {
            color: #ffc107;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004085;
        }
        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }
        .btn-secondary:hover {
            background-color: #5a6268;
            border-color: #545b62;
        }
        .btn-warning {
            background-color: #ffc107;
            border-color: #ffc107;
        }
        .btn-warning:hover {
            background-color: #e0a800;
            border-color: #d39e00;
        }
        footer {
            background-color: #343a40;
            color: #ffffff;
            padding: 1rem 0;
            text-align: center;
            margin-top: 2rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <a class="navbar-brand" href="#">Instrumentos</a>
        <?php if ($isLoggedIn): ?>
            <span class="text-white ms-auto">Bienvenido, <?php echo htmlspecialchars($_SESSION['username']); ?>!</span>
        <?php endif; ?>
    </div>

    <div class="container">
        <h1 class="mb-4">Carga de Datos</h1>

        <?php if (!empty($message)): ?>
            <div class="alert alert-info"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <form action="" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="name" class="form-label">Número:</label>
                <input type="text" name="name" id="name" class="form-control" placeholder="Ingrese el número del instrumento" required>
            </div>
            <div class="mb-3">
                <label for="instrumento" class="form-label">Instrumento:</label>
                <select name="instrumento" id="instrumento" class="form-select" required>
                    <option value="">Seleccione un instrumento</option>
                    <option value="Ordenanza">Ordenanza</option>
                    <option value="Resolucion">Resolución</option>
                    <option value="Declaracion">Declaración</option>
                    <option value="Comunicacion">Comunicación</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="year" class="form-label">Año:</label>
                <input type="text" name="year" id="year" class="form-control" placeholder="Ingrese el año" required>
            </div>
            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción:</label>
                <textarea name="descripcion" id="descripcion" class="form-control" placeholder="Ingrese una descripción (máximo 140 caracteres)" maxlength="140" required></textarea>
            </div>
            <div class="mb-3">
                <label for="pdf_file" class="form-label">Archivo PDF:</label>
                <input type="file" name="pdf_file" id="pdf_file" class="form-control" accept="application/pdf" required>
            </div>
            <div class="mb-3">
                <input type="checkbox" name="hasAnexos" id="hasAnexos" onclick="toggleAnexos()">
                <label for="hasAnexos" class="form-label">¿El expediente tiene anexos?</label>
            </div>
            <div id="anexosSection" style="display: none;">
                <div class="mb-3">
                    <label for="anexos" class="form-label">Cargar Anexos (puede seleccionar varios archivos):</label>
                    <input type="file" name="anexos[]" id="anexos" class="form-control" accept="application/pdf" multiple>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Cargar Datos</button>
        </form>

        <a href="index.php" class="btn btn-secondary mt-3">Volver</a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleAnexos() {
            const anexosSection = document.getElementById('anexosSection');
            const hasAnexosCheckbox = document.getElementById('hasAnexos');
            anexosSection.style.display = hasAnexosCheckbox.checked ? 'block' : 'none';
        }
    </script>
</body>
</html>