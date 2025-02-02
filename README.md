# 📢 News Aggregator API

_A Laravel-based project that aggregates news from external APIs and stores it in a local database._

---

## 🚀 Getting Started

### 📂 Setup

#### 🔹 Step 1: Create the `.env` File
```sh
cp .env.example .env
```

#### 🔹 Step 2: Install Composer Dependencies
```sh
composer install
```

#### 🔹 Step 3: Run Docker Containers
```sh
docker-compose up -d --build
```

#### 🔹 Step 4: Install Dependencies Inside Docker
```sh
docker-compose exec app composer install
```

#### 🔹 Step 5: Generate Application Key
```sh
docker-compose exec app php artisan key:generate
```

#### 🔹 Step 6: Run Migrations & Seed Database
```sh
docker-compose exec app php artisan migrate --seed
```

#### 🔹 Step 7: Set Permissions
```sh
docker-compose exec app bash -c "
    chown -R www-data:www-data /var/www/html/storage;
    chmod -R 775 /var/www/html/storage;
    chmod -R 775 /var/www/html/bootstrap/cache;
"
```

---

## 🌐 Accessing the Application

| Service        | URL |
|---------------|-------------------------------|
| **Laravel App**  | [http://localhost:8000](http://localhost:8000) |
| **phpMyAdmin**  | [http://localhost:8080](http://localhost:8080) |
| **API Documentation (Swagger)**  | [http://localhost:8000/api/documentation](http://localhost:8000/api/documentation) |

---

## ✅ Running Tests

To run Laravel's unit tests:
```sh
docker-compose exec app php artisan test
```

---

## 📰 Fetching News from APIs

To populate the local database with news from external sources, run the following commands:

```sh
docker-compose exec app php artisan news:fetch newsapi
docker-compose exec app php artisan news:fetch nytimes
docker-compose exec app php artisan news:fetch guardian
```

---

## 🛠 Need Help?
For any issues, refer to Laravel's official documentation: [Laravel Docs](https://laravel.com/docs)

