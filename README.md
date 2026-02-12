# Corporate Travel Manager

Full Stack application for managing corporate travel requests.

## Tech Stack

- Backend: Laravel (API REST)
- Frontend: Vue.js
- Authentication: JWT
- Database: MySQL
- Infrastructure: Docker

## Status

🚧 Project initialization phase
# Setup after clone

docker compose up -d --build
docker compose exec backend php artisan key:generate
docker compose exec backend php artisan migrate