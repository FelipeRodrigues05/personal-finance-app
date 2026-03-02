# Personal Finance App

O **Personal Finance App** é um sistema criado para ajudar pessoas a finalmente terem clareza sobre sua vida financeira.

Muita gente não tem uma forma simples, visual e organizada de acompanhar gastos e foi justamente isso que me motivou a construir esta aplicação.

Além disso, o projeto trouxe um desafio técnico interessante: estruturar uma aplicação complexa usando FilamentPHP, com forte manipulação de dados, múltiplos tipos de transações, uploads, integrações e persistência distribuída.

O resultado é um sistema moderno, robusto e fácil de usar.

---

## 📊 Visão Geral do Projeto

O sistema oferece:
- Dashboard intuitiva com overview financeiro
- Registro de despesas e receitas
- Controle de gastos no cartão
- Rendimentos de investimentos
- Upload de vouchers (comprovantes) para Amazon S3
- Admin Panel completo com FilamentPHP
- Infra moderna com:
  - Docker
  - Redis (cache e filas) 
  - PostgreSQL
  - AWS S3

## 🧩 Tecnologias Utilizadas
| Tecnologia                         | Descrição                                        |
| ---------------------------------- | ------------------------------------------------ |
| **PHP 8.4**                        | Backend moderno e tipado                         |
| **Laravel 12**                     | Core da aplicação, rotas, serviços e arquitetura |
| **FilamentPHP**                    | Painel administrativo completo                   |
| **PostgreSQL**                     | Banco de dados principal                         |
| **Redis**                          | Cache, filas e otimizações                       |
| **Amazon S3**                      | Armazenamento de vouchers (comprovantes)         |
| **Docker / Docker Compose**        | Ambiente containerizado                          |
| **TailwindCSS**                    | Estilização frontend                             |
| **Arquitetura Services + Actions** | Organização limpa e escalável                    |

--- 

## 🚀 Funcionalidades Implementadas

- Gestão completa de despesas e receitas
- Gastos no cartão com histórico detalhado
- Rendas de investimentos
- Dashboard com gráficos e indicadores
- Upload e consulta de vouchers direto na S3
- CRUDs administrativos via FilamentPHP
- Sistema otimizado com Redis para cache

---

## 🔧 Principais Desafios Técnicos

- Organização da manipulação de dados financeiros com múltiplas origens
- Integração profunda com FilamentPHP, mantendo a UI simples e escalável
- Sincronização de dados com PostgreSQL e Redis
- Estrutura de uploads e processamento para S3
- Construção de uma arquitetura limpa, separando responsabilidades

---

## 🛠️ Funcionalidades em Desenvolvimento

Gestão de grupos de pessoas
> compartilhamento financeiro entre membros da família ou equipes

Suporte multilíngue
> multi-idioma nativo

Melhorias visuais na UI
> novos dashboards, transições e refinamento da experiência

---

## 📦 Como Rodar o Projeto (Docker)
1. Clone o repositório
```bash
git clone https://github.com/FelipeRodrigues05/personal-finance-app
```

2. Acesse a pasta
```bash
cd personal-finance-app
```

3. Copie o arquivo de ambiente
```bash
cp .env.example .env
```

Configure:
**PostgreSQL
Redis
S3 (bucket, keys, region)**

4. Suba os containers
```bash
docker compose up -d --build
```

5. Instale dependências
```bash
docker compose exec app composer install
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate
```
6. Acesse a aplicação
```bash
http://localhost:8000
```

---

## 📁 Estrutura do Projeto
```
app/
 ├── Filament/
 ├── Http/
 ├── Models/
 ├── Services/
 ├── Actions/
database/
docker/
resources/
routes/
```

---

## 🤝 Contribuições

Sinta-se livre para abrir Issues e Pull Requests.
Críticas, ideias e feedbacks são sempre bem-vindos.

📜 Licença

Este projeto está sob a licença MIT.
