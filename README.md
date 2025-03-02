# Banking System - Laravel-v10

This banking system, built with Laravel, supports authentication with registration, login, and 2FA. The first user registered is assigned as an admin. Admins can create savings accounts with a unique account number and an initial balance of $10,000. Users can transfer funds, view transaction history, and manage accounts. Multi-currency transfers (USD, GBP, EUR) are supported with real-time conversion using exchangeratesapi.io, applying a 0.01 spread. Admins can search and filter accounts efficiently. The system ensures security and accuracy in financial operations. The project is developed in PHP, Laravel, HTML, CSS, and JavaScript

## Installation

### Prerequisites

- PHP >= 8.1
- Composer
- Node
- MySQL

Clone the repository:

`git clone https://github.com/muhajirvt/banking-system.git`

### Install dependencies:

`composer install`

`npm install`

`npm run dev`

### Set up the environment file:

`cp .env.example .env`

Configure your .env file with your database credentials and add new variable for currency exchange rate api key

```
EXCHANGE_RATE_API_KEY={api_key}
```

clear config `php artisan config:clear` and for generating key `php artisan key:generate`

### Run migrations:
    
`php artisan migrate`

### Serve the application:

`php artisan serve`

