# Laravel Local Server

The local server package providers a local development environment for Laravel projects. It is built on a containerized architecture using Docker images and Docker Compose to provide drop-in replacements for most components of the cloud infrastructure.

## Requirements

Make sure all dependencies have been installed before moving on:

* [PHP](http://php.net/manual/en/install.php) >= 7.2
* [Composer](https://getcomposer.org/download/)
* [Docker Desktop](https://www.docker.com/products/docker-desktop)

## Installation

You can install the package via composer:

Add the following to your composer.json repositories 
```bash
"type": "composer",
"url": "https://packages.interconnectit.com"
```

Then run this command in your project directory.
```bash
composer require interconnectit/laravel-local-server --dev
```

## Usage

### Env Vars
- Set your `APP_URL` along the lines of `http://blog.localtest.me/`
- Set your database connections 
```dotenv
  DB_CONNECTION=mysql
  DB_HOST=mysql
  DB_PORT=3306
  DB_DATABASE=laravel
  DB_USERNAME=laravel
  DB_PASSWORD=laravel
 ```
- Set your Redis
```dotenv
REDIS_HOST=redis
REDIS_PASSWORD=null
REDIS_PORT=6379
```
- Set your drivers and queue vars
```dotenv
BROADCAST_DRIVER=redis
CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis
```
- Set your mailer
```dotenv
MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=test@email.com
MAIL_FROM_NAME="${APP_NAME}"
```

### Artisan Commands
The local server uses the command line via the `composer` command.
`php artisan` commands are replaced with `composer local-server artisan`. This is especially important for database related commands

Navigate to your shell to your project's directory. You should already have installed Laravel by running `laravel new` or `composer create-project` but if not, do so now. See [Installing Laravel](https://laravel.com/docs/master#installing-laravel).

### Starting the local server

To start the local server, simply run `composer local-server start`. The first time this will download all the necessary Docker images.

Once the initial install is over and download have completed, you should see the output:

```sh
Starting blog-proxy      ... done
Starting blog-redis      ... done
Starting blog-mailhog    ... done
Starting blog-mysql      ... done
Starting blog-backend    ... done
Starting blog-phpmyadmin ... done
Starting blog-worker     ... done
Starting blog-scheduler  ... done
Starting blog-frontend   ... done

Your local server is ready!
To access your site visit: http://blog.localtest.me/
```

Visiting your site's URL should now work.

### Stopping the local server

To stop the local server containers, simply run `composer local-server stop`.

### Destroying the local server

To destroy the local server containers, simply run `composer local-server destroy`.

### Viewing the local server status

To get details on the running local server status and containers, run `composer local-server status`. You should see output similar to:

```sh
     Name                    Command                  State                         Ports
------------------------------------------------------------------------------------------------------------
blog-backend      docker-php-entrypoint php-fpm    Up             9000/tcp
blog-frontend     nginx -g daemon off;             Up             80/tcp
blog-mailhog      MailHog                          Up             1025/tcp, 8025/tcp
blog-mysql        docker-entrypoint.sh --def ...   Up (healthy)   3306/tcp, 33060/tcp
blog-phpmyadmin   /docker-entrypoint.sh apac ...   Up             80/tcp
blog-proxy        /entrypoint.sh traefik           Up             0.0.0.0:80->80/tcp, 0.0.0.0:8080->8080/tcp
blog-redis        docker-entrypoint.sh redis ...   Up (healthy)   6379/tcp
blog-scheduler    docker-php-entrypoint sh / ...   Up
blog-worker       docker-php-entrypoint php  ...   Up
```

All containers should have a status of "Up". If they do not, you can inspect the logs for each service by running `composer local-server logs <service>`, for example, if `blog-mysql` shows a status other than "Up", run `composer local-server logs mysql`.

### Viewing the local server logs

Often you'll want to access logs from the services that local server provides. For example, PHP errors logs, Nginx access logs, or MySQL logs. To do so, run the `composer local-server logs <service>` command, where `<service>` can be any of `proxy`, `frontend`, `backend`, `worker`, `scheduler`, `phpmyadmin`, `mysql`, `redis`. This command will tail the logs (live update). To exit the log view, simply press `Ctrl+C`.

### Ejecting the local server configuration

If you aren’t satisfied with the preselected services and configuration choices, you can `eject` at any time. It will copy all the configuration files into your project.

You don’t have to ever use `eject`. The curated service set is suitable for small to middle deployments, and you shouldn’t feel obligated to use this command. However I understand that this package wouldn’t be useful if you couldn’t customize it when you are ready for it.

See custom [recipes](../../wiki/Recipes).

## Testing

```bash
composer test
```

## Security

If you discover any security related issues, please raise an issue in the tracker.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
