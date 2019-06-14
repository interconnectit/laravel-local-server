# Laravel Local Server

The local server package providers a local development environment for Laravel projects. It is built on a containerized architecture using Docker images and Docker Compose to provide drop-in replacements for most components of the Cloud infrastructure.

## Requirements

Make sure all dependencies have been installed before moving on:

* [PHP](http://php.net/manual/en/install.php) >= 7.1
* [Composer](https://getcomposer.org/download/)
* [Docker Desktop](https://www.docker.com/products/docker-desktop)

## Install

Via Composer:

``` bash
$ composer require interconnectit/laravel-local-server --dev
```

## Usage

### Starting the local server

To start the local server, simply run `composer local-server start`. The first time you this will download all the necessary Docker images.

Once the initial install and download have completed, you should see the output:

``` sh
Starting...
Creating network "laravel-project_default" with the default driver
Creating volume "laravel-project_mysql-data" with default driver
Creating laravel-project-mysql         ... done
Creating laravel-project-proxy         ... done
Creating laravel-project-php           ... done
Creating laravel-project-phpmyadmin    ... done
Creating laravel-project-nginx         ... done
Started.

To access site please visit: http://laravel-project.localtest.me/
To access phpmyadmin please visit: http://phpmyadmin.laravel-project.localtest.me/
```

### Stopping the local server

To stop the local server, simply run `composer local-server stop`.

### Destroying the local server

To destroy the local server, simply run `composer local-server destroy`.

### Viewing the local server status

To get details on the running local server status, run `composer local-server status`. You should see output similar to:

``` sh
            Name                           Command                  State                         Ports
--------------------------------------------------------------------------------------------------------------------------
laravel-project-elasticsearch   /usr/local/bin/docker-entr ...   Up (healthy)   9200/tcp, 9300/tcp
laravel-project-mysql           docker-entrypoint.sh --def ...   Up (healthy)   3306/tcp, 33060/tcp
laravel-project-nginx           nginx -g daemon off;             Up             80/tcp
laravel-project-php             docker-php-entrypoint php-fpm    Up             9000/tcp
laravel-project-phpmyadmin      /run.sh supervisord -n -j  ...   Up             80/tcp, 9000/tcp
laravel-project-proxy           /traefik                         Up             0.0.0.0:80->80/tcp, 0.0.0.0:8080->8080/tcp
```

All containers should have a status of "Up". If they do not, you can inspect the logs for each service by running `composer local-server logs <service>`, for example, if `docker_mysql_1` shows a status other than "Up", run `composer local-server logs mysql`.

### Viewing the local server logs

Often you'll want to access logs from the services that local server provides. For example, PHP errors logs, Nginx access logs, or MySQL logs. To do so, run the `composer local-server logs <service>` command, where `<service>` can be any of `php`, `nginx`, `mysql`, `elasticsearch`. This command will tail the logs (live update). To exit the log view, simply press `Ctrl+C`.
