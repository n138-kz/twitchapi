<?php
header('content-type: text/plain;charset=UTF-8');
// http_response_code(204);
$config=[
    'filepath'=>realpath(__DIR__.'/../config.json'),
];
$config['data']=[];
$config['data']['raw']=file_get_contents($config['filepath']);
$config['data']['parse']=json_decode($config['data']['raw'], TRUE);
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
if(!isset($request['code'])||empty($request['code'])){
	http_response_code(400);
	if(explode(';', $config['export_format'].';')[0]=='application/json'){
		die(json_encode([
			'request_at'=>$_SERVER['REQUEST_TIME'],
			'status'=>http_response_code(),
			'message'=>'Missing code',
		]));
	}else{
		die('Missing code');
	}
}

function getuserinfo($code='', $login='*'){
	global $config;
	/* 
	manual_url='https://dev.twitch.tv/docs/api/reference#get-users'
	access_token='ssxxxxxxxxxxxxxxxxxxxxxxxxxxxx'
	client_id='61xxxxxxxxxxxxxxxxxxxxxxxxxxxx'
	user_id='10000000' # not login name, but the user id
	curl -H "Authorization: Bearer ${access_token}" -H "Client-Id: ${client_id}" https://api.twitch.tv/helix/users?login?={$user_id} | jq
	*/
	$url="https://api.twitch.tv/helix/users?login?={$login}";

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HTTPHEADER, [
		"Authorization: Bearer {$code}",
		"Client-Id: {$config['data']['parse']['twitch']['client_id']}",
	]);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
	$ch_body = json_decode(curl_exec($ch), TRUE);
	$ch_head = curl_getinfo($ch);
	$ch = null;

	return [
		'head'=>$ch_head,
		'body'=>$ch_body,
	];
}
function getuservideoarchives($code='', $id='0'){
	global $config;
	/* 
	manual_url='https://dev.twitch.tv/docs/api/reference#get-videos'
	access_token='ssxxxxxxxxxxxxxxxxxxxxxxxxxxxx'
	client_id='61xxxxxxxxxxxxxxxxxxxxxxxxxxxx'
	user_id='10000000' # not login name, but the user id
	curl -H "Authorization: Bearer ${access_token}" -H "Client-Id: ${client_id}" https://api.twitch.tv/helix/videos?user_id=${user_id} | jq
	*/
	$url="https://api.twitch.tv/helix/videos?user_id={$id}&first=100";

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HTTPHEADER, [
		"Authorization: Bearer {$code}",
		"Client-Id: {$config['data']['parse']['twitch']['client_id']}",
	]);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
	$ch_body = json_decode(curl_exec($ch), TRUE);
	$ch_head = curl_getinfo($ch);
	$ch = null;

	return [
		'head'=>$ch_head,
		'body'=>$ch_body,
	];
}
function getstreamStatus($code='', $id='0'){
	global $config;
	/* 
	manual_url='https://dev.twitch.tv/docs/api/reference#get-streams'
	access_token='ssxxxxxxxxxxxxxxxxxxxxxxxxxxxx'
	client_id='61xxxxxxxxxxxxxxxxxxxxxxxxxxxx'
	user_id='10000000' # not login name, but the user id
	curl -H "Authorization: Bearer ${access_token}" -H "Client-Id: ${client_id}" https://api.twitch.tv/helix/streams?user_id=${user_id} | jq
	*/
	$url="https://api.twitch.tv/helix/streams?user_id={$id}";

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HTTPHEADER, [
		"Authorization: Bearer {$code}",
		"Client-Id: {$config['data']['parse']['twitch']['client_id']}",
	]);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
	$ch_body = json_decode(curl_exec($ch), TRUE);
	$ch_head = curl_getinfo($ch);
	$ch = null;

	return [
		'head'=>$ch_head,
		'body'=>$ch_body,
	];
}
function getTwitchItem($url='', $code=''){
	global $config;
	if(empty($url) || empty($code)){
		return NULL;
	}
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HTTPHEADER, [
		"Authorization: Bearer {$code}",
		"Client-Id: {$config['data']['parse']['twitch']['client_id']}",
	]);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
	$ch_body = json_decode(curl_exec($ch), TRUE);
	$ch_head = curl_getinfo($ch);
	$ch = null;

	return [
		'head'=>$ch_head,
		'body'=>$ch_body,
	];
}

if(!($request['item']=='get-userinfo' || $request['item']=='get-uid')){
	if(!isset($request['id'])||empty($request['id'])){
		http_response_code(400);
		if(explode(';', $config['export_format'].';')[0]=='application/json'){
			die(json_encode([
				'request_at'=>$_SERVER['REQUEST_TIME'],
				'status'=>http_response_code(),
				'message'=>'Missing id',
			]));
		}else{
			die('Missing id');
		}
	}
}
$result=NULL;
switch($request['item']){
	case 'get-userinfo':
		$result=getuserinfo($request['code'], '*');
		$result=isset($result['body'])?$result['body']:$result;
		$result=isset($result['data'])?$result['data']:$result;
		$result=count($result)==1?$result[0]:$result;
		break;
	case 'get-uid':
		$result=getuserinfo($request['code'], '*');
		$result=isset($result['body'])?$result['body']:$result;
		$result=isset($result['data'])?$result['data']:$result;
		$result=count($result)==1?$result[0]:$result;
		$result=isset($result['id'])?$result['id']:$result;
		break;
	case 'get-videos':
		$result=getuservideoarchives($request['code'], $request['id']);
		$result=isset($result['body'])?$result['body']:$result;
		$result=isset($result['data'])?$result['data']:$result;
		$result=count($result)==1?$result[0]:$result;
		break;
	case 'get-markers':
		/* 
		manual_url='https://dev.twitch.tv/docs/api/reference#get-stream-markers'
		access_token='ssxxxxxxxxxxxxxxxxxxxxxxxxxxxx'
		client_id='61xxxxxxxxxxxxxxxxxxxxxxxxxxxx'
		user_id='10000000' # not login name, but the user id
		video_id='10000000'
		curl -H "Authorization: Bearer ${access_token}" -H "Client-Id: ${client_id}" https://api.twitch.tv/helix/streams/markers?user_id=${user_id} | jq # get from the recently live stream
		curl -H "Authorization: Bearer ${access_token}" -H "Client-Id: ${client_id}" https://api.twitch.tv/helix/streams/markers?video_id=${video_id} | jq
		*/
		$url="https://api.twitch.tv/helix/streams/markers?video_id={$request['id']}&first=100";
		$result=getTwitchItem($url, $request['code']);
		$result=isset($result['body'])?$result['body']:$result;
		$result=isset($result['data'])?$result['data']:$result;
		$result=count($result)==1?$result[0]:$result;
		$result=isset($result['videos'])?$result['videos']:$result;
		$result=count($result)==1?$result[0]:$result;
		break;
	case 'get-streamStatus':
		$result=getstreamStatus($request['code'], $request['id']);
		$result=isset($result['body'])?$result['body']:$result;
		$result=isset($result['data'])?$result['data']:$result;
		$result=count($result)==1?$result[0]:$result;
		break;
	case 'get-channelinformation':
		/* 
		manual_url='https://dev.twitch.tv/docs/api/reference#get-channel-information'
		access_token='ssxxxxxxxxxxxxxxxxxxxxxxxxxxxx'
		client_id='61xxxxxxxxxxxxxxxxxxxxxxxxxxxx'
		user_id='10000000' # not login name, but the user id
		curl -s -H "Authorization: Bearer ${access_token}" -H "Client-Id: ${client_id}" https://api.twitch.tv/helix/channels?broadcaster_id=${user_id} | jq
		*/
		$result=getTwitchItem("https://api.twitch.tv/helix/channels?broadcaster_id={$request['id']}", $request['code']);
		$result=isset($result['body'])?$result['body']:$result;
		$result=isset($result['data'])?$result['data']:$result;
		$result=count($result)==1?$result[0]:$result;
	default:
		http_response_code(404);
		if(explode(';', $config['export_format'].';')[0]=='application/json'){
			die(json_encode([
				'request_at'=>$_SERVER['REQUEST_TIME'],
				'status'=>http_response_code(),
				'message'=>'Invalid item',
			]));
		} else {
			die('Invalid item');
		}
}

header("Content-Type: {$config['export_format']};charset=UTF-8");
#echo json_encode(['config'=>$config,'_SERVER'=>$_SERVER,'_GET'=>$_GET,'request'=>$request, 'result'=>$result]);

if(explode(';', $config['export_format'].';')[0]=='application/json'){
	die(json_encode([
		'request_at'=>$_SERVER['REQUEST_TIME'],
		'status'=>http_response_code(),
		'message'=>'Sucess',
		'data'=>$result,
	]));
}else{
	$result=(is_array($result))?json_encode($result):$result;
	die($result);
}
