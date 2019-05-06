<?php
@session_start();
$host=$_GET['h'] ? $_GET['h'] : 'http://10.0.0.10:8080/';
$uri=$_GET['uri'] ? $_GET['uri'] : '';
$url=$host.$uri;

if ($_SERVER['REQUEST_METHOD'] =="POST") {
    echo $_SESSION['cookiestr'];
  $context = stream_context_create(array('http' => array('method' => $_SERVER['REQUEST_METHOD'],'header'=> 'Cookie:'.@$_SESSION['cookiestr'],'content' => http_build_query($_POST),'timeout' => 20)));
  $data=file_get_contents($url,false,$context);
}else{
  $context = stream_context_create(array('http' => array('method' => $_SERVER['REQUEST_METHOD'],'header'=> 'Cookie:'.@$_SESSION['cookiestr'],/*'content' => http_build_query($_POST),*/'timeout' => 20)));
  $data=file_get_contents($url,false,$context);
}

$cookies="";
$cookiesstr="";
foreach ($http_response_header as $key => $value) {
  if (preg_match_all('/Set-Cookie: (.*?);/',$value,$cookies)>0) {
    break;
  }
}
foreach ($cookies[1] as $key => $value) {
  $cookiesstr.=$value.";";
}
if (($_SESSION['cookiestr'])！= $cookiesstr) {
  $_SESSION['cookiestr']=$cookiesstr;
}

$data=preg_replace('/src="(?!http)/', "src=\"http://" . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "?h=" . $_GET['h'] . "&uri=", $data);
$data=preg_replace('/href="(?!http|#)/', "href=\"http://" . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "?h=" . $_GET['h'] . "&uri=", $data);
$data=preg_replace('/action="(?!http)/', "action=\"http://" . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "?h=" . $_GET['h'] . "&uri=", $data);
//$data=preg_replace('/data-main="(?!http)/', "data-main=\"http://" . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "?h=" . $_GET['h'] . "&uri=", $data);
//$data=preg_replace('/baseUrl: "\/Areas\/HelpPage\//', "baseUrl: \"http://" . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "?h=" . $_GET['h'] . "&uri=/Areas/HelpPage/", $data);
//$data=preg_replace('/\'app\': \'\/Areas\/HelpPage\/content\/js\/app\'/', "'app': 'http://" . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "?h=" . $_GET['h'] . "&uri=/Areas/HelpPage/content/js/app.js'", $data);


echo $data;
