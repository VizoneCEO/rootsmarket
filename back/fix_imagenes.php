<?php
// Configuración para evitar timeouts y ver errores
set_time_limit(600);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/conection/db.php');

echo "<h1>Limpieza Final de Imágenes (Escaneo de Directorio)</h1>";
echo "<pre>";

try {
    // 1. Definir directorio de imágenes
    // Usamos la ruta absoluta basada en la ubicación de este script
    $dir_relativo = '/front/multimedia/productos/';
    $dir_fisico = realpath(__DIR__ . '/..') . $dir_relativo;

    if (!is_dir($dir_fisico)) {
        die("ERROR CRÍTICO: No se encuentra el directorio: $dir_fisico");
    }

    // 2. Escanear TODOS los archivos reales que existen en la carpeta
    echo "Escaneando directorio: $dir_fisico ...<br>";
    $archivos_en_carpeta = scandir($dir_fisico);

    // Crear un mapa: [ID_13_CHARS] => "Nombre_Real_Del_Archivo.png"
    $mapa_archivos = [];
    foreach ($archivos_en_carpeta as $archivo) {
        if ($archivo === '.' || $archivo === '..') continue;

        // Extraer los primeros 13 caracteres si parece ser un ID válido
        if (preg_match('/^([a-z0-9]{13})-(.*)$/i', $archivo, $coincidencias)) {
            $id_hash = $coincidencias[1]; // Ej: 68e44eeba1aa8
            $mapa_archivos[$id_hash] = $archivo;
        }
    }
    echo "Se encontraron " . count($mapa_archivos) . " archivos candidatos para limpiar.<br><hr>";

    // 3. Consultar la base de datos
    $stmt = $pdo->query("SELECT id, imagen_url FROM producto_imagenes");
    $registros_db = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $contador = 0;
    $errores = 0;

    foreach ($registros_db as $reg) {
        $id_db = $reg['id'];
        $url_db = $reg['imagen_url']; // Ej: .../productos/68e44eeba1aa8-carnes.jpeg

        // Obtener el nombre del archivo según la BD
        $nombre_db = basename($url_db);

        // Extraer el ID de 13 caracteres del nombre en la BD
        // Buscamos algo que empiece con 13 alfanuméricos
        if (preg_match('/^([a-z0-9]{13})/', $nombre_db, $match)) {
            $id_unico = $match[1];

            // ¿Existe este ID en nuestro mapa de archivos físicos?
            if (isset($mapa_archivos[$id_unico])) {

                $nombre_real_archivo = $mapa_archivos[$id_unico]; // El nombre con caracteres raros real
                $path_origen = $dir_fisico . $nombre_real_archivo;

                // Obtener la extensión real del archivo
                $ext = strtolower(pathinfo($nombre_real_archivo, PATHINFO_EXTENSION));

                // Definir nuevo nombre limpio
                $nuevo_nombre = $id_unico . '.' . $ext;
                $path_destino = $dir_fisico . $nuevo_nombre;
                $nueva_url_db = $dir_relativo . $nuevo_nombre;

                // Si el nombre ya está limpio, saltar
                if ($nombre_real_archivo === $nuevo_nombre) {
                    // Verificar si la BD necesita actualización aunque el archivo esté bien
                    if ($url_db !== $nueva_url_db) {
                         $pdo->prepare("UPDATE producto_imagenes SET imagen_url = ? WHERE id = ?")->execute([$nueva_url_db, $id_db]);
                         echo "<span style='color:blue;'>[SOLO BD]</span> URL actualizada para ID $id_db<br>";
                    }
                    continue;
                }

                // Intentar RENOMBRAR
                if (rename($path_origen, $path_destino)) {
                    // Actualizar BD
                    $update = $pdo->prepare("UPDATE producto_imagenes SET imagen_url = ? WHERE id = ?");
                    if ($update->execute([$nueva_url_db, $id_db])) {
                        echo "<span style='color:green;'>[ÉXITO]</span> $nombre_real_archivo -> <strong>$nuevo_nombre</strong><br>";
                        $contador++;
                    } else {
                        // Si falla BD, revertir nombre (seguridad)
                        rename($path_destino, $path_origen);
                        echo "<span style='color:red;'>[ERROR BD]</span> No se pudo actualizar ID $id_db<br>";
                        $errores++;
                    }
                } else {
                    echo "<span style='color:red;'>[ERROR PERMISOS]</span> No se pudo renombrar: $nombre_real_archivo. <strong>¡Ejecuta el comando chmod!</strong><br>";
                    $errores++;
                }

            } else {
                // El archivo no está en la carpeta, pero está en la BD.
                // Puede que ya se haya renombrado antes o se borró.
                // Verificamos si ya existe el archivo limpio
                $posible_limpio = glob($dir_fisico . $id_unico . ".*");
                if (!empty($posible_limpio)) {
                    $archivo_limpio = basename($posible_limpio[0]);
                    $nueva_url = $dir_relativo . $archivo_limpio;
                    // Actualizamos la BD para que apunte al archivo limpio que ya existe
                    $pdo->prepare("UPDATE producto_imagenes SET imagen_url = ? WHERE id = ?")->execute([$nueva_url, $id_db]);
                    echo "<span style='color:orange;'>[CORRECCIÓN BD]</span> Archivo físico ya estaba limpio ($archivo_limpio). BD actualizada.<br>";
                } else {
                    echo "<span style='color:gray;'>[FANTASMA]</span> El archivo para ID $id_unico no existe en la carpeta.<br>";
                }
            }
        }
    }

    echo "</pre>";
    echo "<h3>Proceso terminado.</h3>";
    echo "<ul><li>Renombrados: $contador</li><li>Errores: $errores</li></ul>";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>