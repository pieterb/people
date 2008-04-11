#!/bin/bash

cd `dirname $0`/../sql
mysqldump --default-character-set=utf8 -u readonly -preadonly -Q --add-drop-table central_database > central_database.sql
