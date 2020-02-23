define create_db
	@mysql --execute="CREATE USER IF NOT EXISTS 'forge'@'localhost';"
	@mysql --execute="CREATE DATABASE IF NOT EXISTS $1;"
	@mysql --execute="GRANT ALL PRIVILEGES ON *.* TO 'forge'@'localhost';"
endef

setup:
	make setup_copy_env
	composer install

setup_copy_env:
	@if [ ! -f .env ]; then \
		cp .env.example .env; \
		echo "Copied .env file"; \
	fi

setup_create_db:
	$(call create_db,laravel)
