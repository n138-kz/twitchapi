<?php
header('content-type: text/plain');
$config['twitch']['client_id'] = '61qmmbfstntv8v1k3a6xyvsrnylwgn';
$config['twitch']['client_secret'] = NULL;
$config['twitch']['redirect_uri'] = 'https://api.n138.jp/twitch/code/';
$config['twitch']['response_type'] = 'code';
$config['twitch']['scope'] = 'https://dev.twitch.tv/docs/authentication/scopes#twitch-access-token-scopes';
$config['twitch']['scope'] = 'user:read:broadcast';
$config['twitch']['scope'] = 'channel:manage:broadcast';
$config['twitch']['grant_type'] = 'authorization_code';

foreach(['client_id','redirect_uri','response_type','scope'] as $k1 => $v1){
	$config['twitch'][$v1]
		=isset($_GET['twitch:'.$v1])&&$_GET['twitch:'.$v1]!=''
		?$_GET['twitch:'.$v1]:$config['twitch'][$v1];
}

if(!isset($_GET['code'])||$_GET['code']==''){
	http_response_code(400);
	die('missing code');
}

if(!isset($_GET['user_id'])||$_GET['user_id']==''){
	http_response_code(400);
	die('missing user_id');
}

$redirect_uri = 'https://dev.twitch.tv/docs/api/reference#get-users';
$redirect_uri = '';
$redirect_uri .= '';
$redirect_uri .= 'https://api.twitch.tv/helix/users';
$redirect_uri .= '?login='.$_GET['user_id'];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $redirect_uri);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
	"Authorization: Bearer {$_GET['code']}",
	"Client-Id: {$config['twitch']['client_id']}",
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
$ch_body = json_decode(curl_exec($ch),TRUE);
$ch_head = curl_getinfo($ch);
$ch = null;
if(isset($ch_body['data'])&&count($ch_body['data'])==0){
	http_response_code(204);
}else{
	http_response_code($ch_head['http_code']);
}
if(isset($ch_body['data'])&&count($ch_body['data'])>0){
	foreach(glob('../*') as $k1 => $v1){
		if(is_dir($v1)){
			$ch_body['urls'][]=str_replace($_SERVER['CONTEXT_DOCUMENT_ROOT'], '', realpath($v1)).'/?code='.$_GET['code'].'&user_id='.$ch_body['data'][0]['id'];
		}
	}
}
die(json_encode([
	"head"=>$ch_head,
	"body"=>$ch_body,
]));
exit();
