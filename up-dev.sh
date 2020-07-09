#docker network create itu_shared

docker-compose -f nginx-proxy/docker-compose.yaml up -d
docker-compose -f portfolio/docker-compose.yaml up -d 
docker-compose -f blog/docker-compose.yaml up -d


docker-compose -f blog/docker-compose.yaml exec -T nginx ash -c "npm install"
docker-compose -f blog/docker-compose.yaml exec -T nginx ash -c "npm run dev"
docker-compose -f blog/docker-compose.yaml exec -T php bash -c "composer install"
docker-compose -f blog/docker-compose.yaml exec -T php php artisan migrate:refresh --seed