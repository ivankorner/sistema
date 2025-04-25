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
                <label for="descripcion" class="form-label">Titulo:</label>
                <textarea name="descripcion" id="descripcion" class="form-control" placeholder="Ingrese una descripción (máximo 140 caracteres)" maxlength="140" required></textarea>
            </div>


            <div class="mb-3">
                <label for="tema" class="form-label">Tema</label>
                <select name="tema" id="tema" class="form-select" required>
                <option value="">Seleccione un Tema</option>
                                    <option value="Acceso a la información pública">Acceso a la información pública</option>
                                    <option value="Adultos mayores">Adultos mayores</option>
                                    <option value="Agua potable">Agua potable</option>
                                    <option value="Alumbrado">Alumbrado</option>
                                    <option value="Animales">Animales</option>
                                    <option value="Armas">Armas</option>
                                    <option value="Calles">Calles</option>
                                    <option value="Casinos">Casinos</option>
                                    <option value="Cementerio">Cementerio</option>
                                    <option value="Colectivos">Colectivos</option>
                                    <option value="Comercios">Comercios</option>
                                    <option value="Comisión investigadora">Comisión investigadora</option>
                                    <option value="Cultura">Cultura</option>
                                    <option value="Deportes">Deportes</option>
                                    <option value="Desarrollo Social">Desarrollo Social</option>
                                    <option value="Discapacidad">Discapacidad</option>
                                    <option value="Diversidad sexual">Diversidad sexual</option>
                                    <option value="Educacion y Cultura">Educacion y Cultura</option>
                                    <option value="Espacio Público">Espacio Público</option>
                                    <option value="Espacio verde y arbolado">Espacio verde y arbolado</option>
                                    <option value="Estacionamiento Medido">Estacionamiento Medido</option>
                                    <option value="Hábitat">Hábitat</option>
                                    <option value="Hacienda y Finanzas">Hacienda y Finanzas</option>                    
                                    <option value="Higiene y Sanidad">Higiene y Sanidad</option>
                                    <option value="Hogar de ancianos">Hogar de ancianos</option>
                                    <option value="Información Pública">Información Pública</option>
                                    <option value="Iniciativa comunitaria, coop. de trabajo y vecinales">Iniciativa comunitaria, coop. de trabajo y vecinales</option>
                                    <option value="Medio ambiente">Medio ambiente</option>
                                    <option value="Niñez, adolescencia y familia">Niñez, adolescencia y familia</option>
                                    <option value="Obras Privadas">Obras Privadas</option>
                                    <option value="Obras Publicas">Obras Publicas</option>
                                    <option value="Personal municipal">Personal municipal</option>
                                    <option value="Producción">Producción</option>
                                    <option value="Recursos Hidricos">Recursos Hidricos</option>
                                    <option value="Reglamento de urbanizacion y subdivisiones">Reglamento de urbanizacion y subdivisiones</option>
                                    <option value="Reglamento de zonificación">Reglamento de zonificación</option>
                                    <option value="Relaciones públicas y ceremonial">Relaciones públicas y ceremonial</option>
                                    <option value="Residuos">Residuos</option>
                                    <option value="Salud">Salud</option>
                                    <option value="Seguridad">Seguridad</option>
                                    <option value="Sin tema">Sin tema</option>
                                    <option value="Taxis y remises">Taxis y remises</option>
                                    <option value="Tecnologia">Tecnologia</option>
                                    <option value="Transito y seguridad Vial">Transito y seguridad Vial</option>
                                    <option value="Transporte">Transporte</option>
                                    <option value="Turismo">Turismo</option>
                </select>
            </div>

            
            <div class="mb-3">
                <label for="year" class="form-label">Año:</label>
                <input type="text" name="year" id="year" class="form-control" placeholder="Ingrese el año" required>
            </div>
            <div class="mb-3">
                <label for="tema" class="form-label">Autor</label>
                <select name="tema" id="tema" class="form-select" required>
                <option value="">Seleccione un Tema</option>
                <option value="">Seleccione un Autor</option>
                                    <option value="Proyecto Concejal">Proyecto Concejal</option>
                                    <option value="Peticion Particular">Petición Particular</option>
                                    <option value="Petición del DEM">Petición del DEM</option>
                                    <option value="Comunicacion Oficial">Comunicación Oficial</option>
                </select>
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