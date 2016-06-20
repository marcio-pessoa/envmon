var local_time = new Date();
var start_moment = local_time.getTime();

function systemDateAndTime() {
  var local_time = new Date();
  var computed = new Date(remote_timestamp * 1000 + 
                          local_time.getTime() - start_moment);
  var Y = computed.getFullYear();
  var M = computed.getMonth() + 1;
  var D = computed.getDate();
  var h = computed.getHours();
  var m = computed.getMinutes();
  var s = computed.getSeconds();
  M = addZero(M);
  D = addZero(D);
  h = addZero(h);
  m = addZero(m);
  s = addZero(s);
  document.getElementById('date').innerHTML = Y + "-" + M + "-" + D;
  document.getElementById('time').innerHTML = h + ":" + m + ":" + s;
  var t = setTimeout(systemDateAndTime, 100);
}

function systemUptime() {
  var local_time = new Date();
  var computed = new Date(remote_uptime * 1000 + 
                          local_time.getTime() - start_moment);
  var D = Math.floor(computed.getTime() / 1000 / 60 / 60 / 24);
  var h = Math.floor(computed.getTime() / 1000 / 60 / 60) % 24;
  var m = Math.floor(computed.getTime() / 1000 / 60) % 60;
  var s = Math.floor(computed.getTime() / 1000) % 60;
  h = addZero(h);
  m = addZero(m);
  s = addZero(s);
  var days = "";
  if (D > 0) {
    days = D + ", ";
  }
  document.getElementById('uptime').innerHTML = days + h + ":" + m + ":" + s;
  var t = setTimeout(systemUptime, 100);
}

function timeToNextSeason() {
  var local_time = new Date();
  var t = seconds_to_next_season * 1000 - (local_time.getTime() - start_moment)
  if (t < 1) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (xhttp.readyState == 4 && xhttp.status == 200) {
        seconds_to_next_season = xhttp.responseText;
      }
    };
    xhttp.open("GET", "/www/?content=status&sub=end", true);
    xhttp.send();
    t = seconds_to_next_season * 1000 - (local_time.getTime() - start_moment);
  }
  var computed = new Date(t);
  var D = Math.floor(computed.getTime() / 1000 / 60 / 60 / 24);
  var h = Math.floor(computed.getTime() / 1000 / 60 / 60) % 24;
  var m = Math.floor(computed.getTime() / 1000 / 60) % 60;
  var s = Math.floor(computed.getTime() / 1000) % 60;
  h = addZero(h);
  m = addZero(m);
  s = addZero(s);
  var days = "";
  if (D > 0) {
    days = D + ", ";
  }
  document.getElementById('nextseasonin').innerHTML = days + h + ":" + m + ":" + s;
  var t = setTimeout(timeToNextSeason, 100);
}

function addZero(i) {
  if (i < 10) {
    i = "0" + i;
  }
  return i;
}
