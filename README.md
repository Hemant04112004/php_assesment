# PHP Skills Test - Laravel Solution

## Summary
This project implements the requested inventory submission page using Laravel, Bootstrap, HTML, JavaScript, and CSS.

Implemented features:
- Form fields: Product name, Quantity in stock, Price per item.
- Ajax form submission (no full page reload).
- Data persisted to JSON file at storage/app/products.json.
- Table display ordered by datetime submitted.
- Columns shown in required order:
  - Product name
  - Quantity in stock
  - Price per item
  - Datetime submitted
  - Total value number
- Total value number computed as Quantity in stock x Price per item.
- Sum total row at the bottom.
- Extra credit: inline edit and save for each row (Ajax).

Also included for Laravel skills coverage:
- Routes and controller implementation.
- Migration and seeder files for a Product model example.

## Routes
- GET /
- GET /products
- POST /products
- PUT /products/{id}

## Run Locally
1. Install dependencies:

	composer install

2. Ensure env exists:

	copy .env.example .env

3. Generate key:

	php artisan key:generate

4. Start server:

	php artisan serve

5. Open:

	http://127.0.0.1:8000

## Notes
- This solution does not require a database to run the form workflow because it stores submissions in JSON.
- Optional migration/seeding artifacts are included under database/migrations and database/seeders.
