#!/bin/bash

function start_mysql() {
    service mysql start
}

function initialize_db() {


        start_mysql
        
        mysql -u root -e "CREATE USER 'prashant'@'localhost' IDENTIFIED BY '123'"
        mysql -u root -e "GRANT ALL PRIVILEGES ON * . * TO 'prashant'@'localhost'"
        echo "=> Done!"
        echo "========================================================================"
        echo "You can now connect to this MySQL Server "
        echo "========================================================================"


        echo "=> Initializing database"
        mysql -h localhost -u root -e "CREATE DATABASE user_management_system"
        mysql -h localhost -u root user_management_system < /tmp/db_backup.sql
}

initialize_db
