<?php
header('content-type: text/plain');
//http_response_code(204);
$config=[
    'filepath'=>realpath(__DIR__.'/../config.json'),
];
echo json_encode($config);
