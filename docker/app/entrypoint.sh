#!/bin/sh
set -e

#php-fpm --daemonize && nginx -g 'daemon off;'
supervisord -c /etc/supervisor/conf.d/worker.conf
