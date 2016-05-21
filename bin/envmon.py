#!/usr/bin/env python
# -*- coding: utf-8 -*-
"""
envmon.py

Author: Marcio Pessoa <marcio.pessoa@sciemon.com>

Change log: Check CHANGELOG file for more information.
"""

try:
    import sys
    import getopt
    import json
    import os
    import random
    import telnetlib
    from smtplib import SMTP
    from time import sleep
    from timer import Timer
except ImportError, err:
    print "Critical - %s" % (err)
    sys.exit(2)


# Program definitions
PROGRAM_NAME = "envmon.py"
PROGRAM_VERSION = "0.01b"
PROGRAM_DATE = "2016-05-14"


def loop():
    if relay1:
        command_send()


def message_version():
    """Display version message"""
    print PROGRAM_NAME, PROGRAM_VERSION, "(" + PROGRAM_DATE + ")"

def message_usage():
    """Display help message"""
    print PROGRAM_NAME
    print ""
    print "Usage: " + PROGRAM_NAME + " -h"
    print ""
    print "Options:"
    print "  -f, --file                config file"
    print "  -r, --reload              reload config file"
    print "  -c, --command             command to run"
    print "  -d, --device              communication device and TCP port"
    print "                            (default: localhost:6571)"
    print "  -p, --port                TCP port to listen (def: 2323)"
    print "  -a, --address             IP address to bind (def: all)"
    print ""
    print "  -h, --help                display this help and exit"
    print "  -V, --version             output version information and exit"
    print ""
    print "Examples:"
    print "  " + PROGRAM_NAME + " -f config.json"
    print "  " + PROGRAM_NAME + " -i x2"
    print "  " + PROGRAM_NAME + " -i useless --run"
    print "  " + PROGRAM_NAME + " --id x6 --verify"
    print "  " + PROGRAM_NAME + " --id envmon -I network --upload"
    print ""
    print "Report", PROGRAM_NAME, "bugs to marcio@pessoa.eti.br"
    message_version()

def message_list():
    """Display configured devices"""
    print("Id\tName\tVersion\t\tDescription")
    print("----------------------------------------------------")
    for i in device.items_extended():
        print(str(i[0]) + "\t" + str(i[1]) + "\tMark " + str(i[2]) +
              "\t\t" + str(i[3]))


class Config:
    """docstring"""
    def __init__(self, id, platform, mark, description, architecture,
                 system_path, system_logs,
                 device_path, device_speed,
                 network_address,
                 device_check = True, device_network = True):
        self.id = id
        self.platform = platform
        self.mark = mark
        self.description = description
        self.architecture = architecture
        self.path = system_path
        self.arduino_file = self.path[self.path.rfind("/", 0):] + ".ino"
        self.logs = system_logs
        self.device_path = device_path
        self.device_speed = device_speed
        self.device_check = device_check
        self.network_address = network_address
        self.device_port = self.device_path

    def run(self):
        """Connect a terminal to device console."""
        if self.device_check:
            self.check_device()
        print "Communication device:", \
              os.popen("readlink -f " + self.device_path).read()
        return_code = os.system(TERMINAL_PROGRAM +
                                " --port=" + self.device_path +
                                " --baud=" + str(self.device_speed) +
                                " --echo --quiet")
        if return_code != 0:
            print "Return code:", return_code
        sys.exit(return_code)

    def verify(self):
        """Run Arduino program and verify a sketch."""
        self.presentation()
        return_code = os.system(ARDUINO_PROGRAM +
                                " --verify --board " +
                                self.architecture + " " +
                                self.path + "/" + 
                                self.arduino_file)
        if return_code != 0:
            print "Return code:", return_code
            if return_code > 127:
                return_code = 1
        sys.exit(return_code)

    def upload(self):
        """Run Arduino program and upload a sketch."""
        if self.device_check:
            project.check_device()
        project.presentation()
        if not self.network_address:
            device = os.popen("readlink -f " + self.device_path).read()
        else:
            device = self.device_path
        print "Communication device:", device
        return_code = os.system(ARDUINO_PROGRAM + " --upload --board " +
                                self.architecture + " " +
                                self.path + "/" + self.arduino_file +
                                " --port " + self.device_port)
        if return_code != 0:
            print "Return code:", return_code
            if return_code > 127:
                return_code = 1
        else:
            f = open(self.logs + "/.buildno", 'r')
            buildno = f.read()
            f.close()
            if buildno == "":
                buildno = 1
            else:
                buildno = int(buildno) + 1
            f = open(self.logs + "/.buildno", 'w')
            f.write(str(buildno))
            print "Build number:", buildno
        sys.exit(return_code)

    def comm(self, interface):
        """"""
        self.interface = interface
        if self.interface == "serial":
            self.device_port = self.device_path
        elif self.interface == "network":
            self.device_port = self.network_address
            self.device_check = False
            self.device_network = True
        else:
            print "Invalid interface."
            sys.exit(2)

    def check_device(self):
        """Check if device is pysically conected."""
        if not os.path.islink(self.device_path):
            print "Device not found:", self.device_path
            sys.exit(1)

    def presentation(self):
        """"""
        print "Project: " + self.platform + " Mark " + self.mark + " (" + \
              self.description + ")"
        print "Platform:", self.architecture
        print "Process started at: (" + time.strftime('%Y-%m-%d %H:%M:%S') + ")"



def main():
    global device, project
    project_id = ""
    project_interface = ""
    project_action = "run"

    try:
        opts, args = getopt.getopt(sys.argv[1:], "li:rvuI:c:hV",
                                   ["list", "id=", "run", "verify", "upload",
                                    "interface=", "config=", "help", "version"])
    except getopt.GetoptError as err:
        # print help information and exit:
        print str(err)  # will print something like "option -a not recognized"
        message_usage()
        sys.exit(2)




    configuration_file = os.path.join(os.getenv('HOME', ''), '.devices.json')
    for o, a in opts:
        if o in ("-l", "--list"):
            project_action = "list"
        elif o in ("-i", "--id"):
            project_id = a
        elif o in ("-r", "--run"):
            project_action = "run"
        elif o in ("-v", "--verify"):
            project_action = "verify"
        elif o in ("-u", "--upload"):
            project_action = "upload"
        elif o in ("-I", "--interface"):
            project_interface = a
        elif o in ("-c", "--config"):
            configuration_file = a
        elif o in ("-h", "--help"):
            message_usage()
            sys.exit(0)
        elif o in ("-V", "--version"):
            message_version()
            sys.exit(0)
        else:
            assert False, "unhandled option"
    # Create device object and load configuration file
    device = DeviceProperties(configuration_file)
    # Just list projects
    if project_action == "list":
        message_list()
        sys.exit(0)
    # Do the selected task (run, verify or upload)
    if not project_id == "":
        # Load device properties
        device.device(project_id)
        project = Project(device.id,
                          device.system_plat,
                          device.system_mark,
                          device.system_desc,
                          device.system_arch,
                          device.system_path,
                          device.system_logs,
                          device.comm_serial_path, device.comm_serial_speed,
                          device.comm_network_address,
                          True, True)
        # Device defined?
        if not project_interface == "":
            project.comm(project_interface)
        if project_action == "run":
            project.run()
        elif project_action == "verify":
            project.verify()
        elif project_action == "upload":
            project.upload()
    else:
        message_usage()
        sys.exit(2)

    relay1 = Timer()
    while True:
        loop()
        sleep(0.25)

if __name__ == '__main__':
    main()
