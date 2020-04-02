# Laravel Vue SPA boiler plate

Laravel Vue single page application boiler plate using laravel passport authentication.

Laravel 7.2.2, laravolt avatar 3.2, intervention image 2.5, laravel passport 8.4, vue 2.5.17, vue-router 3.1.6, 
vuelidate 0.7.5, vuetify 2.2.18, vuex 3.1.3.
  
### Prerequisites
```
 Make sure to use a version of php >= 7.3.9 (php -v).
 Make sure you have composer installed.
 Make sure you have npm installled.   
```

### How to use
- Download (as zip) and extract or git clone the project under your web server's root directory.
 
- Run `php artisan key:generate`

- Install dependencies with `Composer` first:
  ```bash
  $ composer install
  ```

- Update .env - set app url, database connection, mail connection and laravel passport details.
 
- Run `php artisan storage:link` and `php artisan passport:install`.
   
- Install front-end dependencies with `npm`:
  ```bash
  $ npm install
  ``` 

- Run `php artisan db:seed`, this will create two users and few related articles for each user. 
  You can use email: jhon@gmail.com, pwd: password to login and play with user section of the app.
  
- To run the tests use `phpunit`:   
  ```bash
  $ ./vendor/bin/phpunit --testdox tests
  ```




