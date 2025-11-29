# Lucrare de laborator nr. 6
Rularea unui site PHP pe baza a doua containere: Nginx + PHP-FPM

## 1. Titlul lucrarii de laborator
Lucrare de laborator nr. 6 – Aplicatie PHP rulata prin Docker Compose utilizand containere Nginx si PHP-FPM.

## 2. Scopul lucrarii
Scopul lucrarii este de a intelege modul in care mai multe containere Docker pot lucra impreuna:
- Nginx pentru gestionarea cererilor HTTP,
- PHP-FPM pentru executia codului PHP,
- MariaDB pentru baza de date,
- comunicarea intre containere printr-o retea comuna definita in Docker Compose.

## 3. Sarcina
Sa se creeze o aplicatie PHP functionala folosind Docker Compose, cu urmatoarele servicii:
1. frontend – Nginx
2. backend – PHP-FPM cu extensii mysqli si pdo_mysql
3. database – MariaDB

Aplicatia trebuie sa fie accesibila prin browser la `http://localhost:8080`.

## 4. Descriere detaliata a etapelor efectuate

### 4.1 Structura proiectului
```
laborator6/
├── docker-compose.yml
├── site/
│   └── index.php
├── backend/
│   └── Dockerfile
└── nginx/
    └── default.conf
```

### 4.2 Continutul fisierului docker-compose.yml
```
version: '3.9'

services:
  frontend:
    image: nginx:1.23-alpine
    container_name: frontend
    ports:
      - "8080:80"
    volumes:
      - ./site:/var/www/html
      - ./nginx/default.conf:/etc/nginx/conf.d/default.conf
    depends_on:
      - backend
    networks:
      - lab6_network

  backend:
    build: ./backend
    container_name: backend
    volumes:
      - ./site:/var/www/html
    networks:
      - lab6_network

  database:
    image: mariadb:10.5
    container_name: database
    restart: always
    environment:
      MYSQL_ROOT_PASSWORD: root
      MYSQL_DATABASE: testdb
    volumes:
      - ./db_data:/var/lib/mysql
    networks:
      - lab6_network

networks:
  lab6_network:
    driver: bridge
```

### 4.3 Continutul fisierului backend/Dockerfile
```
FROM php:7.4-fpm
RUN docker-php-ext-install mysqli pdo pdo_mysql
```

### 4.4 Continutul fisierului nginx/default.conf
```
server {
    listen 80;
    server_name _;
    root /var/www/html;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$args;
    }

    location ~ \.php$ {
        fastcgi_pass backend:9000;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

### 4.5 Comenzile utilizate
Crearea retelei:
```
docker network create lab6_network
```

Pornirea aplicatiei:
```
docker compose up -d --build
```

Afisarea containerelor:
```
docker ps
```

Oprirea serviciilor:
```
docker compose down
```

### 4.6 Capturi de ecran
<img width="596" height="175" alt="image" src="https://github.com/user-attachments/assets/a6dc9f59-64f6-4b67-8cd5-d18f28d13f74" />

- Lista containerelor (`docker ps`)
- Browser: `http://localhost:8080`

## 5. Concluzii
Utilizarea Docker Compose pentru aplicatii multi-container aduce multiple avantaje:
- configuratii centralizate si usor de gestionat,
- pornirea tuturor containerelor printr-o singura comanda,
- comunicarea automata intre servicii prin intermediul retelelor,
- arhitectura modulara si scalabila,
- separarea clara intre frontend, backend si baza de date.

Aceasta abordare simplifica dezvoltarea, testarea si mentenanta aplicatiilor moderne.
