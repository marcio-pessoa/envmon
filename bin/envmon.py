#!/usr/bin/env python
# -*- coding: utf-8 -*-
"""
envmon.py

Author: Marcio Pessoa <marcio@pessoa.eti.br>

Change log:
"""

try:
    import collections
    import datetime
    from datetime import datetime, timedelta
    from getopt import getopt, GetoptError
    import httplib
    import json
    # import os
    import re
    from math import floor
    from select import select
    from smtplib import SMTP
    from socket import socket, AF_INET, SOCK_STREAM, SOL_SOCKET, SO_REUSEADDR
    from socket import gethostname
    import subprocess
    from string import split
    from sys import exit, argv, stdout
    import telnetlib
    from time import sleep, time
    from timer import Timer
    from check import checker
except ImportError, err:
    print "Critical - %s" % (err)
    exit(1)


# Program definitions
PROGRAM_NAME = "envmon"
PROGRAM_DESCRIPTION = "Environment Monitor"
PROGRAM_VERSION = "0.01b"
PROGRAM_DATE = "2016-06-19"
PROGRAM_COPYRIGHT = "Copyright (c) 2015-2016 Marcio Pessoa"
PROGRAM_LICENSE = "undefined. There is NO WARRANTY."
PROGRAM_WEBSITE = "http://pessoa.eti.br/"
PROGRAM_CONTACT = "Marcio Pessoa <marcio.pessoa@sciemon.com>"

OK = 0
WARNING = 1
CRITICAL = 2
UNKNOWN = 3


def message_version():
    """Display help message"""
    print PROGRAM_NAME + " - " + PROGRAM_DESCRIPTION + ", " + PROGRAM_VERSION, \
        "(" + PROGRAM_DATE + ")"


def message_usage():
    """Display help message"""
    print PROGRAM_NAME
    print PROGRAM_DESCRIPTION
    print ""
    print "Usage: " + PROGRAM_NAME + " -h"
    print ""
    print "Options:"
    print "  -c, --config              configuration file (config.json)"
    print "  -d, --device              communication device and TCP port"
    print "                            (localhost:6571)"
    print "  -p, --port                TCP port to listen (2323)"
    print "  -b, --bind                IP address to bind (all)"
    print ""
    print "  -v, --verbose             Verbose mode"
    print "  -h, --help                display this help and exit"
    print "  -V, --version             output version information and exit"
    print ""
    print "Examples:"
    print "  " + PROGRAM_NAME + " -c config.json"
    print "  " + PROGRAM_NAME + " -c config.json -b localhost -d localhost:6571"
    print "  " + PROGRAM_NAME + " -b localhost -p 2424 -d 127.0.0.1:6571"
    print ""
    print "Report", PROGRAM_NAME, "bugs to marcio@pessoa.eti.br"
    message_version()


def timestamp():
    """Return string ANSI timestamp"""
    ts = time()
    st = datetime.fromtimestamp(ts).strftime('%Y-%m-%d %H:%M:%S')
    return st


def echo(string, debug=True):
    """"""
    if debug:
        print string,


def echoln(string, debug=True):
    """"""
    if debug:
        print string


def log(string, debug=True):
    """"""
    if debug:
        print timestamp() + " - " + string,


def logln(string, debug=True):
    """"""
    if debug:
        print timestamp() + " - " + string


def try_bind(socket, address, port, timeout=30):
    """Display help message"""
    start_time = time()
    while (time() - start_time) < timeout:
        try:
            return socket.bind((address, port))
        except:
            sleep(1)
            # try one last time, just to throw up an exception...
    return socket.bind((address, port))


class Console:
    """"""
    def __init__(self, bind='127.0.0.1', port=2323):
        server = socket(AF_INET, SOCK_STREAM)
        server.setsockopt(SOL_SOCKET, SO_REUSEADDR, 1)
        try_bind(server, bind, port)
        server.listen(1)  # No connection backlog
        server.setblocking(0)
        self.server = server
        self.clients = []
        self.clients_sendbuffer = {}
        self.sockets = [server]
        # Data waiting to be transferred to sockets
        self.sendbuffer = ''
        # Data waiting to be transferred to PacketProcessor
        self.recvbuffer = ''

    def run(self):
        sockets_to_read_from, sockets_to_write_to, err = \
            select(self.sockets, [], [], 0)
        # Accept new connections
        if self.server in sockets_to_read_from:
            logln("Conecting client.")
            self.accept()
            sockets_to_read_from.remove(self.server)
        # Read from sockets
        if len(self.sendbuffer) < 1024:
            for sock in sockets_to_read_from:
                self.socket_receive(sock)
        # Write buffers to sockets
        sockets_to_read_from, sockets_to_write_to, err = \
            select([], self.clients, [], 0)
        for client in sockets_to_write_to:
            try:
                buff = self.clients_sendbuffer[client]
                sent = client.send(buff)
                self.clients_sendbuffer[client] = buff[sent:]
            except:
                self.close(client)
        # Drop starving clients
        for client in self.clients:
            if len(self.clients_sendbuffer[client]) > 8192:
                self.close(client)

    def socket_receive(self, client):
        chunk = client.recv(1024)
        if chunk == '':
            self.close(client)
            return None
        self.recvbuffer += chunk
        # send chunk as echo to all other clients
        for current_client in self.clients:
            if current_client != client:
                self.clients_sendbuffer[current_client] += chunk

    def write(self, data):
        # send chunk to all clients
        for c in self.clients:
            self.clients_sendbuffer[c] += data

    def read(self, maxlen):
        if maxlen > len(self.recvbuffer):
            res = self.recvbuffer
            self.recvbuffer = ''
        else:
            res = self.recvbuffer[:maxlen]
            self.recvbuffer = self.recvbuffer[maxlen:]
        return res

    def available(self):
        return len(self.recvbuffer)

    def is_connected(self):
        return len(self.clients) > 0

    def accept(self):
        (client, address) = self.server.accept()
        self.sockets.append(client)
        self.clients.append(client)
        self.clients_sendbuffer[client] = ''

    def close(self, sock):
        logln("Disconecting client.")
        sock.close()
        self.clients.remove(sock)
        self.sockets.remove(sock)
        del self.clients_sendbuffer[sock]


def update(d, u):
    for k, v in u.iteritems():
        if isinstance(v, collections.Mapping):
            r = update(d.get(k, {}), v)
            d[k] = r
        else:
            d[k] = u[k]
    return d


def statusWrite(chave):
    global status
    status_file = "/opt/envmon/var/status.json"
    # Read status file
    try:
        file = open(status_file, 'r')
        content = file.read()
        file.close()
    except IOError, err:
        echoln("Can't open status file.")
        try:
            echo("Trying to create a new status file...")
            content = '{}'
            status = json.loads(content)
            file = open(status_file, 'w')
            json.dump(status, file, indent=2)
            file.close()
        except IOError, err:
            echoln("Fail.")
            exit(1)
        echoln("Done.")
    try:
        status = json.loads(content)
    except ValueError, err:
        try:
            echoln("Invalid status file.")
            echo("Trying to create a new status file...")
            content = '{}'
            status = json.loads(content)
            file = open(status_file, 'w')
            json.dump(status, file, indent=2)
            file.close()
            status = json.loads(content)
        except IOError, err:
            echoln("Fail.")
            exit(1)
        echoln("Done.")
        exit(2)
    # Change payload
    content = update(status, chave)
    # Write status file
    try:
        file = open(status_file, 'w')
        json.dump(content, file, indent=2)
        file.close()
    except IOError, err:
        echoln("Can't write status file.")
        exit(1)


def sensorsRead():
    logln("Updating status...", debug)
    getCpuData()
    getMemData()
    getSwapData()
    getIntStorageData()
    getExtStorageData()
    checkSeason()
    logln("Status updated.", debug)


def pumpSquirt():
    logln("Squirting...")


def getCpuData():
    n = subprocess.check_output("/opt/envmon/bin/check_system.sh cpu",
                                shell=True)
    n = float(re.sub(r'[^\d.]+', '', n))
    value = round(n, 0)
    status = checker(value,
                     cfg["threshold"]["cpu"]["warning"],
                     cfg["threshold"]["cpu"]["critical"])
    r = {
        'system': {
            'cpu': {
                'value': 0,
                'status': 3,
                'moment': ''
            }
        }
    }
    r['system']['cpu']['value'] = value
    r['system']['cpu']['status'] = status
    r['system']['cpu']['moment'] = timestamp()
    statusWrite(r)


def getMemData():
    n = subprocess.check_output("/opt/envmon/bin/check_system.sh mem",
                                shell=True)
    n = float(re.sub(r'[^\d.]+', '', n))
    value = round(n, 0)
    status = checker(value,
                     cfg["threshold"]["memory"]["warning"],
                     cfg["threshold"]["memory"]["critical"])
    r = {
        'system': {
            'memory': {
                'value': 0,
                'status': 3,
                'moment': ''
            }
        }
    }
    r['system']['memory']['value'] = value
    r['system']['memory']['status'] = status
    r['system']['memory']['moment'] = timestamp()
    statusWrite(r)


def getSwapData():
    n = subprocess.check_output("/opt/envmon/bin/check_system.sh swap",
                                shell=True)
    n = float(re.sub(r'[^\d.]+', '', n))
    value = round(n, 0)
    status = checker(value,
                     cfg["threshold"]["swap"]["warning"],
                     cfg["threshold"]["swap"]["critical"])
    r = {
        'system': {
            'swap': {
                'value': 0,
                'status': 3,
                'moment': ''
            }
        }
    }
    r['system']['swap']['value'] = value
    r['system']['swap']['status'] = status
    r['system']['swap']['moment'] = timestamp()
    statusWrite(r)


def getIntStorageData():
    n = subprocess.check_output("/opt/envmon/bin/check_system.sh root",
                                shell=True)
    n = float(re.sub(r'[^\d.]+', '', n))
    value = round(n, 0)
    status = checker(value,
                     cfg["threshold"]["intstorage"]["warning"],
                     cfg["threshold"]["intstorage"]["critical"])
    r = {
        'system': {
            'intstorage': {
                'value': 0,
                'status': 3,
                'moment': ''
            }
        }
    }
    r['system']['intstorage']['value'] = value
    r['system']['intstorage']['status'] = status
    r['system']['intstorage']['moment'] = timestamp()
    statusWrite(r)


def getExtStorageData():
    n = subprocess.check_output("/opt/envmon/bin/check_system.sh sd",
                                shell=True)
    n = float(re.sub(r'[^\d.]+', '', n))
    value = round(n, 0)
    status = checker(value,
                     cfg["threshold"]["extstorage"]["warning"],
                     cfg["threshold"]["extstorage"]["critical"])
    r = {
        'system': {
            'extstorage': {
                'value': 0,
                'status': 3,
                'moment': ''
            }
        }
    }
    r['system']['extstorage']['value'] = value
    r['system']['extstorage']['status'] = status
    r['system']['extstorage']['moment'] = timestamp()
    statusWrite(r)


def getWiFiSignalStrength():
    n = subprocess.check_output("/opt/envmon/bin/check_system.sh wifi",
                                shell=True)
    n = float(re.sub(r'[^\d.]+', '', n))
    value = round(n, 0)
    status = checker(value,
                     cfg["threshold"]["wifisignal"]["warning"],
                     cfg["threshold"]["wifisignal"]["critical"],
                     False, False, True)
    r = {
        'system': {
            'wifisignal': {
                'value': 0,
                'status': 3,
                'moment': ''
            }
        }
    }
    r['system']['wifisignal']['value'] = value
    r['system']['wifisignal']['status'] = status
    r['system']['wifisignal']['moment'] = timestamp()
    statusWrite(r)


def getSysTempData(n):
    value = round(n, 1)
    status = checker(value,
                     cfg["threshold"]["temperature"]["sys"]["min"]["warning"],
                     cfg["threshold"]["temperature"]["sys"]["min"]["critical"],
                     cfg["threshold"]["temperature"]["sys"]["max"]["warning"],
                     cfg["threshold"]["temperature"]["sys"]["max"]["critical"],
                     True)
    r = {
        'system': {
            'temperature': {
                'value': 0,
                'status': 3,
                'moment': ''
            }
        }
    }
    r['system']['temperature']['value'] = value
    r['system']['temperature']['status'] = status
    r['system']['temperature']['moment'] = timestamp()
    statusWrite(r)


def getFanData(n):
    value = round(n, 0)
    status = checker(value,
                     cfg["threshold"]["fan"]["warning"],
                     cfg["threshold"]["fan"]["critical"])
    r = {'system': {'fan': {'value': 0, 'status': 3, 'moment': ''}}}
    r['system']['fan']['value'] = value
    r['system']['fan']['status'] = status
    r['system']['fan']['moment'] = timestamp()
    statusWrite(r)


def getWaterData(n):
    value = round(n, 1)
    status = checker(value,
                     cfg["threshold"]["water"]["warning"],
                     cfg["threshold"]["water"]["critical"],
                     False, False, True)
    r = {
        'environment': {
            'water': {
                'value': 0,
                'status': 3,
                'moment': ''
            }
        }
    }
    r['environment']['water']['value'] = value
    r['environment']['water']['status'] = status
    r['environment']['water']['moment'] = timestamp()
    statusWrite(r)


def getEnvTempData(n):
    value = round(n, 1)
    status = checker(value,
                     cfg["threshold"]["temperature"]["env"]["min"]["warning"],
                     cfg["threshold"]["temperature"]["env"]["min"]["critical"],
                     cfg["threshold"]["temperature"]["env"]["max"]["warning"],
                     cfg["threshold"]["temperature"]["env"]["max"]["critical"],
                     True)
    r = {
        "environment": {
            "temperature": {
                "value": 0,
                "status": 3,
                "moment": ""
            }
        }
    }
    r['environment']['temperature']['value'] = value
    r['environment']['temperature']['status'] = status
    r['environment']['temperature']['moment'] = timestamp()
    statusWrite(r)


def getMoistureData(n):
    value = round(n, 1)
    # TODO(Marcio): Coletar a estacao do ano de forma dinamica
    status = checker(value,
                     cfg["threshold"]["moisture"]["winter"]["warning"],
                     cfg["threshold"]["moisture"]["winter"]["critical"],
                     False, False,
                     True)
    r = {
        'environment': {
            'moisture': {
                'value': 0,
                'status': 3,
                'moment': ''
            }
        }
    }
    r['environment']['moisture']['value'] = value
    r['environment']['moisture']['status'] = status
    r['environment']['moisture']['moment'] = timestamp()
    statusWrite(r)


def getHumidityData(n):
    value = round(n, 1)
    status = checker(value,
                     cfg["threshold"]["humidity"]["warning"],
                     cfg["threshold"]["humidity"]["critical"],
                     False, False, True)
    r = {
        'environment': {
            'humidity': {
                'value': 0,
                'status': 3,
                'moment': ''
            }
        }
    }
    r['environment']['humidity']['value'] = value
    r['environment']['humidity']['status'] = status
    r['environment']['humidity']['moment'] = timestamp()
    statusWrite(r)


def getData(search, data):
    matchObj = re.match(r'(.*)' + search + ': (\d*\.\d)', data, re.S)
    if matchObj:
        n = float(matchObj.group(2))
        return n
    else:
        # logln("No match: " + str(data))
        return True


def checkSeason():
    # Read seasons file
    try:
        file = open(cfg['file']['seasons'], 'r')
        content = file.read()
        file.close()
    except IOError, err:
        echoln("Can't open seasons file.")
        exit(1)
    try:
        seasons = json.loads(content)
    except ValueError, err:
        echoln("Invalid seasons file.")
        exit(2)
    t = int(time())
    m = []
    for date in seasons['calendar']:
        n = int(datetime.strptime(date, "%Y-%m-%d %H:%M").strftime('%s'))
        m.append(n)
    m.sort()
    for i in m:
        if i > t:
            proxima = datetime.fromtimestamp(i).strftime('%Y-%m-%d %H:%M')
            break
        else:
            atual = datetime.fromtimestamp(i).strftime('%Y-%m-%d %H:%M')
    hemisphere = cfg['system']['hemisphere'].encode('utf8')
    estacao = seasons['calendar'][atual][hemisphere]
    # logln("Current season: " + atual, debug)
    # logln("Next season: " + proxima, debug)
    # logln("System hemisphere: " + hemisphere, debug)
    # logln("Season: " + estacao)
    r = {
            'season': {
                'begin': '0000-00-00 00:00',
                'end': '0000-00-00 00:00',
                'current': 'unknown'
            }
        }
    r['season']['begin'] = atual
    r['season']['end'] = proxima
    r['season']['current'] = estacao
    statusWrite(r)
    return i - t


def checkNotifications():
    log("Updating notifications...")
    # Read notifications file
    try:
        file = open(cfg['file']['notifications'], 'r')
        content = file.read()
        file.close()
    except IOError, err:
        echoln("Can't open notifications file.")
        exit(1)
    try:
        seasons = json.loads(content)
    except ValueError, err:
        echoln("Invalid notifications file.")
        exit(2)
    t = int(time())
    m = []
    for date in seasons['calendar']:
        n = int(datetime.strptime(date, "%Y-%m-%d %H:%M").strftime('%s'))
        m.append(n)
    m.sort()
    for i in m:
        if i > t:
            proxima = datetime.fromtimestamp(i).strftime('%Y-%m-%d %H:%M')
            break
        else:
            atual = datetime.fromtimestamp(i).strftime('%Y-%m-%d %H:%M')
    logln("Current notification: " + atual, debug)
    logln("Next notification: " + proxima, debug)


def notifySeason():
    pt = "Começou a #primavera! Seja bem vindo a estação mais florida."
    en = "It is #Spring! Welcome the most colorful season."
    ts = " 2016-09-22 14:21:00 UTC"
    msg_pt = pt + ts
    msg_en = en + ts
    subprocess.check_output("python /opt/envmon/bin/tweety.pyc" +
                            " -c /opt/envmon/cfg/config.json" +
                            " -m '" + msg_pt + "'",
                            shell=True)
    subprocess.check_output("python /opt/envmon/bin/tweety.pyc" +
                            " -c /opt/envmon/cfg/config.json" +
                            " -m '" + msg_en + "'",
                            shell=True)


def main():
    """Display help message"""
    global debug, cfg
    config_file = 'config.json'
    service_bind = ''
    service_port = 2323
    device_addr = '127.0.0.1'
    device_port = '6571'
    debug = False
    try:
        opts, args = getopt(argv[1:], "hVvc:d:p:b:",
                            ["version", "help", "verbose",
                             "config=", "device=", "port=", "bind="])
    except GetoptError as err:
        print str(err)
        message_usage()
        exit(1)
    for o, a in opts:
        if o in ("-V", "--version"):
            message_version()
            exit(0)
        elif o in ("-h", "--help"):
            message_usage()
            exit(0)
        elif o in ("-v", "--verbose"):
            debug = True
        elif o in ("-c", "--config"):
            config_file = str(a)
        elif o in ("-d", "--device"):
            device_addr = str(a.split(':')[0])
            device_port = int(a.split(':')[1])
        elif o in ("-p", "--port"):
            service_port = int(a)
        elif o in ("-b", "--bind"):
            service_bind = str(a)
        else:
            assert False, "unhandled option"
            exit(1)
    logln("Starting...")
    log("Loading configuration file...", debug)
    # Open configuration file
    try:
        file = open(config_file, 'r')
        content = file.read()
        file.close()
    except IOError, err:
        echoln("Can't open configuration file.")
        exit(1)
    try:
        global cfg
        cfg = json.loads(content)
    except ValueError, err:
        echoln("Invalid configuration file.")
        exit(2)
    echoln("Done.", debug)
    # Open TCP socket
    log("Listening on port " + str(service_port) + "...", debug)
    try:
        console = Console(service_bind, service_port)
    except:
        echoln("Failed to start listener.")
        exit(1)
    echoln("Done.", debug)
    # Open Console
    log("Opening Console on port 6571...", debug)
    try:
        tn = telnetlib.Telnet()
        tn.open('127.0.0.1', 6571, 2)
    except:
        echoln("Failed.")
        exit(1)
    echoln("Done.", debug)
    sensorsRead()
    # Timers
    cpu_timer = Timer(int(cfg["timer"]["system"]["cpu"]) * 1000)
    memory_timer = Timer(int(cfg["timer"]["system"]["memory"]) * 1000)
    systemp_timer = Timer(int(cfg["timer"]["system"]["temperature"]) * 1000)
    fan_timer = Timer(int(cfg["timer"]["system"]["fan"]) * 1000)
    swap_timer = Timer(int(cfg["timer"]["system"]["swap"]) * 1000)
    intstorage_timer = Timer(int(cfg["timer"]["system"]["intstorage"]) * 1000)
    extstorage_timer = Timer(int(cfg["timer"]["system"]["extstorage"]) * 1000)
    wifisignal_timer = Timer(int(cfg["timer"]["system"]["wifisignal"]) * 1000)
    water_timer = Timer(int(cfg["timer"]["environment"]["water"]) * 1000)
    envtemp_timer = Timer(int(cfg["timer"]["environment"]["temperature"]) *
                          1000)
    moisture_timer = Timer(int(cfg["timer"]["environment"]["moisture"]) * 1000)
    humidity_timer = Timer(int(cfg["timer"]["environment"]["humidity"]) * 1000)
    season_timer = Timer(checkSeason() * 1000)
    logln("Startup complete.")
    while True:
        console.run()
        command = console.read(1024)[:-2]
        if command != "":
            if command == 'kill':
                console.write("Shuting down...\r\n")
                sleep(5)
                break
            elif command == 'close' or command == 'bye' or command == 'exit':
                console.close(console.client)
            elif command == 'read':
                sensorsRead()
                console.write("Reading all sensors.\r\n")
            elif command == 'squirt':
                console.write("Squirting.\r\n")
                pumpSquirt()
            elif command == 'reload':
                msg = "Reloading " + PROGRAM_NAME + "..."
                log(msg, debug)
                console.write(msg)
                echoln("Done.", debug)
                console.write("Done.\r\n")

        if cpu_timer.check():
            getCpuData()
        if memory_timer.check():
            getMemData()
        if swap_timer.check():
            getSwapData()
        if intstorage_timer.check():
            getIntStorageData()
        if extstorage_timer.check():
            getExtStorageData()
        if wifisignal_timer.check():
            getWiFiSignalStrength()
        if season_timer.check():
            # notifySeason()
            season_timer.set(checkSeason() * 1000)

        try:
            if moisture_timer.check():
                tn.write("M30\n")
            if fan_timer.check():
                tn.write("M40\n")
            if water_timer.check():
                tn.write("M50\n")
            if systemp_timer.check():
                tn.write("M60 S1\n")
            if envtemp_timer.check():
                tn.write("M60 S2\n")
            if humidity_timer.check():
                tn.write("M70\n")
                tn.write("M20 R255 G0 B0\n")
        except:
            pass

        try:
            data = tn.read_until("Program end.", 1)
        except:
            pass

        n = getData('Temperature 2', data)
        if n is not True:
            getEnvTempData(n)

        n = getData('Humidity', data)
        if n is not True:
            getHumidityData(n)

        n = getData('Moisture', data)
        if n is not True:
            getMoistureData(n)

        n = getData('Distance', data)
        if n is not True:
            getWaterData(n)

        n = getData('Temperature 1', data)
        if n is not True:
            getSysTempData(n)

        n = getData('Fan', data)
        if n is not True:
            getFanData(n)

        sleep(0.05)
        stdout.flush()
    log("Shuting down listener...")
    echoln("Done.")
    log("Closing Console connection...")
    try:
        tn.close()
    except:
        echoln("Fail.")
    echoln("Done.")


if __name__ == '__main__':
    main()
