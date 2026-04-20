<?php
/**
 * Endpoint que retorna el listado de bodegas en formato JSON.
 * Acepta el parámetro opcional 'filtro' (true/false) para filtrar
 * por estado (activa/desactivada). Si no se recibe, retorna todas.
 * Incluye los encargados asociados y sus IDs por cada bodega.
 */
require_once 'conexion.php';

header('Content-Type: application/json');
$filtroEstado = $_REQUEST['filtro'] ?? '';

$sql = "
    SELECT
        b.id,
        b.codigo,
        b.nombre,
        b.direccion,
        b.dotacion,
        b.estado,
        b.fecha_crea,
        COALESCE(
            STRING_AGG(e.nombre || ' ' || e.apellido1, '<br>' ORDER BY e.nombre),
            '<i>Sin encargados</i>'
        ) AS encargados,
        COALESCE(
            JSON_AGG(be.id_encargado) FILTER (WHERE be.id_encargado IS NOT NULL),
            '[]'::json
        ) AS ids_encargados
    FROM bodega b
    LEFT JOIN bodega_encargado be ON be.id_bodega = b.id
    LEFT JOIN encargado e ON e.id = be.id_encargado
";

$params = [];

// Si el filtro es distinto de vacío, filtramos por estado
if ($filtroEstado !== '') {
    $sql .= " WHERE b.estado = :estado";
    $params[':estado'] = ($filtroEstado === 'true') ? 'true' : 'false';
}

$sql .= " GROUP BY b.id, b.codigo, b.nombre, b.direccion, b.dotacion, b.estado
          ORDER BY b.codigo";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $bodegas = $stmt->fetchAll();

    foreach ($bodegas as &$b) {
        $b['ids_encargados'] = json_decode($b['ids_encargados'], true) ?? [];
        $b['estado_bool'] = ($b['estado'] === 't' || $b['estado'] === true || $b['estado'] === 'true');
    }
    unset($b);

    echo json_encode(['success' => true, 'data' => $bodegas]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
