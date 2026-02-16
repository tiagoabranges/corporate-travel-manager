# 🚀 Corporate Travel Manager

Sistema Full Stack para gerenciamento de pedidos de viagem corporativa.

- Arquitetura organizada e bem definida
- Regras de negócio implementadas no backend
- Controle de acesso por papel (admin / usuário)
- API REST segura com JWT
- Frontend moderno em Vue 3 + TypeScript
- Ambiente 100% containerizado com Docker
- Testes automatizados

---

# 🎯 Objetivo do Projeto

Este projeto foi desenvolvido com foco em demonstrar:

- Estruturação de uma API REST robusta
- Implementação de regras de negócio reais
- Autenticação stateless com JWT
- Controle de permissões baseado em papéis
- Organização de código no frontend com Vue 3 + TypeScript
- Padronização de respostas da API
- Testes automatizados no backend
- Ambiente isolado e reproduzível com Docker

O sistema simula um cenário corporativo de gestão de pedidos de viagem, com fluxo de aprovação e regras específicas.

---

# 📌 Sobre o Projeto

O **Corporate Travel Manager** permite o controle completo de pedidos de viagem corporativa.

O sistema permite:

- Criar pedidos de viagem
- Listar pedidos
- Aprovar ou cancelar pedidos (somente admin)
- Impedir cancelamento após aprovação
- Atualizar perfil do usuário
- Controle de acesso baseado em papéis
- Autenticação via JWT
- Testes automatizados no backend

---

# 🏗 Arquitetura

## 🔹 Backend — Laravel (API REST)

- Laravel 10
- Autenticação com JWT (`php-open-source-saver/jwt-auth`)
- Validações com regras claras
- Respostas padronizadas via `ApiResponse`
- Paginação e filtros
- Documentação automática via Swagger (OpenAPI)
- Testes automatizados usando SQLite in-memory

### 🔐 Regras de Negócio

- Apenas administradores podem alterar status
- Pedido aprovado não pode ser cancelado
- Usuários só podem editar/deletar seus próprios pedidos
- Administrador pode visualizar todos os pedidos
- Email não pode ser duplicado ao atualizar perfil

---

## 🔹 Frontend — Vue 3 + TypeScript

- Vue 3 (Composition API)
- TypeScript
- Axios com interceptor JWT
- Proteção de rotas com Router Guard
- Layout com Sidebar
- UI responsiva com Tailwind CSS
- Controle de exibição baseado no papel do usuário
- Feedback visual de erro e sucesso

---

# 🧠 Decisões Técnicas

Algumas decisões tomadas durante o desenvolvimento:

- Uso de JWT para manter a API stateless
- Uso de SQLite in-memory para testes rápidos e isolados
- Controle de permissões tanto no backend quanto no frontend
- Docker para garantir ambiente reproduzível
- Seed automático para facilitar testes do avaliador
- Swagger para documentação padronizada da API

---

# 🐳 Ambiente Docker

O projeto roda completamente via Docker.

## Serviços

### 🗄 MySQL
- Porta externa: `3307`
- Banco: `travel_management`
- Volume persistente

### ⚙ Backend
- Porta: `8000`
- Executa setup automático via `start.sh`

### 💻 Frontend
- Porta: `5173`
- Executa `npm run dev`

---

# 🚀 Como Rodar o Projeto

## 1️⃣ Clone o repositório

```bash
git clone git@github.com:tiagoabranges/corporate-travel-manager.git
cd corporate-travel-manager
```

## 2️⃣ Suba os containers

```bash
docker compose up -d --build
```

O script `start.sh` do backend executa automaticamente:

- composer install
- geração de APP_KEY
- geração de JWT_SECRET
- limpeza de cache
- migrations
- seed
- geração do Swagger

Nenhum comando adicional é necessário.

---

## 3️⃣ Acesse o sistema

### 🌐 Frontend
http://localhost:5173

### 🔌 Backend
http://localhost:8000/api

### 📘 Swagger (Documentação)
http://localhost:8000/api/documentation

---

# 🔑 Usuário Administrador de Teste

Ao subir o projeto, um usuário administrador é criado automaticamente via seed.

Você pode acessar com:

Email:
```
admin@travel.com
```

Senha:
```
123456
```

Esse usuário possui papel de **administrador**, podendo:

- Visualizar todos os pedidos
- Aprovar ou cancelar pedidos
- Testar as regras de negócio do sistema

---

# 👤 Criar Novo Usuário

Também é possível criar um novo usuário pela interface:

1. Acesse:
http://localhost:5173
2. Clique em **Criar conta**
3. Faça login normalmente

Usuários comuns poderão:

- Criar pedidos
- Visualizar apenas seus próprios pedidos
- Editar ou deletar seus pedidos (antes da aprovação)

---

# 🧪 Testando o Fluxo Completo

Sugestão de fluxo para testar o sistema:

1. Faça login como admin
2. Crie um novo usuário
3. Faça login com esse usuário
4. Crie um pedido
5. Volte ao admin
6. Aprove ou cancele o pedido
7. Valide as regras de negócio

---

# 🧪 Rodando os Testes

O backend possui testes automatizados.

Execute:

```bash
docker compose exec backend php artisan test
```

Os testes utilizam:

- SQLite em memória
- Ambiente isolado de testing
- JWT configurado para ambiente de teste

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

---

# 📦 Tecnologias Utilizadas

- Laravel 10
- Vue 3
- TypeScript
- MySQL 8
- JWT
- Tailwind CSS
- Docker
- Swagger (OpenAPI)
- PHPUnit

---

# 👨‍💻 Autor

Tiago Abranges  
Full Stack Developer  

Este projeto foi desenvolvido com o objetivo de treinar tomada de decisão arquitetural, organização de código e aplicação de boas práticas em um cenário próximo ao mundo real.

A proposta foi simular uma aplicação corporativa completa, trabalhando desde a modelagem de regras de negócio até autenticação, controle de acesso por papéis, testes automatizados e containerização com Docker.
