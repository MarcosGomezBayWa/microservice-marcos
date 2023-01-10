BayWa r.e. Microservice Skeleton
================================

# Setup

1. Copy the entire repository to your project.
2. Add an API & Database container to the local docker compose setup.
2. Run `./composer.phar install`
3. Copy a file `local-conf.ini.dist` to `local-conf.ini` and complete the missing values
4. Run `vendor/bin/phing init -propertyfile local-conf.ini`
