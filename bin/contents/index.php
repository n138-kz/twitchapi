<?php
header('content-type: text/plain;charset=UTF-8');
// http_response_code(204);
$config=[
    'filepath'=>realpath(__DIR__.'/../config.json'),
];
$config['data']=[];
$config['data']['raw']=file_get_contents($config['filepath']);
$config['data']['parse']=json_decode($config['data']['raw']);
$config['export_format']=(isset($_SERVER['CONTENT_TYPE'])&&explode(';', strtolower($_SERVER['CONTENT_TYPE']).';')[0]=='application/json')?'application/json':'text/plain';

if(!isset($_SERVER['REQUEST_METHOD'])||strtoupper($_SERVER['REQUEST_METHOD'])!='GET'){
	http_response_code(405);
	if(explode(';', $config['export_format'].';')[0]=='application/json'){
		die(json_encode([
			'request_at'=>$_SERVER['REQUEST_TIME'],
			'status'=>http_response_code(),
			'message'=>'Invalid method',
		]));
	}else{
		die('Invalid method');
	}
}
if(!isset($_GET['item'])){
	http_response_code(400);
	if(explode(';', $config['export_format'].';')[0]=='application/json'){
		die(json_encode([
			'request_at'=>$_SERVER['REQUEST_TIME'],
			'status'=>http_response_code(),
			'message'=>'Missing item',
		]));
	}else{
		die('Missing item');
	}
}

$request=$_GET['item'];
if(json_validate($request)){
	$request=json_decode($request, TRUE);
}else{
	http_response_code(400);
	if(explode(';', $config['export_format'].';')[0]=='application/json'){
		die(json_encode([
			'request_at'=>$_SERVER['REQUEST_TIME'],
			'status'=>http_response_code(),
			'message'=>'Expected json format: item',
		]));
	}else{
		die('Expected json format: item');
	}
	
}

if(!isset($request['client_id'])||empty($request['client_id'])){
	http_response_code(400);
	if(explode(';', $config['export_format'].';')[0]=='application/json'){
		die(json_encode([
			'request_at'=>$_SERVER['REQUEST_TIME'],
			'status'=>http_response_code(),
			'message'=>'Missing client_id',
		]));
	}else{
		die('Missing client_id');
	}
}else{
	if($config['data']['parse']['twitch']['client_id']!=$request['client_id']){
		http_response_code(400);
		if(explode(';', $config['export_format'].';')[0]=='application/json'){
			die(json_encode([
				'request_at'=>$_SERVER['REQUEST_TIME'],
				'status'=>http_response_code(),
				'message'=>'Invalid client_id',
			]));
		}else{
			die('Invalid client_id');
		}
	}
}
if(!isset($request['item'])||empty($request['item'])){
	http_response_code(400);
	if(explode(';', $config['export_format'].';')[0]=='application/json'){
		die(json_encode([
			'request_at'=>$_SERVER['REQUEST_TIME'],
			'status'=>http_response_code(),
			'message'=>'Missing item',
		]));
	}else{
		die('Missing item');
	}
}

function get_userid($login='twitchdev'){
	$url="https://api.twitch.tv/helix/users?login?={$login}";

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HTTPHEADER, [
		"Authorization: Bearer {$_GET['code']}",
		"Client-Id: {$config['twitch']['client_id']}",
	]);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
	$ch_body = json_decode(curl_exec($ch),TRUE);
	$ch_head = curl_getinfo($ch);
	$ch = null;

	return [$url,file_get_contents($url)];
}

header("Content-Type: {$config['export_format']};charset=UTF-8");
echo json_encode(['config'=>$config,'_SERVER'=>$_SERVER,'_GET'=>$_GET,'request'=>$request, 'get_userid'=>get_userid()]);
