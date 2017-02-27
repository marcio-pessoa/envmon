#!/bin/sh
# 
# check_system.sh, envmon Mark II - Environment Monitor
# System performance stats collector script file
# 
# Author: Márcio Pessoa <marcio.pessoa@sciemon.com>
# Contributors: none
# 
# Description:
# 
# Example:
#   /opt/envmon/bin/check_system.sh
# 
# Change log:
# 2017-02-27
#         Added WiFi signal strength monitoring.
#
# 2016-06-17
#         Fixed methodology to get CPU utilization.
#
# 2016-06-07
#         Adapted to be invoked from envmon daemon.
#
# 2015-11-21
#         Added swap memory statistics.
#
# 2015-07-09
#         Experimental version.
# 

PROGRAM_NAME="check_system.sh"

getCPU() {
  top -n 1 | grep -i -e CPU -e id | head -1 | \
    tr -s " " | cut -d " " -f 8 | grep -o "[0-9,.]*" | tr "," "." | \
    awk '{usage=(100-$1)} END {print usage}'
}

getMemory() {
  free | grep ^"Mem:" | tr -s " " | \
    awk '{usage=$3*100/$2} END {print usage}'
}

getSwap() {
  result=$(free | grep ^"Swap:" | tr -s " " | awk '{if($2 + 0 != 0) usage=$3*100/$2} END {print usage}')
  if [ "$result" = "" ]; then
    echo 0
  else
    echo "$result"
  fi
}

getStorage() {
  partition=$1
  result=$(df | grep ^"$partition" | tr -s " " | awk '{usage=$3*100/$2} END {print usage}')
  if [ "$result" = "" ]; then
    echo 0
  else
    echo "$result"
  fi
}

getWiFiSignalStrength() {
  result=$(iwconfig wlan0 | grep Quality | tr -s ' ' | cut -d ' ' -f 3 | cut -d '=' -f 2 | tr '/' ' ' | awk '{usage=$1/$2*100} END {print usage}')
  if [ "$result" = "" ]; then
    echo 0
  else
    echo "$result"
  fi
}

case "$1" in
  cpu)
    getCPU
    ;;
  mem)
    getMemory
    ;;
  swap)
    getSwap
    ;;
  root)
    getStorage rootfs
    ;;
  sd)
    getStorage /dev/sda
    ;;
  wifi)
    getWiFiSignalStrength
    ;;
  *)
    echo "Usage: $PROGRAM_NAME {cpu|mem|swap|root|sd|wifi}" >&2
    exit 3
    ;;
esac
