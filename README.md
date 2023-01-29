1. Download the Project and steup on local enviroment.
2. Install Composer or update the composer  
3. Setup databsed which is located at root ditector with name testdb (2).sql

The Artisan Command which need to  run for import.

php artisan csv:import "<base_url>/ customers.csv"  "App\Models\Customer" 
php artisan csv:import "<base_url>/products.csv" "App\Models\Product" 




GET:  <base_url>/api/orders
POST: <base_url>/api/orders/

{
    
    "customer": "1",
    "payed": "1",
    "products" :[
    	
		{"product_id":55
	
		}
		]
			     
}
PUT <base_url>/api/orders/{id}
{
    "customer": "1",
    "payed": "1"
    
}
POST:<base_url>/api/orders/{id}/add
{
    "product_id": 55
}

POST: <base_url>/api/orders/{id}/pay
{
    "order_id": 23,
    "customer_email": "user@email.com",
    "value": 33.4
}
DELETE: <base_url>/api/orders/{id}
{
    "order_id": 23,
    "customer_email": "user@email.com",
    "value": 33.4
}
