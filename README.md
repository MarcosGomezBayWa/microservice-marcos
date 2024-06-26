BayWa r.e. Microservice Skeleton
================================

# Setup

You first need to create a file `.env` with the following content :
```
PHP_FPM_DOCKER_IMAGE=crpdlusy.azurecr.io/alpine-php82-fpm-dev:2.2.0
```

Then follow the instructions :
1. Copy the entire repository to your project :
   1. Clone this repository
   2. `cd microservice-skeleton`
   3. Mirror Push to your new repository : `git push --mirror https://github.com/baywa-re-lusy/<your new repository>.git` or simply copy all the source files to your repository.
2. Add an API & Database container to the local docker compose setup.
3. Go inside the API Container `docker exec --user "$(id -u):$(id -g)" -ti <your API container name> /bin/bash`
4. Run `./composer.phar install`
5. Copy a file `local-conf.ini.dist` to `local-conf.ini` and complete the missing values
6. Run `vendor/bin/phing init -propertyfile local-conf.ini`
7. Search all occurences of the term *Microservice* (`README.md`, `build.xml`, `composer.json`, etc.) and replace them with the name of your project.

There is an example API `MyApi` and an example RESTful Service `MyService` already configured.

# Authentication

Authentication is handled in the file :
```
 module/MyApi/src/Module.php
```
