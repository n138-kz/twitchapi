<?php
if(!isset($_GET['query'])||$_GET['query']==''){
	http_response_code(400);
	header('location:'.$_SERVER['SCRIPT_URI'].'?query=%20');
	exit();
}
header('content-type: text/html');
?><!DOCTYPE html><html lang="ja">
<head>
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
			<input type="submit">
			<input type="hidden" name="mode" value="encrypt">
			<textarea name="query"><?php if(isset($_GET['mode'])&&$_GET['mode']=='decrypt'){echo openssl_decrypt($_GET['query'], 'aes-256-cbc', 'passphrase');}else{echo $_GET['query'];}?></textarea>
		</form>
	</fieldset>
	<fieldset>
		<legend>openssl_decrypt</legend>
		<form action="" method="GET">
			<input type="submit">
			<input type="hidden" name="mode" value="decrypt">
			<textarea name="query"><?php if(isset($_GET['mode'])&&$_GET['mode']=='encrypt'){echo openssl_encrypt($_GET['query'], 'aes-256-cbc', 'passphrase');}?></textarea>
		</form>
	</fieldset>
</body>
</html>
