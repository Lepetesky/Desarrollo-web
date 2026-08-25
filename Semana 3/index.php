<?php
$tituloPagina = 'Inicio';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Inicio · Pixel Vault</title>
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
                        <a class="nav-link" href="contacto.php">Contacto</a>
                    </li>
                </ul>
            </div>
            <button type="button" class="btn btn-pv" data-bs-toggle="modal" data-bs-target="#myModal">Acceder</button>
        </div>
    </nav>

    <!-- Hero -->
    <section class="pv-hero">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <div class="pv-hero-eyebrow">Física. Original. Coleccionable.</div>
                    <h1>Tu próximo <span>cartucho</span> te espera en la estantería.</h1>
                    <p class="lead">Compra y venta de videojuegos físicos y consolas, nuevos y usados. Cada caja revisada, cada consola testeada antes de llegar a tus manos.</p>
                    <div class="d-flex gap-2 mt-4 flex-wrap">
                        <a href="productos.php" class="btn btn-pv btn-lg">Ver catálogo</a>
                        <a href="servicios.php" class="btn btn-pv-outline btn-lg">Vender mis juegos</a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="pv-shelf">
                        <div class="pv-cart" style="height:78%; background:linear-gradient(160deg,#ff3e7f,#c22a5c);"></div>
                        <div class="pv-cart" style="height:95%; background:linear-gradient(160deg,#2fe6e6,#1f9d9d);"></div>
                        <div class="pv-cart" style="height:65%; background:linear-gradient(160deg,#ffc145,#c78e1f);"></div>
                        <div class="pv-cart" style="height:88%; background:linear-gradient(160deg,#6a5cff,#4536b3);"></div>
                        <div class="pv-cart" style="height:60%; background:linear-gradient(160deg,#ff3e7f,#c22a5c);"></div>
                        <div class="pv-cart" style="height:100%; background:linear-gradient(160deg,#2fe6e6,#1f9d9d);"></div>
                        <div class="pv-cart" style="height:72%; background:linear-gradient(160deg,#ffc145,#c78e1f);"></div>
                        <div class="pv-cart" style="height:82%; background:linear-gradient(160deg,#6a5cff,#4536b3);"></div>
                    </div>
                    <div class="pv-shelf-base"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Franja de stats -->
    <section class="pv-strip">
        <div class="container-fluid">
            <div class="row">
                <div class="col-6 col-md-3 pv-stat">
                    <div class="num">+3.200</div>
                    <div class="lbl">Títulos en stock</div>
                </div>
                <div class="col-6 col-md-3 pv-stat">
                    <div class="num">12</div>
                    <div class="lbl">Generaciones de consola</div>
                </div>
                <div class="col-6 col-md-3 pv-stat">
                    <div class="num">100%</div>
                    <div class="lbl">Testeados antes de vender</div>
                </div>
                <div class="col-6 col-md-3 pv-stat">
                    <div class="num">7 días</div>
                    <div class="lbl">Garantía de cambio</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Accesos rápidos -->
    <section class="pv-section">
        <div class="container">
            <h2 class="pv-section-title">Explora la tienda</h2>
            <p class="pv-section-sub">Un mapa rápido de todo lo que encuentras acá.</p>
            <div class="row g-4">
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="pv-card">
                        <div class="pv-card-art" style="background:linear-gradient(160deg,#ff3e7f,#c22a5c);"><i class="fa fa-building"></i></div>
                        <div class="pv-card-body">
                            <h3>Empresa</h3>
                            <p class="pv-card-meta">Quiénes somos, equipo y misión.</p>
                            <a href="empresa.php">Ir a Empresa →</a>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="pv-card">
                        <div class="pv-card-art" style="background:linear-gradient(160deg,#2fe6e6,#1f9d9d);"><i class="fa fa-shopping-bag"></i></div>
                        <div class="pv-card-body">
                            <h3>Productos</h3>
                            <p class="pv-card-meta">Juegos y consolas disponibles hoy.</p>
                            <a href="productos.php">Ir a Productos →</a>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="pv-card">
                        <div class="pv-card-art" style="background:linear-gradient(160deg,#ffc145,#c78e1f);"><i class="fa fa-wrench"></i></div>
                        <div class="pv-card-body">
                            <h3>Servicios</h3>
                            <p class="pv-card-meta">Compra de usados, reparación, envíos.</p>
                            <a href="servicios.php">Ir a Servicios →</a>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="pv-card">
                        <div class="pv-card-art" style="background:linear-gradient(160deg,#6a5cff,#4536b3);"><i class="fa fa-envelope"></i></div>
                        <div class="pv-card-body">
                            <h3>Contacto</h3>
                            <p class="pv-card-meta">Escríbenos, resolvemos rápido.</p>
                            <a href="contacto.php">Ir a Contacto →</a>
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
