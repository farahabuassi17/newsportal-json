# Technical Notes

## Biggest Technical Challenge and Solution

The biggest challenge I faced in this project was **managing the database layer using Docker and MySQL**.
Initially, the application depended on a MySQL container, and several issues appeared, such as missing tables, schema mismatches, and Docker volume persistence problems.

I learned that MySQL initialization scripts are executed **only on the first container run**.
Because Docker volumes persist data, schema changes were not applied automatically, which caused runtime errors in the PHP application.

To solve this problem and simplify the architecture, I decided to **replace MySQL completely with JSON-based storage**.
I redesigned the application logic to store users, categories, and news inside a single `data.json` file.
This eliminated database dependency issues, simplified Docker setup, and made the project easier to run and understand.

This change improved stability and made the project more suitable for academic and learning purposes.

---

## Data Storage Refactoring (MySQL → JSON)

Instead of using a relational database, the project now uses a **JSON file as a lightweight data store**.

All application data is stored in: torage/data.json

This required refactoring:
- Authentication logic
- CRUD operations for categories and news
- Soft delete and restore functionality
- Manual data relationships (e.g., linking news to categories and users)

This approach removed the need for database containers and volumes while keeping the application fully functional.

---

## Docker and Containerization Lessons

I learned how to properly containerize a PHP application using Docker.
The project now runs using a single web container with Apache and PHP.

### Docker Compose

Docker Compose is used to manage the application services.
Even though the project now uses a single service, Docker Compose provides:
- A clean and consistent startup process
- One-command execution for the entire project
- Easy future extensibility

---

## Multi-stage Docker Build

I used a **multi-stage Docker build** to follow best practices:
- The first stage prepares PHP and required extensions
- The final stage contains only the necessary runtime files

This reduces the final image size and improves performance.

---

## Healthcheck Implementation

A Docker healthcheck was added to ensure the application is running correctly.

A simple endpoint:health.php

returns HTTP 200 if the application is healthy.
Docker periodically checks this endpoint and marks the container as healthy or unhealthy.
This improves reliability and follows containerization best practices.

---

## Makefile Usage

A Makefile was added to simplify Docker commands.
Common tasks such as building, running, stopping, and cleaning the project can be executed using simple commands like:
make build
make run
make stop

This makes the project easier to use and understand for other developers.

---

## Most Important Git & GitHub Lessons

The most important lesson I learned is the importance of a **clean and professional Git workflow**.

I practiced:
- Initializing a Git repository
- Writing clear and meaningful commit messages
- Using feature branches
- Opening Pull Requests
- Merging changes into the main branch

This helped me understand how GitHub is used in real-world software development to track progress, review code, and collaborate effectively.

---

## Pull Request Workflow

All major changes, including the migration from MySQL to JSON, were implemented using a **feature branch**.
The changes were then merged into the main branch using a **Pull Request** with a clear description.

This demonstrates a professional development workflow similar to industry practices.

---

## Final Reflection

This project helped me understand:
- How Docker works in real applications
- The impact of architectural decisions (database vs file-based storage)
- The importance of simplicity and maintainability
- How version control supports professional development

Overall, this project strengthened my practical skills in PHP, Docker, and GitHub workflows.

---

**This update was implemented using a feature branch and merged via Pull Request.**



