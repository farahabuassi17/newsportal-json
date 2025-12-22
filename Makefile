build:
	docker compose build

run:
	docker compose up

run-build:
	docker compose up --build

stop:
	docker compose down

clean:
	docker compose down -v

logs:
	docker compose logs -f
