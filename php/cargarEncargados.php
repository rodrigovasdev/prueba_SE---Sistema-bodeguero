<?php
/**
 * Endpoint que retorna el listado completo de encargados en formato JSON,
 * ordenados por nombre. Es consumido por el select múltiple del formulario
 * para permitir la asignación de encargados a una bodega.
 */
require_once 'conexion.php';

header('Content-Type: application/json');

try {
    $stmt = $pdo->query("SELECT id, run, nombre, apellido1 FROM encargado ORDER BY nombre");
    $encargados = $stmt->fetchAll();

    echo json_encode(['success' => true, 'data' => $encargados]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
