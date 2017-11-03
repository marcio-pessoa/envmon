#!/usr/bin/python

import urllib2

req = urllib2.Request('https://pessoa.eti.br/ip')
response = urllib2.urlopen(req)
the_page = response.read()
print the_page
