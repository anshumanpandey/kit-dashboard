# Inventory Management System with WareHouse Integration. #
This is the private Application for Inventory Management using Laravel-Livewire-jetstream/ tailwind / Alpine Js 

### Cloning 
To clone the Laravel-Rest project to your local machine, run the following command

`git clone --recursive -b master git@github.com:anshumanpandey/inventory-app.git /your_desired_location`

### Composer Installation. 
When checking out the project, be sure to run

`composer install` to install the vendor libraries. These libraries have been excluded from the Git repository. Also
run `composer install` from the submodule paths:

### NPM Injection 

`npm install` to install the `npm_modules` libraries. These libraries have been excluded from the Git repository. Also
run `npm install || npm update` from the submodule paths;


### Configuration Files

Some configuration files are excluded from the repository, as these are symlinked from the *.env.example* .
This allows each developer to have their own private configuration files without overriding configurations from team
members when these files are pushed.

**Please do not commit and push your configuration files into the repository!** 
For your localhost, copy the following files:

 - `$ cp .env.example` into `.env`

and edit them to suit your localhost configuration.

###  Database Migration
All that needs to be done now, is to run the database migration script from the project root.

`./artisan migrate:fresh`
`./artisan db:seed `

###  Deployment
Comming soon...


### To run the app on local enviornment please run 

## Server Livewire 
Proxy : `http://localhost:8000` 

## For build the frontend app simply run

`npm run dev` or `npm run --watch`

### Deployed Url 
`http://localhost:8000` Or `http://app.dev.com:port`

