<?php
/**
 * Archivo de conexión a la base de datos PostgreSQL mediante PDO.
 * Define los parámetros de conexión y expone la variable $pdo
 * para ser utilizada por los demás archivos PHP mediante require_once.
 */

$host     = '127.0.0.1';
$port     = '5432';
$dbname   = 'registrobodeguero';
$user     = 'postgres';
$password = 'a';

try {
    $pdo = new PDO(
        "pgsql:host=$host;port=$port;dbname=$dbname",
        $user,
        $password,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}