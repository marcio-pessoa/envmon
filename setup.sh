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
                    zoneinfo-simple bc \
                    python python-json pyserial"
  check_return $?
}

create_directories() {
  message "Creating directories"
  ssh "root@""$HOST" "mkdir -p /opt/envmon/bin"
  ssh "root@""$HOST" "mkdir -p /opt/envmon/cfg"
  ssh "root@""$HOST" "mkdir -p /opt/envmon/cgi"
  ssh "root@""$HOST" "mkdir -p /opt/envmon/html"
  ssh "root@""$HOST" "mkdir -p /opt/envmon/nrdp"
  ssh "root@""$HOST" "mkdir -p /opt/envmon/strings"
  ssh "root@""$HOST" "mkdir -p /opt/envmon/log"
  ssh "root@""$HOST" "mkdir -p /opt/envmon/var"
  ssh "root@""$HOST" "mkdir -p /opt/envmon/www"
  check_return $?
}

install_files() {
  message "Installing files"
  scp -q -r cgi CHANGELOG html nrdp README strings www \
  "root@""$HOST":/opt/envmon
  check_return $?
}

install_full_config() {
  message "Installing full configuration files"
  scp -q -r cfg "root@""$HOST":/opt/envmon
  check_return $?
}

install_some_config() {
  message "Installing some configuration files"
  scp -q -r cfg/config.php \
            cfg/crontab \
            cfg/default.json \
            cfg/languages.json \
            cfg/seasons.json \
            "root@""$HOST":/opt/envmon/cfg
  check_return $?
}

install_binaries() {
  message "Installing binaries files"
  scp -q -r bin/check_myip.sh \
            bin/check_system.sh \
            bin/envmon.pyc \
            bin/relieve.sh \
            "root@""$HOST":/opt/envmon/cfg
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
    create_directories
    install_files
    install_full_config
    install_binaries
    configure_cron
    configure_webserver
    ;;
  'update')
    install_files
    install_some_config
    install_binaries
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
