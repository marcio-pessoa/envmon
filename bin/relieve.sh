#!/bin/sh
# 
# relieve.sh, envmon Mark II - Environment Monitor, script file
# 
# Author: Márcio Pessoa <marcio.pessoa@sciemon.com>
# Contributors: none
# 
# Description:
# 
# Example: 
#   relieve.sh /mnt /opt/envmon/log/system.log /mnt/envmon/log
# 
# Tip: 
#   Add this script to cron job.
# 
# Change log
# 2015-07-01
#         Experimental version.
# 

# Variables
Mount_Point=$1
File=`basename $2`
Input_Directory=`dirname $2`
Output_Directory=$3

# Return IDs
OK=0
Warning=1
Critical=2
Unknown=3

getTimeStamp() {
  date +"%Y-%m-%d %H:%M:%S - "
}

check_file() {
  if [ -f $1 ]; then
    return $OK
  else
    echo `getTimeStamp`"File not found."
    exit $Critical
  fi
}

check_directory() {
  if [ -d $1 ]; then
    return $OK
  else
    echo `getTimeStamp`"Directory not found."
    exit $Critical
  fi
}

is_mounted() {
  Mount_Point=$1
  if [ ! grep -qs $Mount_Point /proc/mounts ]; then
    echo `getTimeStamp`"Mounting $Mount_Point... \c"
    mount $Mount_Point
    if [ $? -eq 1 ]; then
      echo "Error mounting device."
      exit $Critical
    fi
    echo "Done."
  fi
}

is_mounted $Mount_Point
check_file $Input_Directory/$File
check_directory $Output_Directory
cat $Input_Directory/$File >> $Output_Directory/$File
rm -f $Input_Directory/$File
touch $Input_Directory/$File
