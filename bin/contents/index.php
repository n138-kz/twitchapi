<?php
header('content-type: text/plain;charset=UTF-8');
// http_response_code(204);
$config=[
    'filepath'=>realpath(__DIR__.'/../config.json'),
];
$config['data']=[];
$config['data']['raw']=file_get_contents($config['filepath']);
$config['data']['parse']=json_decode($config['data']['raw']);
$config['export_format']=(explode(';', strtolower($_SERVER['CONTENT_TYPE']).';')[0]=='application/json')?'application/json':'text/plain';

if(!isset($_SERVER['REQUEST_METHOD'])||strtoupper($_SERVER['REQUEST_METHOD'])!='GET'){
	http_response_code(405);
	die('Invalid method');
}
if(!isset($_GET)){
	http_response_code(400);
	die('Missing item');
}
