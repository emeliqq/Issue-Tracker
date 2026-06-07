# Issue Tracker

## Requirements

Before running the application, install:

* XAMPP
* PHP 8.x
* Apache
* MySQL

## Setup

### 1. Clone repository

```bash
git clone <repository-url>
```

### 2. Place project in XAMPP

Copy the project to:

```text
xampp/htdocs/
```

Example:

```text
xampp/htdocs/issue-tracker
```

### 3. Start XAMPP services

Open XAMPP Control Panel and start:

* Apache
* MySQL

### 4. Open application

Navigate to:

```text
http://localhost/issue-tracker/public
```
## REST API

GET /public/api/issues.php
Returns all issues.

GET /public/api/issue.php?id={id}
Returns a single issue.

POST /public/api/issues.php
Creates a new issue using JSON payload.

## Notes

This branch contains the web application version of the project.

Additional development, database migration, Docker deployment and cloud-related features are available in other branches.
