<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

# Constr Up API

API REST de produtos desenvolvida em Laravel e executada via Docker.

## Sobre o projeto

Aplicação de API REST para cadastro e consulta de produtos. Inclui filtros por
query string, ordenação por nome e preço, e retornos padronizados para erros de
não encontrado.

## Arquitetura

- Controllers: recebem a requisição, validam via Form Requests e retornam JSON.
- Services: concentram as regras de negócio e acesso ao modelo.
- Models: representam as entidades do banco de dados.

## Requisitos

- Docker
- Docker Compose

## Instalação e execução (Docker)

1. Clone o repositório:

```
git clone https://github.com/Felipe1208/Constr-Up-API.git
cd Constr-Up-API
```

2. Copie o arquivo de ambiente:

```
cp .env.example .env
```

3. Suba os containers:

```
docker compose up -d --build
```

4. Entre no container da aplicação:

```
docker compose exec app sh
```

5. Instale as dependências (dentro do container):

```
composer install
```

6. Gere a chave da aplicação:

```
php artisan key:generate
```

7. Rode as migrações:

```
php artisan migrate
```

8. Popule o banco com dados de exemplo:

```
php artisan db:seed
```

## Instalação e execução (sem Docker)

### Requisitos locais

- PHP 8.2+ com extensões: pdo_mysql, mbstring, bcmath, intl, zip
- Composer
- MySQL (opcional, se não usar SQLite)

### Passo a passo

1. Clone o repositório:

```
git clone https://github.com/Felipe1208/Constr-Up-API.git
cd Constr-Up-API
```

2. Copie o arquivo de ambiente:

```
cp .env.example .env
```

3. Configure o banco no `.env`:

- **MySQL (local):**
  - `DB_CONNECTION=mysql`
  - `DB_HOST=127.0.0.1`
  - `DB_PORT=3306`
  - `DB_DATABASE=construp_api`
  - `DB_USERNAME=seu_usuario`
  - `DB_PASSWORD=sua_senha`

- **SQLite (mais simples):**
  - `DB_CONNECTION=sqlite`
  - `DB_DATABASE=database/database.sqlite`
  - Crie o arquivo:

```
touch database/database.sqlite
```

4. Instale as dependências:

```
composer install
```

5. Gere a chave da aplicação:

```
php artisan key:generate
```

6. Rode as migrações:

```
php artisan migrate
```

7. Popule o banco com dados de exemplo:

```
php artisan db:seed
```

8. Inicie o servidor local:

```
php artisan serve
```

## Acessar a API

- Docker: http://localhost:8080/api
- Local (artisan serve): http://localhost:8000/api

## Testes

Os testes estão em `tests/Feature` e cobrem os endpoints da API.

Rodar todos os testes de Feature (dentro do container):

```
php artisan test --testsuite=Feature
```

Rodar um teste específico:

```
php artisan test --filter=ProductControllerTest
```

## Postman

Importe a coleção `products.postman_collection.json` no Postman para testar as rotas.

## Parar os containers

```
docker compose down
```
