<?php
session_start();
$isLoggedIn = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SISTEMA DE INFORMACIÓN LEGISLATIVA</title>
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
            <a class="navbar-brand">SISTEMA DE INFORMACIÓN LEGISLATIVA</a>
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
                            <label for="global_search" class="form-label">Contiene</label>
                            <input type="text" name="global_search" id="global_search" class="form-control search-main" placeholder="Contiene texto o número">
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="name" class="form-label search-secondary">Número</label>
                                <input type="text" name="name" id="name" class="form-control search-secondary" placeholder="Número del expediente">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="instrumento" class="form-label search-secondary">Tema</label>
                                <select name="instrumento" id="instrumento" class="form-select search-secondary">
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
                            <div class="col-md-4 mb-3">
                                <label for="instrumento" class="form-label search-secondary">Autor</label>
                                <select name="instrumento" id="instrumento" class="form-select search-secondary">
                                    <option value="">Seleccione un Autor</option>
                                    <option value="Proyecto Concejal">Proyecto Concejal</option>
                                    <option value="Peticion Particular">Petición Particular</option>
                                    <option value="Petición del DEM">Petición del DEM</option>
                                    <option value="Comunicacion Oficial">Comunicación Oficial</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="year" class="form-label search-secondary">Año</label>
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
            <?php if (!$isLoggedIn): ?>
                <a href="login.php" class="btn btn-secondary">Iniciar Sesión</a>
            <?php else: ?>
                <a href="logout.php" class="btn btn-danger">Cerrar Sesión</a>
            <?php endif; ?>
        </div>
    </div>

    <footer>
        <p>&copy; 2025 Instrumentos. Todos los derechos reservados.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>