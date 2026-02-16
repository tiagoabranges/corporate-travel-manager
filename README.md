# 🚀 Corporate Travel Manager

Sistema Full Stack para gerenciamento de pedidos de viagem corporativa.

Desenvolvido como desafio técnico com foco em:

- Arquitetura limpa
- Regras de negócio bem definidas
- Controle de acesso por papel (admin / usuário)
- API REST segura com JWT
- Frontend moderno em Vue 3 + TypeScript
- Ambiente 100% containerizado com Docker

---

# 📌 Sobre o Projeto

O **Corporate Travel Manager** é uma aplicação que permite o controle completo de pedidos de viagem corporativa.

O sistema permite:

- Criar pedidos de viagem
- Listar pedidos com filtros
- Aprovar ou cancelar pedidos (somente admin)
- Impedir cancelamento após aprovação
- Atualizar perfil do usuário
- Enviar notificações quando status é alterado
- Controle de acesso baseado em papéis
- Autenticação via JWT
- Testes automatizados no backend

---

# 🏗 Arquitetura

## 🔹 Backend — Laravel (API REST)

- Laravel 10
- Autenticação com JWT (`php-open-source-saver/jwt-auth`)
- Validações via Form Requests
- Regras de negócio aplicadas no controller
- Respostas padronizadas via `ApiResponse`
- Paginação com filtros
- Notificações no update de status
- Testes automatizados com SQLite in-memory
- Documentação via Swagger (OpenAPI)

### 🔐 Regras de Negócio

- Apenas administradores podem alterar status
- Pedido aprovado não pode ser cancelado
- Usuários só podem editar/deletar seus próprios pedidos
- Admin pode visualizar todos os pedidos
- Email não pode ser duplicado ao atualizar perfil

---

## 🔹 Frontend — Vue 3 + TypeScript

- Vue 3 (Composition API)
- TypeScript
- Axios com interceptor JWT
- Proteção de rotas com Router Guard
- Layout com Sidebar
- UI responsiva com Tailwind CSS
- Controle de exibição baseado em papel do usuário
- Feedback visual de erros e sucesso
- Dashboard com separação de formulário e listagem

---

# 🐳 Ambiente Docker

O projeto roda completamente via Docker.

## Serviços

### 🗄 MySQL
- Porta: `3307`
- Banco: `travel_management`

### ⚙ Backend
- Porta: `8000`
- Executa `php artisan serve`

### 💻 Frontend
- Porta: `5173`
- Executa `npm run dev`

---

# 🚀 Como Rodar o Projeto

## 1️⃣ Clone o repositório

```bash
git clone https://github.com/seuusuario/corporate-travel-manager.git
cd corporate-travel-manager
```



```bash
docker compose up -d --build
```

## 3️⃣ Gere a chave da aplicação

```bash
docker compose exec backend php artisan key:generate
```

## 4️⃣ Rode as migrations

```bash
docker compose exec backend php artisan migrate
```

## 5️⃣ Acesse o sistema

### 🌐 Frontend
http://localhost:5173

### 🔌 Backend
http://localhost:8000/api

### 📘 Swagger
http://localhost:8000/api/documentation

---

# 🧪 Rodando os Testes

O backend possui testes automatizados.

```bash
docker compose exec backend php artisan test
```

Os testes utilizam:

- SQLite em memória  
- Ambiente isolado de testing  
- JWT configurado para testes  

---

# 👥 Papéis do Sistema

## 👤 Usuário Comum

- Criar pedido  
- Visualizar seus pedidos  
- Editar/deletar seus pedidos (antes de aprovação)  

## 👑 Administrador

- Visualizar todos os pedidos  
- Aprovar ou cancelar pedidos  
- Não pode editar/deletar pedidos de outros usuários  
- Pode editar/deletar seus próprios pedidos  

---

# 📂 Estrutura do Projeto

```
corporate-travel-manager/
│
├── backend/
│   ├── app/
│   ├── routes/
│   ├── tests/
│   ├── docker/
│
├── frontend/
│   ├── src/
│   ├── components/
│   ├── views/
│   ├── layouts/
│
├── docker-compose.yml
```