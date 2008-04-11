#!/bin/bash

cd `dirname $0`/../sql
mysql --default-character-set=utf8 -u root -p central_database < central_database.sql
