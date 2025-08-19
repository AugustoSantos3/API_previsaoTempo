<?php
$apiKey = "Sua API Key Aqui"; // troque por sua chave
$city = isset($_GET['city']) ? urlencode($_GET['city']) : "São Paulo";
$units = "metric";
$lang = "pt_br";
$url = "https://api.openweathermap.org/data/2.5/weather?q={$city}&appid={$apiKey}&units={$units}&lang={$lang}";

$response = @file_get_contents($url);

if ($response === FALSE) {
    http_response_code(500);
    echo json_encode(["error" => "Erro ao buscar dados do clima."]);
    exit;
}

$data = json_decode($response, true);

if (!$data || !isset($data['main'])) {
    http_response_code(500);
    echo json_encode(["error" => "Resposta inválida da API", "resposta" => $response]);
    exit;
}

$resultado = [
    "cidade" => $data['name'] ?? '',
    "temperatura" => ($data['main']['temp'] ?? '') . " °C",
    "sensacao" => ($data['main']['feels_like'] ?? '') . " °C",
    "umidade" => ($data['main']['humidity'] ?? '') . "%",
    "clima" => $data['weather'][0]['description'] ?? ''
];

header("Content-Type: application/json; charset=UTF-8");
echo json_encode($resultado, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);