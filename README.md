### Development steps
- [ ] Log In
    - [x] UI form `EMAIL, PASSWORD, REMEMBER`
    - [ ] Tests
        - [ ] Authenticated user cannot access login route
        - [ ] Pending user cannot be authenticated
        - [ ] Guest can access login route
        - [ ] Guest can login with valid credentails
        - [ ] Guest cannot login with invalid credentails
        - [ ] `email` and `remember` fields are flashed on failure
        - [ ] User is redirected to the proper router based on their role after login
- [ ] Join
    - [ ] UI form `FULLNAME, EMAIL, PHONE_NUMBER, PASSWORD, CONFIRM PASSWORD, ROLE, AVATAR`
    - [ ] Tests
        - [ ] Authenticated user cannot access join route
        - [ ] Guest can access join route
        - [ ] Guest can join with valid data
        - [ ] Guest cannot join with invalid data
        - [ ] User is notified when they join
        - [ ] Fields are flashed on failure except `password` fields
- [ ] Products
    - [ ] Display products in the home page
    - [ ] Product card
        - Image
        - Name
        - Price
        - Order button that will redirect them to `order` page
    - [ ] Product page
        - Image
        - Name
        - Description
        - Price
        - Order button that will redirect them to the `order` page
- [ ] Orders
    - [ ] UI form `FULLNAME, EMAIL, PHONE_NUMBER, ADDRESS`
    - [ ] Tests
        - [ ] User cannot access order route
        - [ ] Guest can access order route
        - [ ] Guest can order with valid data
        - [ ] Guest cannot order with invalid data
        - [ ] Customer is notified when they order
        - [ ] Order is not placed when the customer and the product are the same except their current order is delivered, rejected, or canceled
- [ ] Admin
    - [ ] Users
        - [ ] Approve user  (Tests)
            - [ ] Can approve user under `pending` status
            - [ ] Cannot approve user under `active` status
            - [ ] Only admin can approve user
        - [ ] Delete user (Tests)
            - [ ] First admin cannot be deleted except by themselves
            - [ ] Can delete a user
            - [ ] User under `pending` status is notified before deletion
        - [ ] Upgrade/Downgrade user (Tests)
            - [ ] Cannot downgrade first admin
            - [ ] Admin can upgrade or downgrade user
            - [ ] Only first admin can downgrade admin
    - [ ] Products
        - [ ] Add product
            - [ ] UI form `NAME, DESCRIPTION, PRICE, IMAGE`
            - [ ] Tests
                - [ ] Only admin can access add product route
                - [ ] Admin can add product with valid form data
                - [ ] Admin cannot add product with invalid form data
        - [ ] Edit product
            - [ ] UI form `NAME, DESCRIPTION, PRICE, IMAGE`
            - [ ] Tests
                - [ ] Only admin can access edit product route
                - [ ] Admin can edit product with valid form data
                - [ ] Admin cannot edit product with invalid form data
        - [ ] Delete product (Tests)
            - [ ] Only admin can delete product
- [ ] Dispatcher
    - [ ] Orders (Tests)
        - [ ] Cannot access order not assigned to them
        - [ ] Can confirm order
        - [ ] Can dispatch order
        - [ ] Cannot update order status to delivered until it marked as delivered by the delivery driver
        - [ ] Can reject order
- [ ] Delivery driver
    - [ ] Orders (Tests)
        - [ ] Cannot access order not dispatched to them
        - [ ] Can update delivery status of the order
        - [ ] `IN_DELIVERY` delivery status is reflected on the order status
        - [ ] `DELIVERED` delivery status is not reflected on the order status
- [ ] My account
    - [ ] UI form `FULLNAME, EMAIL, PHONE_NUMBER, PASSWORD, CONFIRM PASSWORD, AVATAR`
    - [ ] Tests
        - [ ] Guest cannot access account route
        - [ ] User can access account route
        - [ ] User can update their account with valid form data
        - [ ] User cannot update their account with invalid form data
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
 - /order/{product}

### Database
orders | users | products
------------ | ------------- | -------------
id | id | id
product | fullname | name
customer_details | email | description
status __[`pending`, `confirmed`, `in_delivery`, `rejected`, `canceled`, `delivered`]__ | password | price
delivery_status __[`in_delivery`, `delivered`]__ | phone_number | image_path
dispatcher | role __[`admin`, `dispatcher`, `delivery_driver`]__ |
dispatched_to | avatar_path | 
token | status __[`pending`, `active`]__ | 
