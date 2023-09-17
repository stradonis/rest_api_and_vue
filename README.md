# project launch

go to it container
docker exec -it php-fpm bash

in the project directory
`composer install`

go to it container
`docker-compose exec encore bash`

and run

`npm install`
`npm run build`

if the problem with adding message, change the directory permissions:
`public/messages`

#### project

http://localhost:8002/home

#### api

create message:

POST http://localhost:8002/api/messages
in body (json): {"message":"my test message"}

get all message:

GET http://localhost:8002/api/messages?sort=[{"selector":"id","desc":true}]

get message

GET http://localhost:8002/api/messages/{messageUuid}