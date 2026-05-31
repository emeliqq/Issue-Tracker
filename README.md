# Issue Tracker (MySQL Version)

## Database Setup

1. Create a new database:

```sql
CREATE DATABASE issue_tracker;
```

2. Import the schema file:

```text
database/issue_tracker.sql
```

using phpMyAdmin or MySQL CLI.

## Database Configuration

Verify connection settings in:

```text
config/Database.php
```

Default configuration:

```php
mysql:host=localhost;dbname=issue_tracker;charset=utf8
username: root
password:
```

## Running the Application

1. Place the project inside:

```text
xampp/htdocs/
```

2. Start:

* Apache
* MySQL

3. Open:

```text
http://localhost/issue-tracker/public
```

## Notes

This branch contains the MySQL-based version of the application and is used for further cloud deployment development.
