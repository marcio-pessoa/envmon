#!/usr/bin/env python
# -*- coding: utf-8 -*-
"""
python.py

Author: Marcio Pessoa <marcio@pessoa.eti.br>

Change log:
"""

try:
    from getopt import getopt, GetoptError
    import json
    import os
    from select import select
    from smtplib import SMTP
    from sys import exit, argv
    import tweepy
except ImportError, err:
    print "Critical - %s" % (err)
    exit(1)


# Program definitions
PROGRAM_NAME = "tweety"
PROGRAM_DESCRIPTION = "Post messages on Twitter"
PROGRAM_VERSION = "0.01b"
PROGRAM_DATE = "2016-06-19"
PROGRAM_COPYRIGHT = "Copyright (c) 2016-2016 Marcio Pessoa"
PROGRAM_LICENSE = "undefined. There is NO WARRANTY."
PROGRAM_WEBSITE = "http://pessoa.eti.br/"
PROGRAM_CONTACT = "Marcio Pessoa <marcio.pessoa@sciemon.com>"


def message_version():
    """Display help message"""
    print PROGRAM_NAME + " - " + PROGRAM_DESCRIPTION + ", " + \
        PROGRAM_VERSION, "("+PROGRAM_DATE+")"


def message_usage():
    """Display help message"""
    print PROGRAM_NAME
    print PROGRAM_DESCRIPTION
    print ""
    print "Usage: " + PROGRAM_NAME + " -h"
    print ""
    print "Options:"
    print "  -c, --config              configuration file (config.json)"
    print "  -m, --message             message to tweet"
    print ""
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


def main():
    """Display help message"""
    config_file = 'config.json'
    service_bind = ''
    service_port = 2323
    device_addr = '127.0.0.1'
    device_port = '6571'
    message = ''
    try:
        opts, args = getopt(argv[1:], "hVc:m:",
                            ["version", "help",
                             "config=", "message="])
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
        elif o in ("-c", "--config"):
            config_file = str(a)
        elif o in ("-m", "--message"):
            message = str(a)
        else:
            assert False, "unhandled option"
            exit(1)
    # Check message size
    if len(message) == 0:
        print "Message can't be empty."
        exit(1)
    if len(message) > 140:
        print "Message size must be less than 140 characters."
        exit(1)
    # Open configuration file
    try:
        content = open(config_file).read()
    except IOError, err:
        print "Can't open configuration file."
        exit(1)
    try:
        # Import JSON data
        cfg = json.loads(content)
    except ValueError, err:
        print "Invalid configuration file."
        exit(2)
    access_token = cfg["notification"]["twitter"]['token']
    access_token_secret = cfg["notification"]["twitter"]['token_secret']
    consumer_secret = cfg["notification"]["twitter"]['consumer_secret']
    consumer_key = cfg["notification"]["twitter"]['consumer_key']
    auth = tweepy.OAuthHandler(consumer_key, consumer_secret)
    auth.set_access_token(access_token, access_token_secret)
    api = tweepy.API(auth)
    status = api.update_status(status=message)
    print "Twitter: \"" + message + "\" sent successfully."


if __name__ == '__main__':
    main()
