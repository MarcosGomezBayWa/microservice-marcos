BayWa r.e. Microservice Skeleton
================================

# Setup

1. Copy the entire repository to your project :
   1. Clone this repository
   2. `cd microservice-skeleton`
   3. Mirror Push to your new repository : `git push --mirror https://github.com/baywa-re-lusy/<your new repository>.git`
2. Add an API & Database container to the local docker compose setup.
2. Run `./composer.phar install`
3. Copy a file `local-conf.ini.dist` to `local-conf.ini` and complete the missing values
4. Run `vendor/bin/phing init -propertyfile local-conf.ini`
5. Search all occurences of the term *Microservice* (`README.md`, `build.xml`, `composer.json`, etc.) and replace them with the name of your project.

There is an example API `MyApi` and an example RESTful Service `MyService` already configured.

# Authentication

Authentication is handled in the file :
```
 module/MyApi/src/Module.php
```
