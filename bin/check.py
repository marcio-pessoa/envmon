#!/usr/bin/env python
# -*- coding: utf-8 -*-
"""
check.py

Author: Marcio Pessoa <marcio@pessoa.eti.br>

Change log:
"""


OK = 0
WARNING = 1
CRITICAL = 2
UNKNOWN = 3


def checker(value,
            max_warning, max_critical,
            min_warning=False, min_critical=False,
            inverse=False):

    # Maximum Critical
    if value >= max_critical:
        status = CRITICAL
    # Maximum Warning
    elif value >= max_warning:
        status = WARNING
    # OK
    else:
        status = OK

    # Minimum check
    if min_warning != min_critical:
        # Minimum Critical
        if value <= min_critical:
            status = CRITICAL
        # Minimum Warning
        elif value <= min_warning:
            status = WARNING

    # Invert results if necessary
    if inverse is True:
        if status == OK:
            status = CRITICAL
        elif status == CRITICAL:
            status = OK
    return status
