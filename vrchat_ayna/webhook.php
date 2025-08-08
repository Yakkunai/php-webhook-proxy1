<?php
// Autorise toutes les origines
header("Access-Control-Allow-Origin: *");

// Permet les méthodes POST, OPTIONS
header("Access-Control-Allow-Methods: POST, OPTIONS");

// Accepte les headers standards (notamment Content-Type pour JSON)
header("Access-Control-Allow-Headers: Content-Type");

// Si c'est une requête OPTIONS (preflight), on répond vide et on sort
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Empêche les autres méthodes
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo 'Méthode non autorisée';
    exit;
}

// Ton code continue ici...

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
