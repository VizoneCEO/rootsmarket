<?php
require_once 'back/conection/db.php';

try {
    $stmt = $pdo->query("SHOW CREATE TABLE productos");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    echo $result['Create Table'];
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>