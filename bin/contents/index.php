<?php
header('content-type: text/plain');
//http_response_code(204);
$config=[
    'filepath'=>realpath(__DIR__.'/../config.json'),
];
$config['rawdata']=file_get_contents($config['filepath']);
echo json_encode($config);
