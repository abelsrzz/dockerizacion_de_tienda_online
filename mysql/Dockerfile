FROM mysql:latest
ADD databases/ /docker-entrypoint-initdb.d/
ADD scrapped_data/ /data 
ADD mysql_data/ /var/lib/mysql

