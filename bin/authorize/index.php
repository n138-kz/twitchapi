<?php
header('content-type: text/plain');
$config['twitch']['client_id'] = '61qmmbfstntv8v1k3a6xyvsrnylwgn';
$config['twitch']['client_secret'] = NULL;
$config['twitch']['redirect_uri'] = 'https://api.n138.jp/twitch/code/';
$config['twitch']['response_type'] = 'code';
$config['twitch']['scope'] = 'https://dev.twitch.tv/docs/authentication/scopes#twitch-access-token-scopes';
$config['twitch']['scope'] = 'user:read:broadcast';
$config['twitch']['scope'] = 'channel:manage:broadcast';
$config['twitch']['grant_type'] = NULL;

foreach(['client_id','redirect_uri','response_type','scope'] as $k1 => $v1){
	$config['twitch'][$v1]
		=isset($_GET['twitch:'.$v1])&&$_GET['twitch:'.$v1]!=''
		?$_GET['twitch:'.$v1]:$config['twitch'][$v1];
}

$redirect_uri = '';
$redirect_uri .= '';
$redirect_uri .= 'https://id.twitch.tv/oauth2/authorize';
$redirect_uri .= '?client_id='.$config['twitch']['client_id'];
$redirect_uri .= '&redirect_uri='.$config['twitch']['redirect_uri'];
$redirect_uri .= '&response_type='.$config['twitch']['response_type'];
$redirect_uri .= '&scope='.$config['twitch']['scope'];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $redirect_uri);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$ch_body = json_decode(curl_exec($ch));
$ch_head = curl_getinfo($ch);
$ch = null;
if((explode(';',$ch_head['content_type'])[0])==='text/html'){
	header('location: '.$redirect_uri);
}
die(json_encode([
	"head"=>$ch_head,
	"body"=>$ch_body,
]));
exit();
