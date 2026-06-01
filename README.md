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

### Start the application

Clone the repository:

```bash
git clone <repository-url>
cd issue-tracker
```

Start containers:

```bash
docker compose up -d
```

The application will be available at:

```text
http://localhost:8080/public
```

### Stop containers

```bash
docker compose down
```

## Database

The database schema is automatically created during the first startup using:

```text
database/issue_tracker.sql
```

MySQL data is stored in a persistent Docker volume, so data remains available after container restarts.

## Goal

The goal of this project is to build a scalable and extendable issue tracking system while learning web development, containerization, and cloud deployment concepts.
