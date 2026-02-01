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
	header('location:../authorize/');
	die('missing code');
}

$redirect_uri = 'https://id.twitch.tv/oauth2/token';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $redirect_uri);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
	'client_id' => $config['twitch']['client_id'],
	'client_secret' => $config['twitch']['client_secret'],
	'code' => $_GET['code'],
	'grant_type' => $config['twitch']['grant_type'],
	'redirect_uri' => $config['twitch']['redirect_uri'],
]));
$ch_body = json_decode(curl_exec($ch));
$ch_head = curl_getinfo($ch);
$ch = null;
http_response_code($ch_head['http_code']);
die(json_encode([
	"head"=>$ch_head,
	"body"=>$ch_body,
]));
exit();
