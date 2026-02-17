<?php
if(!isset($_GET['query'])||$_GET['query']==''){
	http_response_code(400);
	header('location:'.$_SERVER['SCRIPT_URI'].'?query=%20');
	exit();
}
header('content-type: text/html');
?><!DOCTYPE html><html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="Pragma" content="no-cache">
	<meta http-equiv="Cache-Control" content="no-cache">
	<meta http-equiv="Expires" content="0">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="referrer" content="no-referrer">
	<meta http-equiv="refresh" content="300;../">
	<link rel="stylesheet" type="text/css" href="https://n138-kz.github.io/lib/master.css?t=0">
	<script src="https://n138-kz.github.io/lib/master.js"></script>
	<style>
		textarea {
			width: 350px;
		}
	</style>
</head>
<body>
	<h1>設定ツール</h1>
	<h2><a href="./#openssl_encrypt%20generate" name="openssl_encrypt%20generate">openssl_encrypt generate</a></h2>
	<fieldset>
		<legend>openssl_encrypt</legend>
		<form action="" method="GET">
			<input type="submit" value="Encrypt">
			<input type="hidden" name="mode" value="encrypt">
			<input type="password" name="passphrase" value="" required onclick="this.select();"><br>
			<textarea name="query"><?php if(isset($_GET['mode'])&&$_GET['mode']=='decrypt'){echo openssl_decrypt($_GET['query'], 'aes-256-cbc', $_GET['passphrase']);}else{echo $_GET['query'];}?></textarea>
			<input type="button" value="Copy" id="czi3e3gjt9ixkaib" onclick="copyText(this.previousElementSibling.value);">
		</form>
	</fieldset>
	<fieldset>
		<legend>openssl_decrypt</legend>
		<form action="" method="GET">
			<input type="submit" value="Decrypt">
			<input type="hidden" name="mode" value="decrypt">
			<input type="password" name="passphrase" value="" required onclick="this.select();"><br>
			<textarea name="query"><?php if(isset($_GET['mode'])&&$_GET['mode']=='encrypt'){echo openssl_encrypt($_GET['query'], 'aes-256-cbc', $_GET['passphrase']);}?></textarea>
			<input type="button" value="Copy">
		</form>
	</fieldset>
</body>
</html>
