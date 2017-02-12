/**
 * @fileoverview Description of file, its uses and information
 * about its dependencies.
 */

/**
 * Operates on an instance of MyClass and returns something.
 * @param {project.MyClass} obj Instance of MyClass which leads to a long
 *     comment that needs to be wrapped to two lines.
 * @return {boolean} Whether something occurred.
 */
function getStatus() {
  var value;
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (xhttp.readyState == 4 && xhttp.status == 200) {
      obj = JSON.parse(xhttp.responseText);
      // Temperature
      document.getElementById('environment_temperature_value').innerHTML = 
        obj.environment.temperature.value + " &#8451";
      document.getElementsByClassName('environment_temperature_status')[0].id = 
        "status" + obj.environment.temperature.status;
      document.getElementsByClassName('environment_temperature_moment')[0].title =
        obj.environment.temperature.moment;
      // Humidity
      document.getElementById('humidity_value').innerHTML = 
        obj.environment.humidity.value + "%";
      document.getElementsByClassName('humidity_status')[0].id = 
        "status" + obj.environment.humidity.status;
      document.getElementsByClassName('humidity_moment')[0].title =
        obj.environment.humidity.moment;
      // Moisture
      document.getElementById('moisture_value').innerHTML = 
        obj.environment.moisture.value + "%";
      document.getElementsByClassName('moisture_status')[0].id = 
        "status" + obj.environment.moisture.status;
      document.getElementsByClassName('moisture_moment')[0].title =
        obj.environment.moisture.moment;
      // Water
      document.getElementById('water_value').innerHTML = 
        obj.environment.water.value + "%";
      document.getElementsByClassName('water_status')[0].id = 
        "status" + obj.environment.water.status;
      document.getElementsByClassName('water_moment')[0].title =
        obj.environment.water.moment;
      // Season
      // document.getElementById('season_value').innerHTML = 
        obj.season.current;
      document.getElementsByClassName('season_status')[0].id = 
        "status" + 0;
      document.getElementsByClassName('season_moment')[0].title =
        obj.season.begin + "~" + obj.season.end;
      // CPU
      document.getElementById('cpu_value').innerHTML = 
        obj.system.cpu.value + "%";
      document.getElementsByClassName('cpu_status')[0].id = 
        "status" + obj.system.cpu.status;
      document.getElementsByClassName('cpu_moment')[0].title =
        obj.system.cpu.moment;
      // Memory
      document.getElementById('memory_value').innerHTML =
        obj.system.memory.value + "%";
      document.getElementsByClassName('memory_status')[0].id = 
        "status" + obj.system.memory.status;
      document.getElementsByClassName('memory_moment')[0].title =
        obj.system.memory.moment;
      // Swap
      document.getElementById('swap_value').innerHTML = 
        obj.system.swap.value + "%";
      document.getElementsByClassName('swap_status')[0].id = 
        "status" + obj.system.swap.status;
      document.getElementsByClassName('swap_moment')[0].title =
        obj.system.swap.moment;
      // System Temperature
      document.getElementById('system_temperature_value').innerHTML = 
        obj.system.temperature.value + " &#8451";
      document.getElementsByClassName('system_temperature_status')[0].id = 
        "status" + obj.system.temperature.status;
      document.getElementsByClassName('system_temperature_moment')[0].title =
        obj.system.temperature.moment;
      // Fan
      document.getElementById('fan_value').innerHTML = 
        obj.system.fan.value + "%";
      document.getElementsByClassName('fan_status')[0].id = 
        "status" + obj.system.fan.status;
      document.getElementsByClassName('fan_moment')[0].title =
        obj.system.fan.moment;
      // Internal Storage
      document.getElementById('intstorage_value').innerHTML = 
        obj.system.intstorage.value + "%";
      document.getElementsByClassName('intstorage_status')[0].id = 
        "status" + obj.system.intstorage.status;
      document.getElementsByClassName('intstorage_moment')[0].title =
        obj.system.intstorage.moment;
      // External Storage
      document.getElementById('extstorage_value').innerHTML = 
        obj.system.extstorage.value + "%";
      document.getElementsByClassName('extstorage_status')[0].id = 
        "status" + obj.system.extstorage.status;
      document.getElementsByClassName('extstorage_moment')[0].title =
        obj.system.extstorage.moment;
    }
  };
  xhttp.open("GET", "/www/?content=status", true);
  xhttp.send();
  var t = setTimeout(getStatus, 1000);
}

/**
 * Operates on an instance of MyClass and returns something.
 * @param {project.MyClass} obj Instance of MyClass which leads to a long
 *     comment that needs to be wrapped to two lines.
 * @return {boolean} Whether something occurred.
 */
function getSystemStatus() {
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    var value = "-";
    var status = "status3";
    var moment = "0000-00-00 00:00:00";
    if (xhttp.readyState == 4 && xhttp.status == 200) {
      obj = JSON.parse(xhttp.responseText);
      value = obj.daemon.status;
      status = "status" + obj.daemon.status_id;
      moment = obj.daemon.moment;
      document.getElementById('daemon_value').innerHTML = value;
      document.getElementsByClassName('daemon_status')[0].id = status;
      document.getElementsByClassName('daemon_moment')[0].title = moment;
    }
  };
  xhttp.open("GET", "/www/?content=status&sub=daemon", true);
  xhttp.send();
  var t = setTimeout(getSystemStatus, 1000);
}
