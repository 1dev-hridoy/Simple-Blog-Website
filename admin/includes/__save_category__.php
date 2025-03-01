<?php
session_start();
include '../../server/dbcon.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $name = $_POST['name'] ?? '';
        
        if (empty($name)) {
            throw new Exception('Category name is required');
        }
        
        // Check if category already exists
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM category WHERE name = ?");
        $stmt->execute([$name]);
        if ($stmt->fetchColumn() > 0) {
            throw new Exception('Category already exists');
        }
        
        // Insert new category
        $stmt = $pdo->prepare("INSERT INTO category (name) VALUES (?)");
        if ($stmt->execute([$name])) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Category added successfully'
            ]);
        } else {
            throw new Exception('Failed to add category');
        }
        
    } catch (Exception $e) {
        echo json_encode([
            'status' => 'error',
            'message' => $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request method'
    ]);
}
?>