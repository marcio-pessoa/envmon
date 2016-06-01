#!/usr/bin/env python
# -*- coding: utf-8 -*-
"""
envmon.py

Author: Marcio Pessoa <marcio@pessoa.eti.br>

Change log:
"""

try:
    from sys import exit
    from socket import socket, AF_INET, SOCK_STREAM, SOL_SOCKET, SO_REUSEADDR
    from socket import gethostname
    from select import select
    from smtplib import SMTP
    # import time
    from time import sleep, time
    from timer import Timer
except ImportError, err:
    print "Critical - %s" % (err)
    exit(2)


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
    print "  -p, --port                TCP port to listen (def: 2323)"
    print "  -b, --bind                IP address to bind (def: all)"
    print ""
    print "  -h, --help                display this help and exit"
    print "  -V, --version             output version information and exit"
    print ""
    print "Examples:"
    print "  " + PROGRAM_NAME + " -c config.json"
    print "  " + PROGRAM_NAME + " -c config.json -b localhost -d localhost:6571"
    print "  " + PROGRAM_NAME + " -b localhost -p 2424 -d localhost:6571"
    print ""
    print "Report", PROGRAM_NAME, "bugs to marcio@pessoa.eti.br"
    message_version()


def try_bind(socket, address, port, timeout=30):
    start_time = time()
    while (time() - start_time) < timeout:
        try:
            return socket.bind((address, port))
        except:
            sleep(1)
            # try one last time, just to throw up an exception...
    return socket.bind((address, port))


class Console:
    """docstring"""
    def __init__(self, port=2323):
        server = socket(AF_INET, SOCK_STREAM)
        server.setsockopt(SOL_SOCKET, SO_REUSEADDR, 1)
        try_bind(server, '127.0.0.1', port)
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
        print chunk
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
        sock.close()
        self.clients.remove(sock)
        self.sockets.remove(sock)
        del self.clients_sendbuffer[sock]


def main():
    console = Console()
    while True:
        console.run()


if __name__ == '__main__':
    main()
