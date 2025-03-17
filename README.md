# Library Management System - Docker Setup

This repository contains a dockerized version of the Library Management System.

## Prerequisites

- Docker
- Docker Compose

## Getting Started

1. Clone the repository:
```bash
git clone <repository-url>
cd librarymanagement
```

2. Build and start the containers:
```bash
docker-compose up -d --build
```

3. Access the application:
- Web Application: http://localhost:8080
- MySQL Database:
  - Host: localhost
  - Port: 3306
  - Username: root
  - Password: root_password
  - Database: library

4. Stop the containers:
```bash
docker-compose down
```

## Directory Structure

```
librarymanagement/
├── admin/
│   ├── admin.php
│   ├── enter_book.php
│   ├── returnbook.php
│   └── ...
├── Dockerfile
├── docker-compose.yml
├── config.php
└── README.md
```

## Environment Variables

The following environment variables are used:

- `MYSQL_HOST`: Database host (default: db)
- `MYSQL_USER`: Database user (default: root)
- `MYSQL_PASSWORD`: Database password (default: root_password)
- `MYSQL_DB`: Database name (default: library)

## Notes

- The web server runs on port 8080
- MySQL runs on port 3306
- All data is persisted in Docker volumes 