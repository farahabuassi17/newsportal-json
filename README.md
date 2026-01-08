# 📰 News Management System (PHP + JSON + Docker)

A simple **News Management System** built with **PHP** and **Bootstrap**, containerized using **Docker**, and using **JSON file storage** instead of a traditional database.

This project allows users to register, log in, manage categories, add/edit/delete news, and restore deleted news.  
It is designed for **learning, academic purposes, and easy deployment**.

---

## ✨ Features

- User Authentication (Register / Login / Logout)
- Category Management (Add / Edit / Delete)
- News Management:
  - Add news with image upload
  - Edit news
  - Soft delete news
  - Restore deleted news
- View news per logged-in user
- JSON-based data storage (No MySQL)
- Dockerized environment
- Simple health check endpoint

---

## 🛠️ Technologies Used

- PHP 8.2
- Bootstrap 5
- JSON (as a replacement for MySQL)
- Docker & Docker Compose
- Apache

---

## 📁 Project Structure
```
├── src/
│ ├── add_category.php
│ ├── add_news.php
│ ├── categories.php
│ ├── dashboard.php
│ ├── delete_category.php
│ ├── delete_news.php
│ ├── deleted_news.php
│ ├── edit_category.php
│ ├── edit_news.php
│ ├── index.php
│ ├── json_db.php
│ ├── logout.php
│ ├── new.php
│ ├── registre.php
│ ├── restore.php
│ ├── health.php
│ └── uploads/
├── storage/
│ └── data.json
├── Dockerfile
├── docker-compose.yml
├── Makefile
├── .gitignore
├── .dockerignore
└── README.md
```
---

## 📦 Data Storage (JSON)

Instead of using MySQL, the application stores all data in a single JSON file:

### Example structure:

```json
{
  "users": [],
  "categories": [],
  "news": []
}
```

This approach simplifies deployment and removes the need for a database server.

### 🐳 Docker Setup

Build the project :make build
Run the application:make run
Build and run:make run-build
Stop containers:make stop
View logs:make logs

### 🌐 Access the Application

### 🔐 Authentication Flow

1. Register a new account

2. Log in using your credentials

3. Manage categories and news

4. Log out safely using PHP sessions

### Soft Delete & Restore

- Deleted news is not permanently removed

- It is marked as deleted = true

- Deleted items can be restored from the Deleted News page

### Why JSON Instead of MySQL?

- No database server required
- Easy to understand and debug
- Suitable for small projects and academic use
- Faster setup and deployment
- Ideal for Docker-based demonstrations

### 📌 Notes

- Uploaded images are stored in src/uploads/

- data.json is included and contains sample data

- Docker health check ensures the application is running properly

### 👩‍💻 Author

Farah Mahmoud Abuassi
Software Development Student
Islamic University of Gaza – Faculty of Information Technology
