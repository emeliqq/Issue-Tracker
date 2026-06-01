# Issue Tracker

A web application for tracking, managing, and organizing software issues.

## Features

* User registration and authentication
* Create new issues
* View all issues
* View issue details
* Assign issues to users
* Update issue status
* Update issue severity
* Dashboard with reported and assigned issues

## Technologies

* PHP 8
* MySQL 8
* HTML
* CSS
* JavaScript
* Docker
* Docker Compose
* GitHub Actions

## Project Structure

The application follows a modular architecture with separate layers for:

* Models
* Views
* Configuration
* Database access

Issue and user data are stored in a MySQL database.

## Running the Project

### Requirements

* Docker Desktop
* Git

### Clone the repository

```bash
git clone https://github.com/emeliqq/Issue-Tracker.git
cd Issue-Tracker
```

### Start the application

```bash
docker compose up -d
```

The application will be available at:

```text
http://localhost:8080/public
```

### Stop the application

```bash
docker compose down
```

## Database

The database schema is automatically created during the first startup using:

```text
database/issue_tracker.sql
```

MySQL data is stored in a persistent Docker volume, so users and issues remain available after container restarts.

## Continuous Integration

The project uses GitHub Actions for continuous integration.

On every push to the repository, GitHub automatically builds the Docker image and verifies that the application can be successfully containerized.

## Security

Implemented security mechanisms:

* Password hashing using PHP `password_hash()`
* Password verification using PHP `password_verify()`
* Prepared SQL statements (PDO)
* Session-based authentication
* Output escaping using `htmlspecialchars()`

## Goal

The goal of this project is to build a scalable and extendable issue tracking system while learning web development, containerization, CI/CD pipelines, and cloud deployment concepts.
