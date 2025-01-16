

# E_Commerce

This is a Laravel 11 project that includes API endpoints for managing products, orders, transactions, and user authentication. Below are the setup instructions, API documentation, and database schema details.

---

## Setup Instructions

### Prerequisites
- PHP 8.2 or higher
- Composer
- MySQL or any other supported database
- Laravel 11

### Installation Steps

1. **Clone the repository:**
   ```bash
   git clone https://github.com/AhmedWeb2022/Ecommerce.git
   cd <project-folder>
   ```

2. **Install dependencies:**
   ```bash
   composer install
   ```

3. **Create a `.env` file:**
   Copy the `.env.example` file to `.env` and update the database credentials:
   ```bash
   cp .env.example .env
   ```

4. **Generate the application key:**
   ```bash
   php artisan key:generate
   ```

5. **Run migrations:**
   ```bash
   php artisan migrate
   ```

6. **Seed the database (optional):**
   ```bash
   php artisan db:seed
   ```

7. **Start the development server:**
   ```bash
   php artisan serve
   ```

8. **Access the application:**
   Open your browser and go to `http://127.0.0.1:8000`.

---

## API Documentation

The API documentation is available on Postman. You can access it using the following links:

- **Postman Documentation:** [API Documentation](https://crimson-space-30579.postman.co/workspace/6ad63876-1d24-48c2-9905-7fbfb8e34cfb/documentation/39361869-497c47ae-a8f1-4bcf-a468-c7efce26b8d1)
- **Postman Collection:** [API Collection](https://crimson-space-30579.postman.co/workspace/New-Team-Workspace~6ad63876-1d24-48c2-9905-7fbfb8e34cfb/collection/39361869-497c47ae-a8f1-4bcf-a468-c7efce26b8d1?action=share&creator=39361869)

### API Endpoints

#### Authentication
- **Register:** `POST /register`
- **Login:** `POST /login`
- **Logout:** `POST /logout`

#### Products
- **Fetch all products:** `GET /products`
- **Show a product:** `GET /products/{id}`
- **Add a product:** `POST /products`
- **Update a product:** `PUT /products/{id}`
- **Delete a product:** `DELETE /products/{id}`

#### Orders
- **Fetch all orders:** `GET /orders`
- **Fetch order details:** `GET /orders/{id}`
- **Add an order:** `POST /orders`
- **Update an order:** `PUT /orders/{id}`
- **Delete an order:** `DELETE /orders/{id}`

#### Transactions
- **Create a transaction:** `POST /transaction`
- **Update transaction status:** `POST /transaction_update_status`

---

## Database Schema

### Tables and Relationships

1. **Users Table**
   - `id`: Primary key
   - `name`: User's name
   - `email`: User's email (unique)
   - `password`: User's password
   - `email_verified_at`: Timestamp for email verification
   - `remember_token`: Token for "remember me" functionality
   - `timestamps`: Created at and updated at timestamps

2. **Personal Access Tokens Table**
   - `id`: Primary key
   - `tokenable_id`: Polymorphic relation ID
   - `tokenable_type`: Polymorphic relation type
   - `name`: Token name
   - `token`: Unique token string
   - `abilities`: Token abilities (nullable)
   - `last_used_at`: Timestamp for last usage
   - `expires_at`: Token expiration timestamp
   - `timestamps`: Created at and updated at timestamps

3. **Products Table**
   - `id`: Primary key
   - `name`: Product name
   - `price`: Product price
   - `timestamps`: Created at and updated at timestamps
   - `softDeletes`: Soft delete functionality

4. **Orders Table**
   - `id`: Primary key
   - `user_id`: Foreign key referencing `users.id`
   - `total_amount`: Total amount of the order
   - `status`: Order status (0: pending, 1: successful, 2: failed)
   - `timestamps`: Created at and updated at timestamps
   - `softDeletes`: Soft delete functionality

5. **Order Products Table**
   - `id`: Primary key
   - `order_id`: Foreign key referencing `orders.id`
   - `product_id`: Foreign key referencing `products.id`
   - `quantity`: Quantity of the product in the order
   - `price`: Price of the product in the order
   - `timestamps`: Created at and updated at timestamps
   - `softDeletes`: Soft delete functionality

6. **Transactions Table**
   - `id`: Primary key
   - `user_id`: Foreign key referencing `users.id`
   - `order_id`: Foreign key referencing `orders.id`
   - `product_id`: Foreign key referencing `products.id`
   - `ammount`: Transaction amount
   - `status`: Transaction status (0: pending, 1: successful, 2: failed)
   - `timestamps`: Created at and updated at timestamps
   - `softDeletes`: Soft delete functionality

### Entity-Relationship Diagram (ERD)
You can view the ERD diagram for this project on DrawSQL:  
[PaySky ERD Diagram](https://drawsql.app/teams/backend-110/diagrams/paysky)

---
