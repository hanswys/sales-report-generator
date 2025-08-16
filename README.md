🔹 Backend Goals (Laravel + DB)
	•	Add MySQL support → currently you’re using SQLite for quick setup. Add MySQL as the primary DB in config/database.php with an .env switch so the app can run locally (SQLite) and in production (MySQL).
	•	Implement Redis caching for:
	•	Query caching (e.g., top-selling products report).

⸻

🔹 Frontend Goals (Bootstrap + jQuery)
	•	Enhance reports page UI:
	•	Add Bootstrap 4.6 table styling & pagination controls.
	•	Add search & filtering (by product, date range) using jQuery + AJAX.

⸻

🔹 Background Jobs (Queues & Workers)
	•	Queue heavy tasks:
	•	Offload Excel/PDF export to queues (instead of running synchronously).

⸻

🔹 Infrastructure & Deployment
	•	Containerization: Add a Dockerfile and docker-compose.yml to mimic production stack (MySQL, Redis, Nginx).
	•	Nginx config setup: Create a sample config for serving the app in production.
	•	GitLab CI/CD pipeline:
	•	Run PHPUnit tests.
	•	Deploy automatically to DigitalOcean VPS.
	•	Environment configs: .env.example with MySQL, Redis, and mail setup for portability.