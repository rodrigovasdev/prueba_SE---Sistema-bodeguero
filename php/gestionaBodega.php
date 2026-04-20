<?php
/**
 * Endpoint que gestiona la creación y actualización de bodegas via POST.
 * Si el campo 'id' es 0 o vacío, inserta una nueva bodega (INSERT).
 * Si 'id' tiene un valor, actualiza la bodega existente (UPDATE).
 * Finalmente sincroniza los encargados en la tabla bodega_encargado.
 */
require_once 'conexion.php';

$id = $_POST['id'] ?? '0';
$codigo = $_POST['codigo'] ?? '';
$nombre = $_POST['nombre'] ?? '';
$direccion = $_POST['direccion'] ?? '';
$dotacion = $_POST['dotacion'] ?? 0;
$estado = $_POST['estado'] ?? 'true';
$encargados = $_POST['encargados'] ?? []; 

try {
    $pdo->beginTransaction();

    if ($id === '0' || $id === '') {
        // Creamos nueva bodega cuando id igual a 0
        $stmt = $pdo->prepare("INSERT INTO bodega (codigo, nombre, direccion, dotacion, estado) VALUES (?, ?, ?, ?, ?) RETURNING id");
        $stmt->execute([$codigo, $nombre, $direccion, $dotacion, $estado]);
        $id = $stmt->fetchColumn();
    } else {
        // Actualizamos bodega existente cuando id distinto de 0
        $stmt = $pdo->prepare("UPDATE bodega SET codigo = ?, nombre = ?, direccion = ?, dotacion = ?, estado = ? WHERE id = ?");
        $stmt->execute([$codigo, $nombre, $direccion, $dotacion, $estado, $id]);
        
        // Limpiamos sus encargados anteriores
        $stmt_del = $pdo->prepare("DELETE FROM bodega_encargado WHERE id_bodega = ?");
        $stmt_del->execute([$id]);
    }
    
    // Insertamos los encargados
    if (!empty($encargados) && is_array($encargados)) {
        $stmt_rel = $pdo->prepare("INSERT INTO bodega_encargado (id_bodega, id_encargado) VALUES (?, ?)");
        foreach ($encargados as $encargado_id) {
            $stmt_rel->execute([$id, $encargado_id]);
        }
    }

    $pdo->commit();
} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    // Retornamos estatus 500 para que response.ok en JS sea false e informe de error a frontend.
    http_response_code(500);
    echo $e->getMessage();
}

exit;
