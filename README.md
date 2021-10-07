# Point System

## Question 1

This is a test question given to solve for the PHP Developer role.

## Database Details

Database tables are created using Laravel's schema migrations. Seeders are created for demo products and for one customer.

Use this command for database seeding.

```bash
php artisan migrate:fresh --seed
```

## UML Diagram

UML Diagram is generated using [laravel-to-uml](https://github.com/andyabih/laravel-to-uml) package.

![UML Diagram](https://i.ibb.co/89r5Kgk/UML-Diagram.jpg)

## MYSQL database schema dump

```sql
SET NAMES utf8mb4;


# Dump of table users
# ------------------------------------------------------------

CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `is_customer` tinyint(1) NOT NULL DEFAULT '1',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


# Dump of table orders
# ------------------------------------------------------------

CREATE TABLE `orders` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `type` enum('normal','promotional') COLLATE utf8mb4_unicode_ci NOT NULL,
  `completed` tinyint(1) NOT NULL DEFAULT '0',
  `received` decimal(8,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `orders_user_id_foreign` (`user_id`),
  CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table points
# ------------------------------------------------------------

CREATE TABLE `points` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `point` decimal(8,2) NOT NULL,
  `available` decimal(8,2) NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `points_user_id_foreign` (`user_id`),
  CONSTRAINT `points_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


# Dump of table order_product
# ------------------------------------------------------------

CREATE TABLE `order_product` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) unsigned NOT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_product_order_id_foreign` (`order_id`),
  KEY `order_product_product_id_foreign` (`product_id`),
  CONSTRAINT `order_product_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  CONSTRAINT `order_product_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


# Dump of table products
# ------------------------------------------------------------

CREATE TABLE `products` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `normal_price` decimal(8,2) NOT NULL,
  `promotional_price` decimal(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

```

## Function to reward point after order completion

```php
if($this->pointToUse)
{
    $this->point = $this->pointToUse;
    auth()->user()->active_points->each(function($active) {
        if($active->available < $this->point)
        {
            $this->point-=$active->available;
            $active->available = 0;
            $active->save();
        }
        else
        {
            $active->available-=$this->point;
            $active->save();
            $this->point = 0;
        }
    });
}
$point = floor($this->totalWithDiscount);
auth()->user()->points()->create([
    'point' => $point,
    'expires_at' => now()->addYear(),
    'available' => $point,
]);
$this->order->completed = true;
$this->order->received = $this->totalWithDiscount;
$this->order->save();
```

## Question 2

For the question given:
```sql
SELECT COUNT(DISTINCT `Orders`.Order_ID) as Number_Of_Order,SUM(IF(`Orders`.Sales_Type = 'Normal',`Orders_Products`.`Normal_Price`,`Orders_Products`.`Promotional_Price`)) as Total_Sales_Amount
FROM `Orders`
inner join `Orders_Products` on `Orders`.Order_ID = `Orders_Products`.Order_ID
```
For the schema I generated

```sql
SELECT COUNT(DISTINCT `orders`.id) as Number_Of_Order,SUM(IF(`orders`.type = 'normal',`products`.`normal_price`,`products`.`promotional_price`)) as Total_Sales_Amount
FROM `orders`
inner join `order_product` on `orders`.id = `order_product`.order_id
inner join `products` on `products`.id = `order_product`.product_id
```

## Question 3 

Total New Amount will be MYR 4.72 with MYR 0.28 GST

```php
    $gstPercentage = 6; // Given GST percentage 6%
    $includedAmount = 5.00; // Given GST Included amopunt MYR 5.00
    $netAmount = round($includedAmount*100/(100+$gstPercentage),2); // Net Amount excluding GST = 4.72
    $gstAmount = $includedAmount-$netAmount; // GST Amount = 0.28
```
