# New Digitals - Coding challenge

## Setup development

Please follow these steps to have the default settings in place:

- Add the following to your `/etc/hosts` or similar:
  ```
  127.0.0.1		newdigitals-challenge.dev
  127.0.0.1		www.newdigitals-challenge.dev
  ```
- Copy the `.env.example`: `cp .env.example .env`
- Install `mkcert` (if not already installed, including `mkcert -install` command).
- Run `mkcert -cert-file .docker/nginx/newdigitals-challenge.dev.pem -key-file .docker/nginx/newdigitals-challenge.dev-key.pem "*.newdigitals-challenge.dev" newdigitals-challenge.dev`.
- Run `make build` to build the docker images.
- Run `make install` to install the composer dependencies and initialize the database.
- Run `make up` to start the docker containers.

## Emails

All emails are sent to Mailcatcher, which is available at `http://localhost:1080`.

## Database

### Create / Update the database schema

We use the DoctrineMigrationsBundle to manage schema updates. See: <https://symfony.com/doc/master/bundles/DoctrineMigrationsBundle/index.html>

To update the database schema, simply execute following command:

`make doctrine-migrations-migrate`

Every time an Entity changes (field added, field rename, index added etc.) you have to execute:

`make doctrine-migrations-diff`

which creates a new migration file. They have to be committed to the repository.
Never change a previously created (already committed into master) migration file!

### Run migrations

You can run the migrations by `make doctrine-migrations-migrate`.

## Code validation

The following command must be triggered before you commit your code changes.

`make api-code-validation`

## XDebug

There are Makefile commands to enable/disable Xdebug. In order for xdebug to work with PHPStorm you need to add a default RemoteDebug Configuration, and you need to add a server path mapping with the same name that is set for `PHP_IDE_CONFIG` in your .env

It is also necessary to increase the value in `Settings | PHP | Debug | External Connection | Max. simultaneous Connections` to at least 5. The reason is that we have 5 containers that can have xdebug enabled. If the `Max. simultaneous Connections` value is lower than that, then any containers that were unable to open a connection will be frozen because they will try to connect before processing anything.
