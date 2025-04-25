<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Búsqueda de Instrumentos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
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
        .search-main {
            font-size: 1.5rem;
            padding: 1rem;
            border-radius: 10px;
            border: 1px solid #ced4da;
        }
        .search-secondary {
            font-size: 0.9rem;
            border-radius: 5px;
        }
        .form-label {
            font-weight: bold;
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
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Instrumentos</a>
            <?php if ($isLoggedIn): ?>
                <span class="text-white ms-auto">Bienvenido, <?php echo htmlspecialchars($_SESSION['username']); ?>!</span>
            <?php endif; ?>
        </div>
    </nav>

    <div class="container mt-5">
        <?php if ($isLoggedIn): ?>
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h1 class="mb-4">Cargar Datos</h1>
                    <a href="carga_datos.php" class="btn btn-warning mt-3">Carga de datos</a>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <h1 class="mb-4">Búsqueda de Datos</h1>
                    <a href="busqueda.php" class="btn btn-secondary mt-3">Búsqueda</a>
                </div>
            </div>
        <?php else: ?>
            <div class="card shadow-sm">
                <div class="card-body">
                    <h1 class="mb-4">Búsqueda</h1>
                    <form action="resultados.php" method="get">
                        <div class="mb-4">
                            <label for="global_search" class="form-label">Búsqueda General:</label>
                            <input type="text" name="global_search" id="global_search" class="form-control search-main" placeholder="Ingrese cualquier texto o número">
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="name" class="form-label search-secondary">Número</label>
                                <input type="text" name="name" id="name" class="form-control search-secondary" placeholder="Número del instrumento">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="instrumento" class="form-label search-secondary">Instrumento:</label>
                                <select name="instrumento" id="instrumento" class="form-select search-secondary">
                                    <option value="">Seleccione un instrumento</option>
                                    <option value="Ordenanza">Ordenanza</option>
                                    <option value="Resolucion">Resolución</option>
                                    <option value="Declaracion">Declaración</option>
                                    <option value="Comunicacion">Comunicación</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="year" class="form-label search-secondary">Año:</label>
                                <select name="year" id="year" class="form-select search-secondary">
                                    <option value="">Seleccione un año</option>
                                    <?php
                                    $currentYear = date('Y');
                                    for ($year = 1973; $year <= $currentYear; $year++) {
                                        echo "<option value=\"$year\">$year</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Buscar</button>
                    </form>
                </div>
            </div>
        <?php endif; ?>

        <div class="mt-4">
        <a href="index.php" class="btn btn-secondary mt-3">Volver a la Búsqueda</a>
        </div>
    </div>

    <footer>
        <p>&copy; 2025 Instrumentos. Todos los derechos reservados.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>