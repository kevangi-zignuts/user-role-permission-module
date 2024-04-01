# **User Role Permission Module**

## Overview

The Laravel Education Management System has a Admin Side. Admin Can Add a teacher and Student. Admin also allocate subject to teachers and students. Admin also add institute and Teacher join institute. One Teacher associate with only one Institute but Institute has a many teachers

## Features

DashBoard :-

- Here in the Dashboard Add User Button which is use for add Teacher and Student
- In Dashboard there is an user index that show the user data and provide action button to view, edit and delete the user data.

Subjects :-

- Here in the Student Page there is an input page to add subject
- In Subject page there is an subject index that show subject name and in actions view teacher, view Student, delete Subject, edit Subject.

Teacher :-

- In the Teacher Page there is an teacher index that show teacher name and actions for add a institute, add a subject, view Subject, Delete and Edit Teacher details

Students :-

- In the Student Page there is an student index that show student name and actions for add a subject, view Subject, Delete and Edit Student details

Institution :-

- Here in the Institution Page there is an input page to add institution
- In Institution page there is an Institution index that show institution name and in actions add teacher, view Teacher, delete institute , edit institute.

## Requirements

- PHP: Version 8.1 or higher
- Composer: For PHP package management
- nodejs: Version 21.6.2 or higher
- npm: Version 10.6.4 or higher
- MySQL: As the preferred database system

## Installation

Step 1: \*\*Clone the Repository

Clone the repository to your local machine using Git.

```bash
$ git clone https://github.com/kevangi-zignuts/education-management-system.git
```

Step 2: Navigate to the Project Directory

Change your current directory to the project directory.

```bash
$ cd education-management-system
```

Step 3: Install Composer Dependencies

Install the PHP dependencies using Composer.

```bash
$ composer update
```

Step 4: Install Javascript Dependencies

Install the PHP dependencies using Composer.

```bash
$ npm install
```

Step 5: Copy the Environment File

Copy the .env.example file to .env.

```bash
$ cp .env.example .env
```

Step 6: Generate Application Key

Generate an application key.

```bash
$ php artisan key:generate
```

Step 7: Configure Database Connection

Configure your database connection in the .env file.

```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_username
DB_PASSWORD=your_database_password
```

Step 8: Run Migrations and Seeders

Run database migrations and seeders to create database tables and populate them with initial data.

```bash
$ php artisan migrate
$ php artisan migrate db:seed --class=UserSeeder
```

Step 9:

```bash
$ npm run dev
```

Step 9: Start the Development Server

Start the development server to run the application.

```bash
$ php artisan serve
```

Step 9: Access the Application

Open your web browser and visit http://localhost:8000 to access the application.
