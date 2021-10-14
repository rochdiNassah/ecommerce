
# What is this?
This is a simple eCommerce web application created with Laravel and designed using Tailwind.

## Application objects
* TODO

### Server requirements
* TODO

### Installation
* Clone this repository.
* Run `cd ecommerce && composer install`.
* Copy `.env.example` to `.env`.
* Run `php artisan key:generate`.
* Adjust database details in your `.env`.
* Run `php artisan migrate --seed`.
* Run `php artisan serve --port=8080` and head to your browser to test the application.

### Credentials for testing
| <sub>ROLE</sub>                | <sub>EMAIL ADDRESS</sub>      | <sub>PASSWORD</sub> |
|:-------------------------------|------------------------------|:-------------------:|
| __<sub>Super admin</sub>__     | <sub>sadmin@foo.bar<sub>     | __<sub>1234</sub>__ |
| __<sub>Admin</sub>__           | <sub>admin@foo.bar<sub>      | __<sub>1234</sub>__ |
| __<sub>Dispatcher</sub>__      | <sub>dispatcher@foo.bar<sub> | __<sub>1234</sub>__ |
| __<sub>Delivery driver</sub>__ | <sub>delivery@foo.bar<sub>   | __<sub>1234</sub>__ |
