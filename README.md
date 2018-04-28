# the-cassette-coach

This project is an old experiment of mine, where I had some background in Java and I wanted to learn more about javascript, css, php and Zend Framweork 2.

It's a game to learn about music theory.

## Run it using docker (recommended)

In order to run it locally using docker, just run the following command:

```
docker network create jdonado-nw
docker-compose up -d
```

Open the app on `http://localhost:9090` and log in as:

- User: javi
- Password: 12341234

## Local installation

You'll need a working instance of Apache Web Server, with PHP 5.6. See the official [Zend Framework documentation](https://framework.zend.com/manual/2.4/en/user-guide/skeleton-application.html) for more information. 

- Run php composer.phar install
- Add SetEnv ZF2_PATH to the apache VirtualHost, pointing to the library of the zend framework you have just installed via composer
- Activate mod_rewrite
- Dump the database from dump.sql
- Update the database configuration from /configuration/global/*.local files
- Define the environment variable `MYSQL_ROOT_PASSWORD` 

