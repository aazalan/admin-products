PORT ?= 8000
start:
	php -S 0.0.0.0:$(PORT)

dbrun:
	sudo /etc/init.d/mysql start


connect:
	mysql -u root -p