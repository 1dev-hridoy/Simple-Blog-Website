<?php
header('Content-Type: application/json');
include '../../server/dbcon.php';

try {
    // Get POST data
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['post_id']) || !isset($data['platform'])) {
        throw new Exception('Missing required data');
    }

    // Validate platform
    $valid_platforms = ['facebook', 'twitter', 'whatsapp', 'linkedin', 'other'];
    if (!in_array($data['platform'], $valid_platforms)) {
        throw new Exception('Invalid platform');
    }

    // Record the share
    $stmt = $pdo->prepare("INSERT INTO post_shares (post_id, platform) VALUES (?, ?)");
    $stmt->execute([$data['post_id'], $data['platform']]);

    // Get updated share count
    $stmt = $pdo->prepare("SELECT COUNT(*) as share_count FROM post_shares WHERE post_id = ?");
    $stmt->execute([$data['post_id']]);
    $result = $stmt->fetch();

    echo json_encode([
        'success' => true,
        'message' => 'Share recorded successfully',
        'share_count' => $result['share_count']
    ]);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}