# Laravel Vue SPA boiler plate

Laravel Vue single page application boiler plate using laravel passport authentication with passport cookies.

Laravel 7.2, laravolt avatar 3.2, intervention image 2.5, laravel passport 8.4, vue 2.5.17, vue-router 3.1.6, 
vuelidate 0.7.5, vuetify 2.2.18, vuex 3.1.3.
  
 
### Prerequisites
```
 Make sure to use a version of php >= 7.3.9 (php -v).
 Make sure you have composer installed.
 Make sure you have npm installled.   
```

### Features

- Frontend built with [Vuetify](https://vuetifyjs.com/en/) UI framework.
- Pages landing, login, register, forgot password. 
- Email verification (To enable email verification verify that App\Models\User model implements the Illuminate\Contracts\Auth\MustVerifyEmail contract.) 
- User dashboard, user update profile, user update password.
- User update avatar using package [laravolt avatar](https://github.com/laravolt/avatar) and [intervention image](http://image.intervention.io/) 
- User article CRUD with [CKEditor](https://ckeditor.com/ckeditor-5/) 
- Client-side form validation with [vuelidate](https://github.com/vuelidate/vuelidate)
- Laravel Passport Authentication.
- PHPUnit test for all the features.
- Laravel dusk test for frontend UI. 


### How to use
- Download (as zip) and extract or git clone the project under your web server's root directory.
 
- Run `php artisan key:generate`

- Install dependencies with `Composer` first:
  ```bash
  $ composer install
  ```

- Create two database one for app (e.g boilerplate) and one for app testing (e.g boilerplate_testing).

- Create two file .env and .env.dusk.local using .env.example. Update both the files - set app url, database connection, mail connection and laravel passport details.

- Update phpunit.xml file `<server name="DB_DATABASE" value="testDatabaseName (e.g boilerplate_testing)"/>` 

- Run `php artisan storage:link` and `php artisan passport:install`.
   
- Install front-end dependencies with `npm`:
  ```bash
  $ npm install
  ``` 

- Run `php artisan db:seed`, this will create two users and few related articles for each user. 
  You can use `email: jhon@gmail.com`, `pwd: password` to login and play with user section of the app.
  
- To run the tests use `phpunit`:   
  ```bash
  $ ./vendor/bin/phpunit --testdox tests
  ```

- To run the ui tests use `dusk`:   
    ```bash
    $ php artisan dusk --testdox
    ```

 ### DEMO 1
 ![Laravel Vue SPA boiler plate Demo](demo01.gif)
 
 ### DEMO 2
 ![Laravel Vue SPA boiler plate Demo](demo02.gif)




