# Plans & Subscription Management API

A robust REST API built with Laravel for managing subscription plans and user subscriptions. This project handles user authentication (via Sanctum), plan creation, subscription assignment, and automated background tasks to manage subscription statuses, past-due payments, and grace periods.

## Features

- **User Authentication:** Registration, login, and logout functionalities secured by Laravel Sanctum.
- **Plan Management:** Full CRUD operations for subscription plans (Name, Price, Duration).
- **Subscription Engine:** Allow users to subscribe to plans. Handles activation, trial periods, and expiration.
- **Automated Scheduler:** A built-in command scheduler that automatically updates subscription statuses (e.g., setting past-due subscriptions to canceled after a grace period).
- **Access Control:** Middleware (`CheckSubscriptionAccess`) to restrict access to premium features based on subscription validity.
- **Payment Webhook Handling:** Mock endpoints to handle payment failures and update subsciption status accordingly.

## Prerequisites

Before setting up the project, make sure you have the following installed on your machine:

- PHP >= 8.2
- Composer
- MySQL
- [Laragon](https://laragon.org/) (for local environment setup)

## Installation & Setup

Follow these steps to get the project up and running locally:

1. **Clone the repository:**
   ```bash
   git clone https://github.com/raghdahelmy/Subscription-Lifecycle-Engine.git
   cd Subscription-Lifecycle-Engine
   ```

2. **Install Composer Dependencies:**
   ```bash
   composer install
   ```

3. **Configure the Environment:**
   Copy the example `.env` file to create your own configuration:
   ```bash
   cp .env.example .env
   ```
   *Update the `.env` file with your Database credentials (`DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`).*

4. **Generate Application Key:**
   ```bash
   php artisan key:generate
   ```

5. **Run Database Migrations:**
   This will create the necessary tables in your database (`users`, `plans`, `subscriptions`, etc.):
   ```bash
   php artisan migrate
   ```

6. **Serve the Application:**
   Start the local development server:
   ```bash
   php artisan serve
   ```
   The API will be available at `http://127.0.0.1:8000`.

## Running Scheduled Tasks

This project includes scheduled tasks to automatically cancel expired subscriptions after their grace period. To run the scheduler locally during development, keep the following command running in a separate terminal:

```bash
php artisan schedule:work
```


## API Endpoints Overview

All endpoints are prefixed with `/api`. Requests containing data should send it as `application/json` along with the `Accept: application/json` header.

### Authentication
- `POST /api/register`: Register a new user.
- `POST /api/login`: Authenticate and receive a Sanctum token.
- `POST /api/logout`: Revoke the current user's token (Requires Bearer Token).

### Plans (Public / Admin)
- `GET /api/plans`: List all available plans.
- `GET /api/plans/{plan}`: Show a specific plan.
- `POST /api/plans`: Create a new plan (Requires Auth).
- `PUT /api/plans/{plan}`: Update an existing plan (Requires Auth).
- `DELETE /api/plans/{plan}`: Delete a plan (Requires Auth).

### Subscriptions
- `POST /api/subscribe`: Subscribe the authenticated user to a plan (Requires Auth).
- `POST /api/webhook/payment-failed`: Mock webhook endpoint to simulate a failed recurring payment.

## Exported Postman Collection

A Postman collection (`PlansManagement_API.postman_collection.json`) is included in the project root. You can import this file directly into Postman to test all the API endpoints effortlessly.


