<?php
if(!defined('cookie_key'))
    require __DIR__.'/../conf/encsettings.php';
    
function check_cookie(){
	if(!isset($_COOKIE['SID']))
		return false;
	$cookie = decrypt(cookie_key, $_COOKIE['SID']);
	if($cookie === false)
		return false;
	$arr = unserialize($cookie);
	//print_r($arr);

	if(false===$arr || !isset($arr['magic']) || $arr['magic']!="cwoj")
		return false;
	$user = $arr['user'];
	if(preg_match('/\W/',$user) || strlen($user)==0)
		return false;

	$_SESSION['user'] = $user;
	return true;
}
function write_cookie($remember){
	$arr = array('magic'=>'cwoj');
	$arr['user']=$_SESSION['user'];

	$data = encrypt(cookie_key, serialize($arr));
	if($remember==1)
        setcookie('SID', $data, time()+cookie_expire);
	else 
        setcookie('SID', $data);
}

function clear_cookie($name){
	setcookie("$name",'',time()-3600);
}

function encrypt($key, $plain_text){
	$iv='7284565820000000';
	$key=hash('sha256',$key,true);
	return openssl_encrypt($plain_text,'aes-256-cbc',$key,false,$iv);
}
function decrypt($key, $c_t){ 
	$iv='7284565820000000';
	$key=hash('sha256',$key,true);
	return openssl_decrypt($c_t,'aes-256-cbc',$key,false,$iv);
}