# TestMaster Project

## Overview

**TestMaster** is a Laravel-based project that includes a comprehensive API for managing tasks and unit tests to ensure the functionality of the application. This README provides an overview of the project setup, configuration, and usage.

## Table of Contents

- [Features](#features)
- [Installation](#installation)
- [Configuration](#configuration)
- [Usage](#usage)
  - [Running the Application](#running-the-application)
  - [API Endpoints](#api-endpoints)
  - [Running Tests](#running-tests)
- [Contributing](#contributing)
- [License](#license)

## Features

- **Task Management API**: Create, read, update, and delete tasks.
- **Authentication**: User registration and login with Sanctum.
- **Unit Testing**: Tests for the TaskController to ensure correct functionality.

## Installation

To set up the project locally, follow these steps:

1. **Clone the Repository**

   ```bash
   git clone https://github.com/KenzaES/TestMaster.git
Navigate to Project Directory

bash
Copy code
cd TestMaster
Install Dependencies

Ensure you have Composer and Node.js installed. Then run:

bash
Copy code
composer install
npm install
Set Up Environment

Copy the .env.example file to a new file named .env:

bash
Copy code
cp .env.example .env
Update the .env file with your database and other configuration settings.

Generate Application Key

bash
Copy code
php artisan key:generate
Run Migrations

bash
Copy code
php artisan migrate
Seed the Database (Optional)

bash
Copy code
php artisan db:seed
Start the Development Server

bash
Copy code
php artisan serve
The application will be available at http://localhost:8000.

Configuration
Database: Configure your database connection in the .env file.

Sanctum: Ensure Sanctum is set up for API authentication. Run:

bash
Copy code
php artisan sanctum:install
Usage
Running the Application
Start the Laravel development server:

bash
Copy code
php artisan serve
Access the application via http://localhost:8000.

API Endpoints
Register User: POST /api/register
Login User: POST /api/login
Logout User: POST /api/logout
Add Task: POST /api/add/tasks
Get Tasks: GET /api/tasks
Get Task: GET /api/tasks/{id}
Update Task: PUT /api/edit/tasks/{id}
Delete Task: DELETE /api/tasks/{id}
Running Tests
To run the unit tests:

Run PHPUnit Tests

bash
Copy code
php artisan test
This will execute all tests, including those in tests/Feature/TaskControllerTest.php.

Run Specific Test File

bash
Copy code
./vendor/bin/phpunit tests/Feature/TaskControllerTest.php
Contributing
Contributions are welcome! Please follow these steps:

Fork the repository.
Create a feature branch (git checkout -b feature/YourFeature).
Commit your changes (git commit -am 'Add some feature').
Push to the branch (git push origin feature/YourFeature).
Create a new Pull Request.
License
This project is licensed under the MIT License. See the LICENSE file for details.

javascript
Copy code

This formatted `README.md` file includes detailed instructions for installation, configuration, and usage, along with the commands needed for running tests and contributing to the project.





