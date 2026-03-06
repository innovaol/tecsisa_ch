<?php
/**
 * Laravel Shared Hosting Deploy Helper
 * Sube este archivo a tu public_html y ejecútalo como: tu-dominio.com/deploy_helper.php
 * ¡BORRA ESTE ARCHIVO DESPUÉS DE USARLO POR SEGURIDAD!
 */

// 1. Mostrar errores para debug
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "<h1>🚀 Tecsisa Deploy Helper</h1>";

// Detectar si estamos en la carpeta public
$isPublicDir = basename(__DIR__) === 'public' || basename(__DIR__) === 'public_html';

// 2. Intentar crear el enlace simbólico del storage
echo "<h3>1. Creando enlace simbólico de Storage...</h3>";
// Asumiendo estructura: tecsisa_app (codigo) / public_html (web)
$target = __DIR__ . '/../storage/app/public';

$link = __DIR__ . '/storage';

if (file_exists($link)) {
    echo "⚠️ El enlace/carpeta 'storage' ya existe. Intentando recrear...<br>";
    if (is_link($link)) {
        unlink($link);
    }
    elseif (is_dir($link)) {
        echo "❌ 'storage' es una carpeta real, debes borrarla manualmente antes de crear el enlace simbólico.<br>";
    }
}

if (!file_exists($link)) {
    if (symlink($target, $link)) {
        echo "✅ Enlace simbólico creado con éxito.<br>";
    }
    else {
        echo "❌ Error al crear el enlace simbólico. Verifica los permisos.<br>";
    }
}

// 3. Ejecutar Migraciones y Caché
echo "<h3>2. Ejecutando comandos de Artisan...</h3>";

function runArtisan($command)
{
    try {
        require_once __DIR__ . '/../vendor/autoload.php';
        $app = require_once __DIR__ . '/../bootstrap/app.php';
        $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
        $status = $kernel->call($command);
        echo "🏃 Comando '$command' ejecutado. Salida: <pre>" . $kernel->output() . "</pre><br>";
    }
    catch (Exception $e) {
        echo "❌ Error al ejecutar '$command': " . $e->getMessage() . "<br>";
    }
}

// Comandos sugeridos
runArtisan('config:cache');
runArtisan('route:cache');
runArtisan('view:cache');
echo "⚠️ Migraciones: Descomenta 'migrate --force' en el código de este archivo si ya configuraste tu .env en el servidor.<br>";
// runArtisan('migrate --force'); 

echo "<br><br><strong style='color:red;'>⚠️ ¡ELIMINA ESTE ARCHIVO (deploy_helper.php) DE TU public_html AHORA MISMO POR SEGURIDAD!</strong>";
?>
