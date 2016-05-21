var local_time = new Date();
var start_moment = local_time.getTime();
function startTime() {
    var local_time = new Date();
    var computed = new Date(remote_seconds * 1000 + 
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
    document.getElementById('clock').innerHTML = h + ":" + m + ":" + s;
    var t = setTimeout(startTime, 500);
}
function addZero(i) {
    if (i < 10) {
        i = "0" + i;
    }
    return i;
}
