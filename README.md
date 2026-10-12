# Task CLI

Aplicação de linha de comando para cadastrar e gerenciar tarefas pessoais. Permite criar, listar, consultar, concluir e excluir tarefas, com opção de filtrar a listagem por status. Os dados são armazenados em PostgreSQL.

## Funcionalidades

- Criar tarefas com título;
- Listar tarefas, opcionalmente filtrando por status;
- Consultar uma tarefa pelo ID;
- Concluir uma tarefa;
- Excluir uma tarefa.

## Sumário

- [Pré-requisitos](#pré-requisitos)
- [Como executar](#como-executar)
- [Comandos disponíveis](#comandos-disponíveis)
- [Tecnologias utilizadas](#tecnologias-utilizadas)

## Pré-requisitos

- Docker Engine;
- Docker Compose v2;
- Git, caso queira clonar o repositório.

## Como executar

Clone o repositório e acesse a pasta do projeto:

```bash
git clone <URL_DO_REPOSITORIO>
cd task-cli
```

Crie o arquivo `.env` a partir do exemplo:

```bash
cp .env.example .env
```

O `.env.example` contém os valores necessários para executar o projeto localmente. Inicie os serviços:

```bash
docker compose up --build -d
```

O comando inicia o container PHP e o PostgreSQL. Na primeira inicialização, o PostgreSQL cria a tabela de tarefas usando `database/schema.sql`; o container da aplicação instala as dependências do Composer.

Para acompanhar os logs:

```bash
docker compose logs -f app
```

Execute os comandos da aplicação com `docker compose exec`:

```bash
docker compose exec app php bin/console
```

Para parar os containers:

```bash
docker compose down
```

Os dados do PostgreSQL são mantidos no volume Docker `pgdata` ao parar os containers dessa forma.

## Comandos disponíveis

```bash
# Exibir os comandos disponíveis
docker compose exec app php bin/console

# Criar uma tarefa
docker compose exec app php bin/console task:add "Estudar PHP"

# Listar todas as tarefas
docker compose exec app php bin/console task:list

# Filtrar tarefas por status (pending ou completed)
docker compose exec app php bin/console task:list --status=pending

# Exibir uma tarefa pelo ID
docker compose exec app php bin/console task:show 1

# Concluir uma tarefa pelo ID
docker compose exec app php bin/console task:complete 1

# Excluir uma tarefa pelo ID
docker compose exec app php bin/console task:delete 1
```

## Tecnologias utilizadas

- **PHP 8.4 CLI** - Linguagem e execução dos comandos;
- **Symfony Console** - Criação da interface de linha de comando;
- **PDO PostgreSQL** - Acesso ao banco de dados;
- **PostgreSQL 17** - Banco de dados relacional;
- **Composer** - Gerenciamento de dependências e autoload;
- **Carbon** - Biblioteca para manipulação de datas;
- **Docker e Docker Compose** - Ambiente local da aplicação e do banco de dados.