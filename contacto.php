<?php
$tituloPagina = 'Contacto';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Contacto · Pixel Vault</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&family=Rajdhani:wght@600;700&family=Work+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">

    <!-- Solo el estilo del logo (letra + tamaño del icono) -->
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
                        <a class="nav-link" href="servicios.php">Servicios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="contacto.php">Contacto</a>
                    </li>
                </ul>
            </div>
            <button type="button" class="btn btn-pv" data-bs-toggle="modal" data-bs-target="#myModal">Acceder</button>
        </div>
    </nav>

    <section class="pv-hero" style="padding:3rem 0 2.5rem;">
        <div class="container">
            <div class="pv-hero-eyebrow">Hablemos</div>
            <h1 style="font-size:clamp(2rem,4vw,3rem);">¿Buscas un <span>título</span> o quieres vender el tuyo?</h1>
            <p class="lead">Escríbenos y te respondemos dentro del día hábil siguiente.</p>
        </div>
    </section>

    <section class="pv-section">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-7">
                    <div class="pv-form-card">
                        <form action="empresa.php" method="get">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email:</label>
                                <input type="email" class="form-control" id="email" placeholder="tu@email.com" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="asunto" class="form-label">Motivo:</label>
                                <select class="form-control" id="asunto" name="asunto">
                                    <option>Consulta por un juego o consola</option>
                                    <option>Quiero vender mi colección</option>
                                    <option>Reparación técnica</option>
                                    <option>Estado de un pedido</option>
                                    <option>Otro</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="comment" class="form-label">Comentarios:</label>
                                <textarea class="form-control" rows="5" id="comment" name="text" placeholder="Cuéntanos qué buscas o qué quieres vender..."></textarea>
                            </div>
                            <button type="submit" class="btn btn-pv mt-1">Enviar mensaje</button>
                        </form>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="pv-card mb-4">
                        <div class="pv-card-body">
                            <h3><i class="fa fa-map-marker" style="color:var(--magenta);"></i> Tienda</h3>
                            <p style="color:var(--text-dim);">Av. Pajaritos 3200, Maipú, Santiago</p>
                            <h3 class="mt-3"><i class="fa fa-clock-o" style="color:var(--cyan);"></i> Horario</h3>
                            <p style="color:var(--text-dim);">Lunes a sábado, 11:00 – 20:00</p>
                            <h3 class="mt-3"><i class="fa fa-phone" style="color:var(--amber);"></i> Teléfono</h3>
                            <p style="color:var(--text-dim);">+56 9 1234 5678</p>
                        </div>
                    </div>
                </div>
            </div>
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