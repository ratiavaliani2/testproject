Alsow nesesery for Setup

php artisan jwt:secret

Api endpoints

Register
http://127.0.0.1:8000/api/users/register?name=test&email=test@test.com&password=12345678

Login
http://127.0.0.1:8000/api/login?email=test@test.com&password=12345678

Cart List
http://127.0.0.1:8000/api/cart

Add Cart Item
http://127.0.0.1:8000/api/cart/create?product_id=2

Remove Cart Item
http://127.0.0.1:8000/api/cart/remove?product_id=2

Add Amount Item (how many items)
http://127.0.0.1:8000/api/cart/add_amount?product_id=2

Reduce Amount Item (how many items)
http://127.0.0.1:8000/api/cart/reduce_amount?product_id=2

Update User
http://127.0.0.1:8000/api/user/update/1?name=testtt&email=testtts@testtt.com&password=987654321

User logout
http://127.0.0.1:8000/api/user/logout

Order
http://127.0.0.1:8000/api/cart/order

Checkout
http://127.0.0.1:8000/api/cart/checkout
