# API de Autenticação 

O projeto **backend-login** é uma API RESTful desenvolvida com Laravel 8 e MySQL. O objetivo é receber e validar os dados do formulário de login da aplicação **frontend-login**. 

## Requisitos do sistema ✅

- Composer
- Docker
- Docker Compose
- git


## Clonando o Projeto 📋

Para clonar o projeto, abra um terminal no diretório desejado e execute o seguinte comando:

```bash
git clone https://github.com/glucasmr/backend-login.git
```

Após clonar o repositório, entre no diretório do projeto:

```bash
cd backend-login
```

## Configuração Inicial 🔧

Copie o arquivo `.env.example` para `.env` para configurar o ambiente:

```bash
cp .env.example .env
```

## Instalação e Configuração do Laravel Sail 🚀

Instale as dependências do projeto:

```bash
composer install --ignore-platform-reqs
```

Inicie os contêineres Docker com Laravel Sail 🐳:

```bash
./vendor/bin/sail up --build
```

Em uma nova aba do terminal, no diretório raíz do projeto, gere a chave da aplicação Laravel 🔑:

```bash
./vendor/bin/sail artisan key:generate
```

Execute as migrações para criar as tabelas no banco de dados 🗃️:

```bash
./vendor/bin/sail artisan migrate
```

Se desejar, você pode popular o banco de dados com dados de exemplo executando as seeds:

```bash
./vendor/bin/sail artisan db:seed
```

## Como Rodar os Testes 🧪

Execute os testes de integração com:

```bash
./vendor/bin/sail artisan test
```

## Documentação da API 📄

Para gerar a documentação execute o seguinte comando:
```bash
./vendor/bin/sail php artisan l5-swagger:generate
```

Se tiver ocorrido tudo bem, a documentação estará disponível em `http://localhost/api/documentation`. 

## Acessando a Aplicação 🌐

A API estará acessível através do `http://localhost:80`.


## Possíveis Erros e Soluções 🛠️

- **Erro**: Porta `3306` já está em uso 🚫.
  - **Solução**: Verifique se nenhum outro serviço está usando a porta `3306`. Se necessário, ajuste a porta no seu arquivo `.env` e `docker-compose.yml`.

- **Erro**: Permissões ao executar o Sail ⚠️.
  - **Solução**: Execute os comandos do Sail com `sudo` ou adicione seu usuário ao grupo Docker.

- **Erro**: Acesso negado ao acessar o Banco de dados 🛡️.
  - **Solução**: Execute os comando no Sail:
  ```bash
  ./vendor/bin/sail down --rmi all -v
  ```
  - E execute novamente:
  ```bash
  ./vendor/bin/sail up --build
  ```


## Contribuindo 🤝

Sinta-se à vontade para contribuir com o projeto. Abra uma issue ou envie um pull request com suas sugestões e melhorias.