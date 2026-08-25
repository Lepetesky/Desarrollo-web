<?php
$tituloPagina = 'Productos';

// Catalogo de ejemplo. Reemplaza esto por una consulta a tu base de datos
// cuando conectes el sitio a un backend real.
// 'valor'  = precio en número puro (lo usa el JS para calcular el total)
// 'precio' = el mismo precio pero formateado para mostrarlo en pantalla
// 'stock'  = unidades disponibles (define cuánto se puede agregar como máximo)
$juegos = [
    ['titulo' => 'Forza Horizon 6',     'plataforma' => 'PS5',    'estado' => 'Nuevo', 'precio' => '$40.990', 'valor' => 40990, 'stock' => 8,  'color1' => '#e03326', 'color2' => '#1f9d9d', 'imagen' => 'img/juegos/forza.jpg'],
    ['titulo' => 'Cyberpunk 2077',      'plataforma' => 'Xbox',   'estado' => 'Nuevo', 'precio' => '$39.990', 'valor' => 39990, 'stock' => 10, 'color1' => '#e2df0d', 'color2' => '#4ae4e9', 'imagen' => 'img/juegos/cyberpunk.jpg'],
    ['titulo' => 'Minecraft',           'plataforma' => 'Xbox',   'estado' => 'Nuevo', 'precio' => '$14.990', 'valor' => 14990, 'stock' => 15, 'color1' => '#20c028', 'color2' => '#1fc7c7', 'imagen' => 'img/juegos/minecraft.jpg'],
    ['titulo' => 'Kingdom Hearts',      'plataforma' => 'Switch', 'estado' => 'Nuevo', 'precio' => '$29.990', 'valor' => 29990, 'stock' => 6,  'color1' => '#4d7af7', 'color2' => '#0f0f0f', 'imagen' => 'img/juegos/kingdom hearts.jpg'],
    ['titulo' => 'Phantom Blade',       'plataforma' => 'PS5',    'estado' => 'Nuevo', 'precio' => '$44.990', 'valor' => 44990, 'stock' => 5,  'color1' => '#070707', 'color2' => '#676d6d', 'imagen' => 'img/juegos/phantom blade.jpg'],
    ['titulo' => 'Saros',               'plataforma' => 'PS5',    'estado' => 'Nuevo', 'precio' => '$49.990', 'valor' => 49990, 'stock' => 4,  'color1' => '#dac553', 'color2' => '#eecd39', 'imagen' => 'img/juegos/saros.jpg'],
];

$consolas = [
    ['titulo' => 'Consola Playstation 5 Spiderman 2',              'plataforma' => 'Nueva generación',    'estado' => 'Nuevo', 'precio' => '$450.990', 'valor' => 450990, 'stock' => 3, 'color1' => '#e01f1f', 'color2' => '#030303', 'imagen' => 'img/consolas/spiderman2.jpg'],
    ['titulo' => 'Consola Nintendo Switch Edicion Animal Crossing', 'plataforma' => 'Híbrida',             'estado' => 'Nuevo', 'precio' => '$329.990', 'valor' => 329990, 'stock' => 3, 'color1' => '#10d3da', 'color2' => '#0a995e', 'imagen' => 'img/consolas/switch.jpg'],
    ['titulo' => 'Consola Xbox One Edicion Minecraft',              'plataforma' => 'Generación anterior', 'estado' => 'Usado', 'precio' => '$189.990', 'valor' => 189990, 'stock' => 2, 'color1' => '#61f81b', 'color2' => '#000000', 'imagen' => 'img/consolas/mine.jpg'],
    ['titulo' => 'Consola Nintendo 3DS Edicion Pikachu',            'plataforma' => 'Clásica ',            'estado' => 'Usado', 'precio' => '$150.000', 'valor' => 150000, 'stock' => 1, 'color1' => '#e3f11a', 'color2' => '#e4e722', 'imagen' => 'img/consolas/pika.jpg'],
];

function pvIniciales($titulo) {
    $palabras = explode(' ', $titulo);
    $ini = '';
    foreach (array_slice($palabras, 0, 2) as $p) { $ini .= strtoupper(substr($p, 0, 1)); }
    return $ini;
}

function pvRenderTarjetas($items) {
    foreach ($items as $item) {

        // ¿Este producto tiene una imagen guardada y el archivo existe en el servidor?
        $tieneImagen = isset($item['imagen']) && file_exists($item['imagen']);

        // data-valor y data-stock: así le "pasamos" estos datos del PHP al JavaScript,
        // que los va a leer directo del HTML con tarjeta.dataset.valor / tarjeta.dataset.stock
        echo '<div class="col-12 col-sm-6 col-md-4 col-lg-3">';
        echo '  <div class="pv-card" data-valor="' . (int)$item['valor'] . '" data-stock="' . (int)$item['stock'] . '">';
        echo '    <div class="pv-price-tag">' . htmlspecialchars($item['precio']) . '</div>';

        if ($tieneImagen) {
            // Si hay imagen, mostramos la foto real del producto (object-fit:cover evita que se deforme)
            echo '    <img src="' . htmlspecialchars($item['imagen']) . '" alt="' . htmlspecialchars($item['titulo']) . '" class="pv-card-img">';
        } else {
            // Si todavía no hay imagen, mostramos el cuadrito de color con iniciales (como antes)
            $art = 'linear-gradient(160deg,' . $item['color1'] . ',' . $item['color2'] . ')';
            echo '    <div class="pv-card-art" style="background:' . $art . ';">' . htmlspecialchars(pvIniciales($item['titulo'])) . '</div>';
        }

        echo '    <div class="pv-card-body">';
        echo '      <h3>' . htmlspecialchars($item['titulo']) . '</h3>';
        echo '      <div class="pv-card-meta">';
        echo '        <span class="pv-badge">' . htmlspecialchars($item['plataforma']) . '</span>';
        echo '        <span class="pv-badge">' . htmlspecialchars($item['estado']) . '</span>';
        echo '      </div>';
        echo '      <p class="pv-stock-texto mb-2" style="font-size:.8rem; color:var(--text-dim);">Quedan <span class="pv-stock-numero">' . (int)$item['stock'] . '</span> unidades</p>';

        // Cantidad + Agregar al carrito, uno al lado del otro
        echo '      <div class="d-flex gap-2">';
        echo '        <input type="number" class="form-control pv-cantidad-input" value="1" min="1" max="' . (int)$item['stock'] . '" style="width:64px;">';
        echo '        <button type="button" class="btn btn-pv btn-sm flex-grow-1" onclick="agregarDesdeTarjeta(this);">Agregar al carrito</button>';
        echo '      </div>';

        echo '    </div>';
        echo '  </div>';
        echo '</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Productos · Pixel Vault</title>
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

    <!-- Lógica del carrito (cantidad, stock y total) vive en un archivo aparte -->
    <script src="js/productos.js"></script>
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
                        <a class="nav-link active" href="productos.php">Productos</a>
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

    <section class="pv-section">
        <div class="container">
            <h2 class="pv-section-title">Catálogo</h2>
            <p class="pv-section-sub">Juegos y consolas en stock. Precios en pesos chilenos, IVA incluido.</p>

            <ul class="nav pv-tabs mb-4" id="catalogoTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="tab-juegos-btn" data-bs-toggle="tab" data-bs-target="#tab-juegos" type="button" role="tab">
                        <i class="fa fa-circle-o"></i> Juegos
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tab-consolas-btn" data-bs-toggle="tab" data-bs-target="#tab-consolas" type="button" role="tab">
                        <i class="fa fa-television"></i> Consolas
                    </button>
                </li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade show active" id="tab-juegos" role="tabpanel">
                    <div class="row g-4">
                        <?php pvRenderTarjetas($juegos); ?>
                    </div>
                </div>
                <div class="tab-pane fade" id="tab-consolas" role="tabpanel">
                    <div class="row g-4">
                        <?php pvRenderTarjetas($consolas); ?>
                    </div>
                </div>
            </div>

            <!-- Resumen simple de lo que se ha ido agregando (solo en el navegador, no se guarda) -->
            <div class="mt-5 pv-form-card" style="max-width:420px;">
                <h3 style="font-size:1.1rem; margin-bottom:.75rem;">Carrito</h3>
                <ul id="listaCarrito" style="color:var(--text-dim); padding-left:1.2rem; margin-bottom:.75rem;"></ul>
                <p style="border-top:1px solid var(--border); padding-top:.75rem; margin-bottom:0;">
                    Total: <strong id="totalCarrito" style="color:var(--cyan);">$0</strong>
                </p>
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