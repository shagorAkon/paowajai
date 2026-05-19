# 📦 Paowajai Enterprise Ecommerce Platform

A premium, production-ready full-stack e-commerce platform built for high-performance importing and retailing. Features a modular Laravel 12 API-first backend, interactive Vue.js 3 storefront SPA with custom glassmorphism styles, automated Pathao/RedX courier bookings, integrated mobile payments (bKash/Nagad/SSLCommerz), and full Redis caching.

---

## 🛠️ Tech Stack & Architecture

* **Backend:** Laravel 12 (API-first design, Service-Repository pattern, Spatie Roles/Permissions)
* **Frontend:** Vue.js 3 SPA (Composition API, Pinia, Vue Router, Tailwind CSS v4)
* **Datastore:** MySQL 8.0 & Redis Cache/Queue
* **Orchestration:** Docker Compose & Nginx Reverse Proxy
* **Deployment Stacker:** Multi-stage production Dockerfile

---

## 💻 Git Version Control & GitHub Setup

Execute the following commands in sequence to initialize your project, connect with version control, and push to GitHub:

### 1. Initialize Local Repository
```bash
# Initialize local git directory
git init

# Add all project source files to staging
git add .

# Create initial baseline commit
git commit -m "feat: complete catalog, storefront SPA, MFS payments, and Docker orchestration layers"
```

### 2. Configure GitHub Repository
Connect this codebase with your GitHub account:
```bash
# Rename default branch to main
git branch -M main

# Add your repository as origin remote
git remote add origin https://github.com/shagorAkon/paowajai.git

# Push current main branch to remote origin
git push -u origin main
```

---

## 🐳 Docker Deployment Instructions

This project is packaged with a high-performance multi-stage Docker environment (OPcache configurations, multi-layer asset building, static asset CDN-like caching) ready for staging or live deployment.

### 📋 Environment Setup
First, prepare the configuration template:
```bash
# Copy production environment template
cp .env.example .env

# Generate unique secure encryption application key
docker compose run --rm app php artisan key:generate
```

### 🔨 Managing the Docker Lifecyle

#### 1. Build and Run Containers
```bash
# Build the images and run containers in background (detached mode)
docker compose up -d --build
```

#### 2. Verify Status & Logs
```bash
# Check status of running containers
docker compose ps

# Inspect real-time container log output
docker compose logs -f
```

#### 3. Database Migrations & Seeders
Execute database structures and seed default roles, banners, and demo imports:
```bash
# Run backend database migrations inside the FPM container
docker compose exec app php artisan migrate

# Seed catalog categories, permissions, super admin accounts, and catalog products
docker compose exec app php artisan db:seed
```

#### 4. Active Queue Workers
Launch or check the background asynchronous processors:
```bash
# Work queues are automatically managed in background via the 'queue' service.
# If you make modifications and need to force refresh queues:
docker compose restart queue
```

#### 5. Storage Symlink
Link the storage uploads disk to the public-facing directory:
```bash
docker compose exec app php artisan storage:link
```

#### 6. Production Optimizations
```bash
# Clear outdated configs and cache the configurations, routes, and views
docker compose exec app php artisan optimize
```

#### 7. Stop and Restart Lifecycle
```bash
# Restart all containers in orchestration
docker compose restart

# Tear down running containers and internal bridges
docker compose down

# Stop containers without wiping volume networks
docker compose stop
```

---

## 📂 Docker Container Details

* **Nginx Web Server (`web`):** Listens on port `80`. Serves static images/styles with custom 1-year client caching policies and delegates PHP traffic securely to the FPM service.
* **Laravel FPM Backend (`app`):** Runs PHP 8.2-FPM with GD, Zip, PDO MySQL, and PECL Redis extensions. Contains production OPcache configuration.
* **Worker Service (`queue`):** Spawns a secondary execution threads handling background job retries and async payment/courier webhooks.
* **MySQL Store (`mysql`):** Dedicated instance running MySQL 8.0, mapping native data volume backups dynamically inside `./docker/db_data`.
* **Redis Cache (`redis`):** Running memory caching supporting session variables, cart items, and lightning-fast homepage lookups.

---

## 🧪 Running Staging Tests
To confirm purchase calculations, Bangladesh division-specific shipping rates, and coupon formulas are working inside the isolated container environment:
```bash
docker compose exec app php artisan test --filter CheckoutTest
```

---
*Created and maintained by [shagorAkon](https://github.com/shagorAkon).*
