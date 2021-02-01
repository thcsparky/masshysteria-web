<?php


?>

<html>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

<div class="panel panel-default">
  <div class="panel-heading"><b>IP Logs</b>
    <button type="button" class="button btn-primary" onclick="populate();">Populate List</button>
    <button type="button" class="button btn-danger" onclick="clearLog();">Clear NGinX log</button>

    <button type="button" class="button btn-default" onclick="makegrid();">Recreate WHOIS grid</button>


  </div>
  <div class="panel-body" id="sett">

  </div>

<script>

function populate() {
var client = new HttpClient();
client.get('http://159.203.64.37/ipscan/backend.php?command=loadIPS', function(response){
  //response here
  document.getElementById("sett").innerHTML = response;
})
}
function clearLog() {
var client = new HttpClient();
client.get('http://159.203.64.37/ipscan/backend.php?command=clearlog', function(response){
  document.getElementById("sett").innerHTML = response;
})
}
function loadGrid() {
var client = new HttpClient();
client.get('http://159.203.64.37/ipscan/backend.php?command=loadgrid', function(response){
  document.getElementById("sett").innerHTML = response;
})
}

function makegrid() {
var client = new HttpClient();

client.get('http://159.203.64.37/ipscan/backend.php?command=loadIPS', function(response){
  var ips = response;
  var iplist = ips.split('<br>');
  var curtext = document.getElementById("sett").innerHTML;
  //another get inside this function
  var i;
  for(i = 0; i < iplist.length; i++)
  {
    if (iplist[i] == ''){ break; }
    var client2 = new HttpClient();
    client2.get('http://159.203.64.37/ipscan/backend.php?command=getipgrid&ip=' + iplist[i], function(response){
      curtext += response;
      document.getElementById('sett').innerHTML = curtext;
    })
  }
})
}
//continued

function etcetera(){
  var client = new HttpClient();
  client.get('http://159.203.64.37/ipscan/backend.php?command=etc', function(response){
    document.getElementById('sett').innerHTML = response;
  })
}

var HttpClient = function() { //ReUsable http requester
  this.get = function(aUrl, aCallback){
    var anHttpRequest = new XMLHttpRequest();
    anHttpRequest.onreadystatechange =function() {
      if (anHttpRequest.readyState == 4 && anHttpRequest.status == 200)
        aCallback(anHttpRequest.responseText);
    }
    anHttpRequest.open( "GET", aUrl, true);
    anHttpRequest.send( null );
  }
}
</script>
</html>
