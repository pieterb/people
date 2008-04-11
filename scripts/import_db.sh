#!/bin/bash

cd `dirname $0`/../sql
if [ \( "$1" == "" \) -o \( ! -d "/var/lib/mysql/$1" \) ]; then
	echo Usage: import_db.sh \<database_name\>
	exit
fi

mysql --default-character-set=utf8 -u shopuser -pshopuser "$1" < new_database.sql
