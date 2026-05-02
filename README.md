# CRM

Aplicação de CRM desenvolvida com Laravel, Bootstrap 5 e AdminLTE, focada em demonstração de portfólio e operação comercial de pequeno porte.

## Visão Geral

O projeto cobre o fluxo principal de um CRM:

- autenticação e perfis de acesso
- dashboard comercial
- gestão de leads com conversão em cliente
- cadastro de clientes e contatos
- oportunidades com pipeline comercial
- atividades e acompanhamento operacional
- relatórios com exportação CSV
- administração de usuários e parâmetros

## Stack

- PHP 8.4
- Laravel 13
- MySQL
- Bootstrap 5
- AdminLTE
- Vite
- AlertifyJS

## Requisitos

- PHP 8.4+
- Composer 2+
- Node.js 24+
- npm 11+
- MySQL ou MariaDB

## Instalação

1. Clonar o repositório.
2. Instalar dependências do backend:

```bash
composer install
```

3. Instalar dependências do frontend:

```bash
npm install
```

4. Criar o arquivo de ambiente:

```bash
cp .env.example .env
```

No Windows PowerShell, você pode usar:

```powershell
Copy-Item .env.example .env
```

5. Ajustar as variáveis do banco no `.env`.

Exemplo para XAMPP:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=crm_simples
DB_USERNAME=root
DB_PASSWORD=
```

6. Gerar a chave da aplicação:

```bash
php artisan key:generate
```

7. Executar migrations e seeders:

```bash
php artisan migrate --seed
```

8. Gerar os assets:

```bash
npm run build
```

9. Subir a aplicação localmente:

```bash
php artisan serve
```

Endereço padrão:

- `http://127.0.0.1:8000`

## Dados Demo

Usuários iniciais:

- `admin@crm.local` / `admin123`
- `gestor@crm.local` / `admin123`
- `vendedor@crm.local` / `admin123`

O seeder também popula o banco com massa fictícia de aproximadamente 2 anos de operação.

## Comandos Úteis

Rodar testes:

```bash
php artisan test
```

Rodar build frontend:

```bash
npm run build
```

Rodar frontend em modo desenvolvimento:

```bash
npm run dev
```

Resetar banco com dados demo:

```bash
php artisan migrate:fresh --seed
```

## Estrutura Funcional

- `app/Http/Controllers`: controllers dos módulos do CRM
- `app/Http/Requests`: validações
- `app/Models`: entidades de domínio
- `app/Services`: regras de negócio mais específicas
- `database/migrations`: estrutura do banco
- `database/seeders`: carga demo
- `resources/views`: telas Blade com AdminLTE
- `resources/js`: interações frontend e pipeline drag-and-drop

## CI

O repositório inclui workflow GitHub Actions para:

- instalar dependências PHP e Node
- validar build frontend
- executar a suíte de testes

## Licença

Uso demonstrativo e educacional.
