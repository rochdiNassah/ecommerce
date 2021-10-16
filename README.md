
# What is this?
This is a simple eCommerce web application created with Laravel and designed using Tailwind.

## Application objects
* Members can join and login.
* Admin can approve, reject, upgrade, downgrade, or delete members.
* Admin can create or delete products.
* Customer can browse products.
* Customer can order a product.
* Dispatcher can dispatch or reject orders.
* Delivery driver can update the status of dispatched orders.
* Customer can track their order.
* Customer can cancel their non-delivered order.

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
