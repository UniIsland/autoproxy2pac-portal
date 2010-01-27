<?php
header('Content-Type: application/x-ns-proxy-autoconfig');
header('Content-Disposition: attachment; filename="gfwlist.pac"');
$pacURL = "http://autoproxy2pac.appspot.com/pac/" . $_GET["type"] . "/" . $_GET["host"] . "/" . $_GET["port"];
$source = @fopen("$pacURL", "r");
if ($source) {
  while(!feof($source)) {
    print(fread($source, 8192));
    flush();
  }
  @fclose($source);
}
?>