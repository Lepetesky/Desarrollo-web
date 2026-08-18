<?php
$tituloPagina = 'Empresa';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Empresa · Pixel Vault</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&family=Rajdhani:wght@600;700&family=Work+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">

    <style>
        .pv-logo-texto {
            font-family: 'Press Start 2P', cursive;
            font-size: 0.8rem;
        }
        .pv-logo-icono {
            height: 24px;
            vertical-align: middle;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-sm pv-navbar">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <img src="img/bloque-pregunta.png" alt="Pixel Vault" class="pv-logo-icono">
                <span class="pv-logo-texto">PIXEL VAULT</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="collapsibleNavbar">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle active" href="empresa.php" role="button" data-bs-toggle="dropdown">Empresa</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="empresa.php#quienes-somos">Quiénes Somos</a></li>
                            <li><a class="dropdown-item" href="empresa.php#equipo">Nuestro Equipo</a></li>
                            <li><a class="dropdown-item" href="empresa.php#mision">Misión</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="productos.php">Productos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="servicios.php">Servicios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contacto.php">Contacto</a>
                    </li>
                </ul>
            </div>
            <button type="button" class="btn btn-pv" data-bs-toggle="modal" data-bs-target="#myModal">Acceder</button>
        </div>
    </nav>

    <section class="pv-hero" style="padding:3rem 0 2.5rem;">
        <div class="container">
            <h1 style="font-size:clamp(2rem,4vw,3rem);">Un punto de encuentro para quienes prefieren tener el juego en la mano, no solo en la nube.</h1>
        </div>
    </section>

    <section class="pv-section" id="quienes-somos">
        <div class="container">
            <div class="row g-5 align-items-center">
                <div class="col-lg-6">
                    <h2 class="pv-section-title">Quiénes somos</h2>
                    <p>Somos un equipo que compra, vende y reparamos, con un enfoque en la conservación del formato físico.</p>
                    <p>Trabajamos con títulos nuevos sellados y con usados certificados, cada uno con su ficha de estado real: caja, manual, disco o cartucho.</p>
                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="pv-strip" id="equipo">
        <div class="container pv-section">
            <h2 class="pv-section-title">Nuestro equipo</h2>
            <p class="pv-section-sub">Las personas detrás del mesón.</p>
            <div class="row g-4">
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="pv-card">
                        <div class="pv-card-art" style="background:linear-gradient(160deg,#ff3e7f,#c22a5c); padding:0;">
                            <img src="img/nicolas-lopez.png" alt="Nicolas Lopez" style="width:100%;height:100%;object-fit:cover;">
                        </div>
                        <div class="pv-card-body">
                            <h3>Nicolas Lopez</h3>
                            <p class="pv-card-meta">Atención y envíos</p>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="pv-card">
                        <div class="pv-card-art" style="background:linear-gradient(160deg,#2fe6e6,#1f9d9d); padding:0;">
                            <img src="img/renato-gallardo.png" alt="Renato Gallardo" style="width:100%;height:100%;object-fit:cover;">
                        </div>
                        <div class="pv-card-body">
                            <h3>Renato Gallardo</h3>
                            <p class="pv-card-meta">Servicio técnico</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="pv-section" id="mision">
        <div class="container">
            <div class="row g-5">
                <div class="col-md-6">
                    <h2 class="pv-section-title">Misión</h2>
                    <p>Mantener vivo el formato físico del videojuego, ofreciendo un catálogo confiable, revisado y con precio justo tanto para quien compra como para quien vende su colección.</p>
                </div>
                <div class="col-md-6">
                    <h2 class="pv-section-title">Visión</h2>
                    <p>Ser el referente nacional en compra, venta y conservación de videojuegos y consolas físicas, dentro y fuera de línea.</p>
                </div>
            </div>
            <a href="index.php" class="btn btn-pv-outline mt-3">← Volver al inicio</a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="pv-footer">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-12 col-sm-4">
                    <img src="img/bloque-pregunta.png" alt="Pixel Vault" class="pv-logo-icono">
                    <strong class="pv-logo-texto">PIXEL VAULT</strong>
                </div>
                <div class="col-12 col-sm-4 text-sm-center">
                    Videojuegos físicos &amp; consolas · Santiago, Chile
                </div>
                <div class="col-12 col-sm-4 text-sm-end">
                    &copy; 2026 Pixel Vault
                </div>
            </div>
        </div>
    </footer>

    <!-- Modal de acceso -->
    <div class="modal fade" id="myModal">
        <div class="modal-dialog">
            <div class="modal-content pv-modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Autenticación</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="empresa.php">
                        <div class="mb-3 mt-2">
                            <label for="email" class="form-label">Email:</label>
                            <input type="email" class="form-control" id="email" placeholder="tu@email.com" name="email">
                        </div>
                        <div class="mb-3">
                            <label for="pwd" class="form-label">Contraseña:</label>
                            <input type="password" class="form-control" id="pwd" placeholder="••••••••" name="pswd">
                        </div>
                        <div class="form-check mb-3">
                            <label class="form-check-label">
                                <input class="form-check-input" type="checkbox" name="remember"> Recordarme
                            </label>
                        </div>
                        <button type="submit" class="btn btn-pv">Ingresar</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-pv-outline" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>