# BVI Company Formation Backend API

A robust Laravel 11 backend API for BVI company formation applications with auto-save functionality, comprehensive validation, and real-time form management.

## 🚀 Features

-   **Laravel 11** - Latest framework with PHP 8.2+ support
-   **RESTful API** - Clean API design with proper HTTP methods
-   **Auto-save Functionality** - Real-time form data persistence
-   **Comprehensive Validation** - Multi-step form validation with custom rules
-   **Session Management** - Form session tracking and cleanup (clean-up is todo)
-   **Reference Numbers** - Unique tracking for each application
-   **Status Tracking** - Application lifecycle management
-   **Error Handling** - Proper error responses and logging
-   **Database Migrations** - Structured database schema

## 📋 Requirements

-   **PHP 8.2+** (Required for Laravel 11)
-   **Composer 2.0+**
-   **MySQL 8.0+ / PostgreSQL 13+ / SQLite 3.35+**

## 🛠️ Installation

### 1. Clone and Setup

\`\`\`bash

# Install PHP dependencies

composer install

# Copy environment file

cp .env.example .env

# Generate application key

php artisan key:generate
\`\`\`

### 2. Database Configuration

Edit `.env` file with your database credentials:

\`\`\`env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=bvi_company_formation
DB_USERNAME=your_username
DB_PASSWORD=your_password
\`\`\`

### 3. Database Setup

\`\`\`bash

# Run migrations

php artisan migrate

# (Optional) Seed with sample data

php artisan db:seed
\`\`\`

### 4. Start Development Server

\`\`\`bash

# Start Laravel development server

php artisan serve

# API will be available at: http://localhost:8000

\`\`\`

## 📡 API Endpoints

### Base URL: `http://localhost:8000/api/v1`

### Company Formation

| Method | Endpoint                                      | Description                 |
| ------ | --------------------------------------------- | --------------------------- |
| `POST` | `/company-formation`                          | Submit complete application |
| `POST` | `/company-formation/auto-save`                | Auto-save form data         |
| `GET`  | `/company-formation/form-data`                | Get saved form data         |
| `GET`  | `/company-formation/status/{referenceNumber}` | Get application status      |

### Health Check

| Method | Endpoint  | Description                        |
| ------ | --------- | ---------------------------------- |
| `GET`  | `/health` | API health check with version info |

## 📝 API Usage Examples

### 1. Submit Complete Application

\`\`\`bash
curl -X POST http://localhost:8000/api/v1/company-formation \
 -H "Content-Type: application/json" \
 -H "Accept: application/json" \
 -d '{
"point_of_contact": {
"full_name": "John Doe",
"email": "john@example.com"
},
"company_info": {
"company_name": "Tech Innovations Ltd",
"designation": "ltd"
},
"countries_of_interest": {
"jurisdiction_of_operation": "us"
},
"shares_structure": {
"number_of_shares": 1000,
"all_shares_issued": true,
"value_per_share": 1.00
},
"shareholders": [...],
"beneficial_owners": [...],
"directors": [...]
}'
\`\`\`

### 2. Auto-save Form Data

\`\`\`bash
curl -X POST http://localhost:8000/api/v1/company-formation/auto-save \
 -H "Content-Type: application/json" \
 -d '{
"session_id": "session_123456789",
"step": 1,
"data": {
"pointOfContact": {
"fullName": "John Doe",
"email": "john@example.com"
}
}
}'
\`\`\`

### 3. Get Application Status

\`\`\`bash
curl -X GET http://localhost:8000/api/v1/company-formation/status/BVI-2024-ABC123 \
 -H "Accept: application/json"
\`\`\`

### 4. Health Check

\`\`\`bash
curl -X GET http://localhost:8000/api/v1/health \
 -H "Accept: application/json"
\`\`\`

## 🗄️ Database Schema

### `company_formations` Table

| Column                     | Type      | Description                               |
| -------------------------- | --------- | ----------------------------------------- |
| `id`                       | bigint    | Primary key                               |
| `reference_number`         | string    | Unique application reference              |
| `status`                   | enum      | Application status                        |
| `company_name`             | string    | Company name                              |
| `alternative_company_name` | string    | Alternative name (nullable)               |
| `designation`              | enum      | Company designation (ltd, inc, corp, llc) |
| `point_of_contact`         | json      | Contact person details                    |
| `company_info`             | json      | Company information                       |
| `countries_of_interest`    | json      | Jurisdiction details                      |
| `shares_structure`         | json      | Share structure details                   |
| `shareholders`             | json      | Shareholders array                        |
| `beneficial_owners`        | json      | Beneficial owners array                   |
| `directors`                | json      | Directors array                           |
| `submitted_at`             | timestamp | Submission timestamp                      |
| `processed_at`             | timestamp | Processing timestamp                      |
| `notes`                    | text      | Admin notes                               |
| `created_at`               | timestamp | Creation timestamp                        |
| `updated_at`               | timestamp | Last update timestamp                     |
| `deleted_at`               | timestamp | Soft delete timestamp                     |

### `form_sessions` Table

| Column          | Type      | Description               |
| --------------- | --------- | ------------------------- |
| `id`            | bigint    | Primary key               |
| `session_id`    | string    | Unique session identifier |
| `current_step`  | integer   | Current form step (1-4)   |
| `form_data`     | json      | Form data snapshot        |
| `last_saved_at` | timestamp | Last save timestamp       |
| `created_at`    | timestamp | Creation timestamp        |
| `updated_at`    | timestamp | Last update timestamp     |

## TODO

-   clean-up command for sessions form
