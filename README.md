# eCommerce
Small eCommerce web application using Laravel and Tailwind.

### Order lifecycle
1. The customer places the order.
2. The order is assigned to a dispatcher to confirm it and dispatch it to a delivery driver.
4. The delivery driver can then update its delivery_status to __IN_DELIVERY__ or __DELIVERED__. delivery_status's __IN_DELIVERY__ status is reflected on the order's status also.
5. The dispatcher can then confirm its delivery by updating its status to __DELIVERED__.
> The customer gets updated with the status of their order whenever it changes through their email. And of course, they can track it in real-time by the reference that we sent to them once they placed the order.

### Sitemap
 - /login
 - /join
 - /dashboard
 - /product/{id}
 - /track/{token}

### Database
orders | users | products
------------ | ------------- | -------------
id | id | id
product | fullname | name
customer_details | email | description
status __[`pending`, `confirmed`, `in_delivery`, `rejected`, `delivered`]__ | password | price
delivery_status __[`in_delivery`, `delivered`]__ | phone_number | price
dispatcher | role __[`admin`, `dispatcher`, `delivery_driver`]__ | image_path
dispatched_to | avatar_path | 
token |  | 
