## Getting started

Install Docker
```
For windows
https://docs.docker.com/desktop/install/windows-install/
For Ubuntu
https://docs.docker.com/engine/install/ubuntu/
For Mac
https://docs.docker.com/desktop/install/mac-install/
```

Clone Project
```
$ git clone git@github.com:cinoyraz31/medicine-outgoing-stocks.git
$ cd medicine-outgoing-stocks
$ cp .env.example .env
```

Running Docker Compose
```
$ docker-compose build #create image
$ docker-compose up -d #create container
```

Composer Install
```
docker exec -it medicine-outgoing-stocks composer install
docker exec -it medicine-outgoing-stocks php artisan key:generate
```

DB Migration
```
docker exec -it medicine-outgoing-stocks php artisan migrate
docker exec -it medicine-outgoing-stocks php artisan db:seed
```

Running Application
```
http://localhost:8000
can you download on /postman
```
Design Pattern
```
Model
Responses -> content.json.php trying to equalize the standard mvc rules
Controller -> Only call service and render response
Services -> The service insipartion from https://github.com/AaronLasseigne/active_interaction
```

Design Flow Diagram

<img src="https://drive.google.com/uc?id=1GT1ipNlW1yJU7j6qWWwU-RbLptuprdUV">
