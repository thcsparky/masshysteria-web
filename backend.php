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
        $varout = $varout . $printy . '\n';
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

      try{
      $jar = json_decode($var, true);
    }
    catch(Exception $e) {
      print('Error decoding jason');
      return;}

    foreach($jar as $arr)
      print($arr['name']);
  }

  if ($_GET['command'] == 'etc')
  {
    return;
  }

  if ($_GET['command'] == 'getipgrid')
  {
    $ipvar = $_GET['ip'];
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://whois.toolforge.org/gateway.py?lookup=true&ip=' . $ipvar);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; rv:1.7.3) Gecko/20041001 Firefox/0.10.1");
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); //very important
    $dat = curl_exec($ch);
    curl_close($ch);
    //parse data into an array
    $subarr = array();
    $ipname1 = explode("nets</th><td>", $dat);
    $ipname2 = explode('</table>', $ipname1[1]);
    $ipname = $ipname2[0] . '</table><br><br>';
    print('<h3>' . $ipvar . '</h3>' . $ipname);
  }

  if ($_GET['command'] == 'makegrid') //deprecated
  {
    $ips = file_get_contents('/var/www/html/ipscan/ips.txt');
    if ($ips == NULL){
      print('<br>' . 'please populate grid first which will create ips.txt');
      return;
    }
    $arrayips = explode(PHP_EOL, $ips);
    $ipinfo = array();
    foreach($arrayips as $ipvar)
    {
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, 'https://whois.toolforge.org/gateway.py?lookup=true&ip=' . $ipvar);
      curl_setopt($ch, CURLOPT_HEADER, 0);
      curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; rv:1.7.3) Gecko/20041001 Firefox/0.10.1");
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); //very important
      $dat = curl_exec($ch);
      curl_close($ch);
      //parse data into an array
      $subarr = array();
      $ipname1 = explode("nets</th><td>", $dat);
      $ipname2 = explode('</table>', $ipname1[1]);
      $ipname = $ipname2[0] . '</table><br><br>';
      print('<h3>' . $ipvar . '</h3>' . $ipname);
    }
  }
 ?>
