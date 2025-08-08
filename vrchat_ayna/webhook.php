<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo 'Méthode non autorisée';
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['name'], $data['looking'], $data['about'], $data['trap'])) {
    http_response_code(400);
    echo 'Champs manquants';
    exit;
}

$webhookURL = 'https://discord.com/api/webhooks/1402546461518073857/i6UicOeDFVSLvBAz5Ba5vhMm2Bgfl1oXmGWTAidqDVgpD6UBzlNaD2P9STT_NIVysXRr';

$payload = json_encode([
    'content' => "📨 NOUVELLE FICHE ERP :\n🧑 VR Chat Name or ID: {$data['name']}\n🎯 Looking for: {$data['looking']}\n🗣 Something about you: {$data['about']}\n✅ Traps are not gay: {$data['trap']}"
]);

$ch = curl_init($webhookURL);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

echo 'Message envoyé !';
