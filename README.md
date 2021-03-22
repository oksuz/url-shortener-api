# URL SHORTENER API

It provides short version of an url that provided by user as well as support `A/B test` (users are not traceable yet)

## Configuration

You're supposed to obtain a `bit.ly` token in order to `A/B test` works correctly or set `bitly` driver ratio to 0 in `config/services.yml` like:

```yaml
App\Services\UrlShortenerABRouter:
    arguments:
        - { a: { ratio: 0, provider: '@App\Services\BitlyUrlShortener'}, b: { ratio: 100, provider: '@App\Services\BijectiveUrlShortener' } }

```

Set your bitly token to `.env.prod` file with field `BITLY_TOKEN`


#### Test your Bitly token

use for validate your bitly token through command line, replace `<your-token>` and `<url>` part for check

```bash
curl -XGET "https://api-ssl.bitly.com/v3/shorten?access_token=<your-token>&format=txt&longUrl=<url>"
```


### AB Test
You can change A/B sides ratio in `config/services.yml`

## How to run

First thing first, this app needs a database in order to store shortened urls. The `docker-compose up` command prepares all requirements for run the app. 

- ### Docker Compose
App is ready to ship anywhere through `docker`, also has pre-configured docker-compose file.
You can just start with `docker-compose up` command, in few minute app will be accepting request on port `8080`

- ### Docker

Build image with following command in order to push image to any docker registry:
Let's assume the image name is `url-shortener-api`

`docker build -t url-shortener-api .`

In the above command going to install dependencies, prepare `APP_ENV`, and run tests automatically. Also, you can run test that modified/newly added with following command:

`docker run --rm -v${pwd}:/opt/app -eAPP_ENV=test url-shortener-api php bin/phpunit`

### Running the docker image

`.env.prod` file expects following parameters as environment variable:

- APP_SECRET
- DB_USER
- DB_PASSWORD
- DB_HOST
- DB_PORT
- DB_NAME
- BITLY_TOKEN

considering to that docker run command should be:

```bash
docker run --name url-shortener-app \
-e APP_SECRET=test123456 \
-e DB_USER=db-user \
-e DB_PASSWORD=password \
-e DB_HOST=ip|hostname \
-e DB_PORT=3306 \
-e DB_NAME=url_shortener \
-e BITLY_TOKEN=token \
-d url-shortener-api 
```

The above configuration need a separate nginx http server for handle http requests 

- ### Run with PHP Dev Server For Development Purposes

#### Setting up database

set up your database connection, and required parameters (like bitly token) in `.env` (environment specific) file than run following two commands for create the tables and the schemas:

```bash
php bin/console doctrine:database:create --if-not-exists
php bin/console doctrine:migrations:migrate --no-interaction
```

```bash
php -S 0.0.0.0:8282 -t ./public
```
#### Running Test

```bash
php bin/phpunit
```

#### PSR Check
It has also psr checker script, see the psr output to run following command:

```bash
composer phpcs
```