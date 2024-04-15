# **User Role Permission Module**

## Overview

This project is a user role management system developed using Laravel. It provides functionalities for user authentication, role management, module management, and permission management.

## Features

User Authentication :-

- Admins can securely log in using their registered email ID and password.
- Remember Me feature for convenient access.
- Forget Password option to recover forgotten passwords.
- Password reset functionality via email.

Admin Side :-

Dashboard :-

- Upon successful login, admins are redirected to the dashboard.
- Dashboard displays statistics such as total active modules, permissions, roles, and users.

Modules Management :-

- Dynamic creation of parent-child relation modules.
- Seeding modules with module codes for identification.
- List view for all modules with search and filter options.
- Edit functionality to update module name and description.

Permissions Management :-

- CRUD operations for permissions.
- Permissions are assigned module-wise with options for All, Create, Edit, View, and Delete permissions.
- List view for all permissions with search, filter, and pagination.

Role Management :-

- CRUD operations for roles.
- Multi-select permission dropdown for role creation and editing.
- List view for all roles with search, filter, and pagination.

User Management :-

- Provides CRUD operations for users with features like searching, filtering, and pagination.
- Allows admins to set new passwords, edit user details, and delete users.
- Admins can reset user passwords, triggering an email notification to the user.
- Includes a force logout feature to log out users from all devices upon admin action.

User Side :-

Accept Invitation :-

- Clicking 'Accept Invite' in the email redirects the user to the platform, displaying a welcome message and prompting them to set their password. After setting and confirming the password, clicking 'Save' redirects the user directly to their dashboard.

Dashboard :-

- View user details, edit personal information, change password, and access permitted modules. Edit button allows modification of name, contact, and address; change password prompts for current, new, and confirm passwords, redirecting to login with new password.

Sidebar :-

- View assigned modules and submodules, with collapsible main modules revealing their respective submodules.

Contact - People Module :-

- View, search, and filter people with permission-dependent actions such as edit, delete, and create; edit and delete actions controlled by user permissions.
- User-permission-based control over creating, editing, and deleting people with respective form functionalities and confirmation popups.

Contact - Company Module :-

- View, search, and list companies with permission-dependent actions for editing and deleting; creation controlled by user permissions.
- Create/Edit/Delete: User-permission-based control over creating, editing, and deleting companies with respective form functionalities and confirmation popups.

Account - Notes Module :-

- View and search notes with permission-based actions for editing and deleting; creation controlled by user permissions.
- Create/Edit/Delete: User-permission-based control over creating, editing, and deleting notes with respective form functionalities and confirmation popups.

Account - Meetings Module :-

- View, search, and filter meetings with permission-based actions for editing and deleting; automatic deactivation of past meetings.
- Create/Edit/Delete: User-permission-based control over creating, editing, and deleting meetings with form functionalities ensuring future dates and times, and confirmation popups.

Account - Activity Log :-

- View, search, and filter activity log with permission-based actions for editing and deleting; creation controlled by user permissions.

- Create/Edit/Delete: User-permission-based control over creating, editing, and deleting activity log entries with respective form functionalities and confirmation popups.

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
$ git clone https://github.com/kevangi-zignuts/user-role-permission-module.git
```

Step 2: Navigate to the Project Directory

Change your current directory to the project directory.

```bash
$ cd user-role-permission-module
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

```bash
MAIL_MAILER=smtp
MAIL_HOST=smtp.example.com
MAIL_PORT=587
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@example.com
MAIL_FROM_NAME="${APP_NAME}"
```

Step 8: Run Migrations and Seeders

Run database migrations and seeders to create database tables and populate them with initial data.

```bash
$ php artisan migrate --seed
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
