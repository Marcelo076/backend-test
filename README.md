# Como Rodar o Projeto Laravel + Vue.js

Este projeto utiliza Laravel no backend e Vue.js no frontend.

---

## Pré-requisitos

- PHP >= 8.x
- Composer
- Node.js e npm


---

## Passo 1: Clonar o repositório

```bash
git clone https://github.com/Marcelo076/backend-test.git
cd backend-test
```
## Passo 2: Instalar dependências do Laravel

```bash
composer install
 ```
## Passo 3: Configurar o arquivo .env

```bash
cp .env.example .env
 ```
## Passo 4: Gerar a chave do aplicativo

```bash
php artisan key:generate
 ```
## Passo 5: Rodar as migrations (criar tabelas no banco)

```bash
php artisan migrate
 ```

## Passo 6: Instalar dependências do frontend (Vue.js)

```bash
npm install
 ```
## Passo 7: Rodar o compilador do Vue.js

```bash
npm run dev
 ```
## php artisan serve

```bash
php artisan serve
 ```

##  O servidor Laravel estará disponível em:
http://localhost:8000

