#!/bin/sh
#
# install.sh, envmon Mark II - Environment Monitor, script file
# 
# Author: Márcio Pessoa <marcio.pessoa@sciemon.com>
# Contributors: none
# 
# Description:
#   This script install envmon on Arduino Yún or Dragino Yún Shield
# 
# Example: 
#   setup.sh install 192.168.1.11
#   setup.sh update 192.168.1.11
# 
# Change log
# 2016-05-22
#         Using rsync.
#
# 2016-05-11
#         Added update option.
#
# 2016-03-02
#         Appling lint.
#
# 2015-11-21
#         Experimental version.
#

ACTION=$1
HOST=$2

message() {
  message=$1
  echo "$message""... \c"
}

check_return() {
  return=$1
  if [ "$return" -eq 0 ]; then
    echo "Done."
  else
    echo "Fail."
    exit 1
  fi
}

install_dependencies() {
  message "Installing software dependencies"
  ssh "root@""$HOST" "opkg update && opkg install \
                      php5 php5-cgi php5-cli php5-mod-json \
                      rsync \
                      zoneinfo-simple bc \
                      python python-json pyserial"
  check_return $?
}

update() {
  message "Copying files"
  rsync -v --exclude-from rsync_exclude.txt --delete --archive ./* \
        "root@""$HOST":/opt/envmon
  check_return $?
}

install() {
  message "Copying configuration file"
  scp -q -r cfg/config.json "root@""$HOST":/opt/envmon/cfg
  check_return $?
}

configure_cron() {
  message "Configuring cron"
  ssh "root@""$HOST" "cp /opt/envmon/cfg/crontab /etc/crontabs/root"
  check_return $?
  message "Restarting cron"
  ssh "root@""$HOST" "/etc/init.d/cron restart"
  check_return $?
}

configure_webserver() {
  message "Configuring web server"
  ssh "root@""$HOST" "sed -i '/^#.*php-cgi/s/^#//' /etc/config/uhttpd"
  ssh "root@""$HOST" \
  "echo '	option index_page	\"index.html index.php\"' >> /etc/config/uhttpd"
  ssh "root@""$HOST" "ln -fs /opt/envmon/www /www/"
  ssh "root@""$HOST" "ln -fs /opt/envmon/www/favicon.ico /www/"
  ssh "root@""$HOST" "mv /www/index.html /www/index.html.original"
  ssh "root@""$HOST" "ln -fs /opt/envmon/html/index.html /www/"
  ssh "root@""$HOST" "ln -fs /opt/envmon/cfg/config.json /www/"
  ssh "root@""$HOST" \
  "sed -i 's,;short_open_tag = Off,short_open_tag = On,g' /etc/php.ini"
  check_return $?
  message "Restarting web server"
  ssh "root@""$HOST" "/etc/init.d/uhttpd restart"
  check_return $?
}

case "$ACTION" in
  'install')
    install_dependencies
    update
    install
    configure_cron
    configure_webserver
    ;;
  'update')
    update
    ;;
  *)
    echo "Usage:
setup.sh install 192.168.1.11
setup.sh update 192.168.1.11
"
    exit 1
    ;;
esac
exit 0
