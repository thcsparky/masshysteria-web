<?php
  if ($_GET['command'] == 'loadIPS')
  {
    $filedat = file_get_contents('/var/log/lighttpd/access.log');
    $listy = array();
    $pattern = '([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3})';
    $testpattern = '(Mozilla)';

    $arrayips = array();
    $var = preg_match_all($pattern, $filedat, $matches);
    if ($var != NULL)
      {
        foreach($matches[0] as $ip) {
          if (!in_array($ip, $arrayips))
            array_push($arrayips, $ip);
        }
      }
    if (count($arrayips) > 0){
      $varout = '';
      foreach($arrayips as $printy){
        $varout = $varout . $printy . PHP_EOL;
        print($printy . '<br>');
      }
      file_put_contents('/var/www/html/ipscan/ips.txt', $varout);
    }
  }

  if ($_GET['command'] == "clearlog")
  {
    file_put_contents('/var/log/lighttpd/access.log', '');
  }

  if ($_GET['command'] == 'loadgrid')
  {
    $var = file_get_contents('/var/www/html/ipscan/ips.json');
    if ($var == NULL)
      print('<br>Hit populate in order to create the ips.txt file in this dir');

      var_dump($var);
  }

  if ($_GET['command'] == 'makegrid')
  {
    $ips = file_get_contents('/var/www/html/ipscan/ips.txt');
    if ($ips == NULL){
      print('<br>' . 'please populate grid first which will create ips.txt');
      return;
    }
    $arrayips = explode(PHP_EOL, $ips);
    //continue
  }
 ?>
