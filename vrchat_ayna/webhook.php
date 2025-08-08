<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo 'Méthode non autorisée';
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['content'])) {
    http_response_code(400);
    echo 'Contenu manquant';
    exit;
}

$webhookURL = getenv('DISCORD_WEBHOOK_URL');

$payload = json_encode([
    'content' => $data['content']
]);

$ch = curl_init($webhookURL);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

echo 'Message envoyé !';
