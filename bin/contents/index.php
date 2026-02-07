<?php
header('content-type: text/plain;charset=UTF-8');
// http_response_code(204);
$config=[
    'filepath'=>realpath(__DIR__.'/../config.json'),
];
$config['data']=[];
$config['data']['raw']=file_get_contents($config['filepath']);
$config['data']['parse']=json_decode($config['data']['raw']);
echo json_encode(['$config'=>$config,'$_SERVER'=>$_SERVER]);
if(!isset($_SERVER['REQUEST_METHOD'])||strtoupper($_SERVER['REQUEST_METHOD'])!='GET'){
	http_response_code(405);
	die('Invalid method');
}
if(!isset($_GET)){
die('Missing item');
}
