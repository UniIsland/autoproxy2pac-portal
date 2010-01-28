<?php
/*
	Desc:		This is still a prototype for autoproxy2pac-portal.
	Author:		HUANG, Tao <tech at huangtao dot me>
	License:	GPL v3
*/

function frontend() {
header('Cache-Control: public, max-age=3600');
echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>A Portal to AutoProxy2PAC</title>
	<script type="text/javascript">
		uriBase = location.protocol + "//" + location.host + location.pathname;
		function updatePredefined(proxyURI)
			{
			document.getElementById(\'addr\').value = uriBase + proxyURI;
			}
		function updateCustom()
		{
			uriSearch = "?type=" + document.getElementById(\'type\').value + "&host=" + document.getElementById(\'host\').value;
			uriSearch = uriSearch + "&port=" + document.getElementById(\'port\').value;
			document.getElementById("addr").value = uriBase + uriSearch;
			}
	</script>
</head>
<body style="padding-left:50px">
	<h1>A Portal to AutoProxy2PAC</h1>
	<div style="float:right;border-style:ridge;border-width:2px;">
		If you don\'t know what autoproxy is.<br />
		Follow links below for more info.<br />
		<ul><li>
			<a href="http://autoproxy.org/" target=_blank>AutoProxy</a><br />
		</li><li>
			<a href="http://autoproxy2pac.appspot.com/" target=_blank>AutoProxy2PAC</a><br />
		</li></ul>
		See source code of this project:
		<ul><li>
			<a href="http://github.com/UniIsland/autoproxy2pac-portal" target=_blank>autoproxy2pac-portal</a>
		</li></ul>
	</div>
	<p>
		This is still a prototype for autoproxy2pac-portal.<br />
		Set up a simple portal page to autoproxy2pac on any public accessible site.<br />
		In order to make autoproxy2pac unblockable by the GFW.
	</p>
	<hr style="max-width:50%;" />
	<div>
		<input type="radio" name="proxy" onclick="document.getElementById(\'custom\').style.display=\'none\';updatePredefined(\'?type=proxy&host=127.0.0.1&port=8000\')" />GAppProxy. 
		<input type="radio" name="proxy" onclick="document.getElementById(\'custom\').style.display=\'none\';updatePredefined(\'?type=socks&host=127.0.0.1&port=9050\')" />Tor. 
		<input type="radio" name="proxy" onclick="document.getElementById(\'custom\').style.display=\'none\';updatePredefined(\'?type=proxy&host=127.0.0.1&port=8580\')" />FreeGate.<br />
		<input type="radio" name="proxy" onclick="document.getElementById(\'custom\').style.display=\'inline\';updateCustom()" />Custom: 
		<div id="custom" style="display:none;">
			<select id="type" onchange="updateCustom()"><option value="proxy">HTTP</option><option value="socks">SOCKS</option></select>
			<input id="host" type="text" onchange="updateCustom()" size="16" value="127.0.0.1" />:
			<input id="port" type="text" onchange="updateCustom()" size="5" value="8080" />
		</div>
		<div id="proxy">
			<br /><textarea id="addr" rows="1" cols="80" readonly="readonly"></textarea>
		</div>
		<input type="button" value="Download" onclick="location.assign(document.getElementById(\'addr\').value)" /><br />
		Or copy the above address into your browser configuration to use this PAC file.
	</div>
</body>
</html>
';
}
function offerPAC() {
	header('Content-Type: application/x-ns-proxy-autoconfig');
	header('Content-Disposition: attachment; filename="gfwlist.pac"');
	header('Cache-Control: public, max-age=600');
	$pacURL = "http://autoproxy2pac.appspot.com/pac/" . $_GET["type"] . "/" . $_GET["host"] . "/" . $_GET["port"];
	$source = @fopen("$pacURL", "r");
	if ($source) {
	  while(!feof($source)) {
		echo(fread($source, 8192));
		flush();
	  }
	  @fclose($source);
	}
}
function warningInvalidParameter() {
	header('Refresh: 5; ' . $_SERVER["SCRIPT_NAME"]);
	echo '<html><body><h2>Invalid Parameter.</h2>You will be redirected to the portal frontend in 5 sec.<br /></body></html>';
}
function checkParameter() {
	$proxyType = preg_match("#^(proxy|socks)$#i", $_GET["type"]);
//	$proxyHost = preg_match("#^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$#", $_GET["host"]);
	$proxyHost = $_GET["host"];
	$proxyPort = preg_match("#^\d{1,5}$#", $_GET["port"]);
	if ($proxyType && $proxyHost && $proxyPort) return "TRUE";
}

if (checkParameter()) {
	offerPAC();
} elseif (count($_GET)) {
	warningInvalidParameter();
} else {
	frontend();
}







?>