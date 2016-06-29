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
      document.getElementById('cpu_value').innerHTML = 
        obj.system.cpu.value;
      // document.getElementById('cpu_moment').innerHTML =
        // obj.system.cpu.moment;
      // document.getElementById('cpu_status').innerHTML =
        // obj.system.cpu.status;
      document.getElementById('memory_value').innerHTML =
        obj.system.memory.value;
      document.getElementById('swap_value').innerHTML =
        obj.system.swap.value;
      document.getElementById('temperature_value').innerHTML =
        obj.system.temperature.value;
      document.getElementById('fan_value').innerHTML =
        obj.system.fan.value;
      document.getElementById('intstorage_value').innerHTML =
        obj.system.intstorage.value;
      document.getElementById('extstorage_value').innerHTML =
        obj.system.extstorage.value;
    }
  };
  xhttp.open("GET", "/www/?content=status", true);
  xhttp.send();
  var t = setTimeout(getStatus, 5000);
}

/**
 * Operates on an instance of MyClass and returns something.
 * @param {project.MyClass} obj Instance of MyClass which leads to a long
 *     comment that needs to be wrapped to two lines.
 * @return {boolean} Whether something occurred.
 */
function getSystemStatus() {
  var value;
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (xhttp.readyState == 4 && xhttp.status == 200) {
      obj = JSON.parse(xhttp.responseText);
      document.getElementById('status').innerHTML = obj.daemon.status;
      document.getElementById('status_id').innerHTML =
        obj.daemon.status_id;
    }
  };
  xhttp.open("GET", "/www/?content=status&sub=daemon", true);
  xhttp.send();
  var t = setTimeout(getSystemStatus, 1000);
}
