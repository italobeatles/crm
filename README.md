# CRM

Aplicação de CRM desenvolvida em Laravel, Bootstrap 5 e AdminLTE.

## Stack

- PHP 8.4
- Laravel 13
- MySQL
- Bootstrap 5
- AdminLTE
- Vite

## Recursos

- Autenticação de usuários
- Dashboard comercial
- Gestão de leads com conversão
- Gestão de clientes e contatos
- Oportunidades com pipeline
- Atividades e lembretes
- Relatórios com exportação CSV
- Administração de usuários e parâmetros

## Instalação

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm run build
php artisan serve
```

## Acesso demo

- `admin@crm.local` / `admin123`
- `gestor@crm.local` / `admin123`
- `vendedor@crm.local` / `admin123`
