# README #

Steps for the installation:

- Run php composer.phar install
- Add SetEnv ZF2_PATH to the apache VirtualHost, pointing to the library of the zend framework you have just installed via composer
- Activate mod_rewrite
- Dump the database from dump.sql
- Update the database configuration from /configuration/global/*.local files

