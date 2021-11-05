# What is this?
This is a simple eCommerce web application created with Laravel 8 and designed using Tailwind.

### Demo
https://user-images.githubusercontent.com/58631670/140643159-5f42232b-5482-4c69-bac1-c3fb7da4fa76.mp4
* [Screenshots](/../../issues/15)

## Application objects
* Members can join, log in, and reset their password.
* Admin can approve, reject, upgrade, downgrade, or delete members.
* Admin can create or delete products.
* Customer can browse products.
* Customer can order a product.
* Customer can track their order in real-time.
* Customer can view all of their orders.
* Customer can cancel their order.
* Dispatcher can dispatch or reject orders.
* Delivery driver can update the status of dispatched orders.

### Installation
> Note: `redis` and `zmq` PHP extensions should be present in the server. Otherwise, the application will work fine, but some features may not be available.
* Clone this repository.
* Copy `.env.example` to `.env`.
* Adjust database, mail, and redis details in your `.env`.
* Run these commands within the project's root directory:\
`composer install`\
`php artisan key:generate`\
`php artisan migrate --seed`\
`php artisan storage:link`\
`php artisan queue:listen`\
`php artisan ratchet:start`

### Credentials for testing
| <sub>ROLE</sub>                | <sub>EMAIL ADDRESS</sub>      | <sub>PASSWORD</sub> |
|:-------------------------------|-------------------------------|:-------------------:|
| __<sub>Super admin</sub>__     | <sub>sadmin@foo.bar<sub>      | __<sub>1234</sub>__ |
| __<sub>Admin</sub>__           | <sub>admin@foo.bar<sub>       | __<sub>1234</sub>__ |
| __<sub>Dispatcher</sub>__      | <sub>dispatcher@foo.bar<sub>  | __<sub>1234</sub>__ |
| __<sub>Delivery driver</sub>__ | <sub>delivery@foo.bar<sub>    | __<sub>1234</sub>__ |
    
### Hidden routes
* `/login`
* `/join`
    
