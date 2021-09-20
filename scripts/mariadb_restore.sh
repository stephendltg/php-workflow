#!/bin/bash
# =======
# version: 1
# author: sdeletang
# description: restore mariadb
# =======

cat mariadb.sql | docker exec -i mariadbdb /usr/bin/mysql -u root --password=user database
