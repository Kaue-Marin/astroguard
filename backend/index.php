<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

echo json_encode(["status" => "AstroGuard API rodando com sucesso!"]);