#!/bin/bash
# =======
# version: 1
# author: sdeletang
# description: restore mariadb
# =======

gunzip < mariadb.sql.gz | docker exec -i db /usr/bin/mysql -u root --password=user database
