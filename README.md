BayWa r.e. Microservice Skeleton
================================

# Setup

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

# Creation of documentation
1. Execute the script create_documentation.php. The file swagger.json is created in the public folder.
2. Copy all files from /vendor/swagger-api/swagger-ui/dist to /public/documentation.
3. In the public/documentation folder, find the SwaggerUIBundle creation code (normally in index.html or in swagger-initializer.js) and change the `URL` parameter to point to our `/swagger.json` file.

# Authentication

Authentication is handled in the file :
```
 module/MyApi/src/Module.php
```
