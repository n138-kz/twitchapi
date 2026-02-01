<?php
header('content-type:application/json;UTF8');
$ls=[];
foreach(glob('*') as $k1 => $v1){
	if(!is_dir($v1)){continue;}
	$ls[]=$_SERVER['SCRIPT_URI'].$v1.'/';
}
die(json_encode($ls));
