#!/bin/sh
# 
# check_system.sh, envmon Mark II - Environment Monitor, script file
# 
# Author: Márcio Pessoa <marcio.pessoa@sciemon.com>
# Contributors: none
# 
# Description:
# 
# Example: 
#   /opt/envmon/bin/check_system.sh
# 
# Tip: 
#   Add this script to cron job.
# 
# Change log
# 2015-11-21
#         Added swap memory statistics.
#
# 2015-07-09
#         Experimental version.
# 

getTimeStamp() {
  date +"%Y-%m-%d %H:%M:%S"
}

getCPU() {
  grep 'cpu ' /proc/stat | awk '{usage=($2+$4)*100/($2+$4+$5)} END {print usage}'
}

getMemory() {
  free | grep ^"Mem:" | tr -s " " | awk '{usage=$3*100/$2} END {print usage}'
  return 1
}

getSwap() {
  result=$(free | grep ^"Swap:" | tr -s " " | awk '{if($2 + 0 != 0) usage=$3*100/$2} END {print usage}')
  if [ "$result" = "" ]; then
    echo 0
  else
    echo "$result"
  fi
  return 1
}

getStorage() {
  partition=$1
  result=$(df | grep ^"$partition" | tr -s " " | awk '{usage=$3*100/$2} END {print usage}')
  if [ "$result" = "" ]; then
    echo 0
  else
    echo "$result"
  fi
  return 1
}

getUptime() {
  echo $(for i in `uptime | cut -d " " -f 4,7 | tr -d ","`; do echo "$i days \c"; done | cut -d " " -f 1-3) hours
2 days 5:04 hours
}

#~ getNTP() {
  #~ offsets=$(ntpq -nc peers | tail -n +3 | cut -c 62-66 | tr -d '-')
  #~ for offset in ${offsets}; do
      #~ if [ ${offset:-0} -ge ${limit:-100} ]; then
    #~ echo "An NTPD offset is excessive - Please investigate"
    #~ exit 1  
      #~ fi  
  #~ done
#~ }

#
# check <resource> <unit> <warning> <critical> <value>
#
check() {
  Name=$1
  Unit=$2
  Warning=$3
  Critical=$4
  Value=$5
  Status="OK"
  if [ $(echo "$Value > $Warning" | bc) -eq 1 ]; then
    Status="Warning"
  fi
  if [ $(echo "$Value > $Critical" | bc) -eq 1 ]; then
    Status="Critical"
  fi
  echo "$(getTimeStamp) - $Name ($Status): $Value$Unit"
}

check "CPU" "%" 60 80 "$(getCPU)"
check "Memory" "%" 98 99 "$(getMemory)"
check "Swap" "%" 5 10 "$(getSwap)"
check "Storage" "%" 70 90 "$(getStorage rootfs)"
check "SD" "%" 70 90 "$(getStorage /dev/sda)"
#~ getUptime
#~ check "NTP" " " 30 300 `getNTP`
