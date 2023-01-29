1. Download the Project and steup on local enviroment.
2. Install Composer or update the composer  
3. Setup databsed which is located at root ditector with name testdb (2).sql

The Artisan Command which need to  run for import.

php artisan csv:import "<base_url>/ customers.csv"  "App\Models\Customer" 
php artisan csv:import "<base_url>/products.csv" "App\Models\Product" 


1. Order Retrive Api

Method:GET
Url: <base_url>/api/orders

2. Order Add Api
Method:POST
URL: <base_url>/api/orders/

{
    
    "customer": "1",
    "payed": "1",
    "products" :[
    	
		{"product_id":55
	
		}
		]
			     
}
3. Order Update API
Method : Put
URL:  <base_url>/api/orders/{id}
{
    "customer": "1",
    "payed": "1"
    
}
4. Order add product API
Methos:POST
API:<base_url>/api/orders/{id}/add
{
    "product_id": 55
}
5. Payment API
Methos:Post
URL: <base_url>/api/orders/{id}/pay
{
    "order_id": 23,
    "customer_email": "user@email.com",
    "value": 33.4
}
6. Delete Order API
Methos: DELETE
URL: <base_url>/api/orders/{id}
