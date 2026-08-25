<?php
$tituloPagina = 'Servicios';

$servicios = [
    [
        'icono' => 'fa-refresh',
        'color1' => '#ff3e7f', 'color2' => '#c22a5c',
        'titulo' => 'Compra de usados',
        'texto'  => '¿Tienes juegos o consolas que ya no usas? Tráelos y evaluamos su valor . ¡Vende tus juegos, gana dinero y dale una segunda vida a tus juegos!',
    ],
    [
        'icono' => 'fa-wrench',
        'color1' => '#2fe6e6', 'color2' => '#1f9d9d',
        'titulo' => 'Reparación técnica',
        'texto'  => 'Mantenimiento y reparación de consolas de nueva y antigua generación. ¿Tu consola tiene alguna falla? ¡Contáctanos! No dudes en consultarnos, estamos para ayudarte.',
    ],
    [
        'icono' => 'fa-shield',
        'color1' => '#ffc145', 'color2' => '#c78e1f',
        'titulo' => 'Garantía extendida',
        'texto'  => '7 días de cambio directo en usados y 12 meses de garantía por fallas de fábrica en productos nuevos.',
    ],
    [
        'icono' => 'fa-truck',
        'color1' => '#6a5cff', 'color2' => '#4536b3',
        'titulo' => 'Envíos a todo Chile',
        'texto'  => 'Despacho a tu casa, ¡enviamos a todo Chile! No importa dónde estés, tus juegos y consolas llegarán de forma segura hasta tu puerta.',
    ],
    [
        'icono' => 'fa-archive',
        'color1' => '#ff3e7f', 'color2' => '#c22a5c',
        'titulo' => 'Encargos y reservas',
        'texto'  => 'Reserva próximos lanzamientos físicos o encárganos un título de tu maravillosa colección .',
    ],
    [
        'icono' => 'fa-certificate',
        'color1' => '#2fe6e6', 'color2' => '#1f9d9d',
        'titulo' => 'Tasación de colecciones',
        'texto'  => '¿Tienes una colección grande? Vamos hasta tu domicilio, revisamos y evaluamos tus juegos, consolas y accesorios. Nos encargamos de todo para que puedas vender tu colección de forma fácil y segura.',
    ],
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Servicios · Pixel Vault</title>
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
                        <a class="nav-link dropdown-toggle" href="empresa.php" role="button" data-bs-toggle="dropdown">Empresa</a>
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
                        <a class="nav-link active" href="servicios.php">Servicios</a>
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
            <div class="pv-hero-eyebrow">Más que una vitrina</div>
            <h1 style="font-size:clamp(2rem,4vw,3rem);">Todo lo que tu <span>colección</span> necesita.</h1>
            <p class="lead">Compramos, reparamos, garantizamos y despachamos. Estos son los servicios que ofrecemos además de la venta en tienda.</p>
        </div>
    </section>

    <section class="pv-section">
        <div class="container">
            <div class="row g-4">
                <?php foreach ($servicios as $s): ?>
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="pv-card">
                        <div class="pv-card-art" style="background:linear-gradient(160deg,<?php echo $s['color1']; ?>,<?php echo $s['color2']; ?>); font-size:1.8rem;">
                            <i class="fa <?php echo $s['icono']; ?>"></i>
                        </div>
                        <div class="pv-card-body">
                            <h3><?php echo htmlspecialchars($s['titulo']); ?></h3>
                            <p style="color:var(--text-dim); font-size:.92rem;"><?php echo htmlspecialchars($s['texto']); ?></p>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <a href="index.php" class="btn btn-pv-outline mt-4">← Volver al inicio</a>
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
