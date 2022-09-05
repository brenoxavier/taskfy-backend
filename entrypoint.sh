#!/bin/bash

/etc/init.d/php7.4-fpm start -R

nginx -g "daemon off;"
