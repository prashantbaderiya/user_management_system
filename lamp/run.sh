#!/bin/bash

./local_db_setup.sh

rm /var/www/html/index.html

exec supervisord -n
