<?php
/**
 * Endpoint que elimina una bodega por su 'id' recibido via POST.
 * Primero elimina los registros asociados en bodega_encargado y
 * luego elimina el registro principal en la tabla bodega.
 * Retorna un JSON con 'success' true o false según el resultado.
 */
require_once 'conexion.php';

header('Content-Type: application/json');

$id = $_POST['id'] ?? null;

try {
    // Eliminamos los encargados asociados a la bodega
    $stmt_rel = $pdo->prepare("DELETE FROM bodega_encargado WHERE id_bodega = ?");
    $stmt_rel->execute([$id]);

    // Eliminamos la bodega
    $stmt = $pdo->prepare("DELETE FROM bodega WHERE id = ?");
    $stmt->execute([$id]);
    
    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
exit;
