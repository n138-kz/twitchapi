<?php
header('content-type: text/plain');
//http_response_code(204);
$config=[
    'filepath'=>__DIR__.'/../config.json',
];
echo json_encode($config);