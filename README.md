# Preventivas

Sistema web para planejamento, execução e validação de manutenções
preventivas de equipamentos e unidades operacionais.

O projeto foi desenvolvido com foco em um cenário real de operação de
TI, no qual uma preventiva pode envolver múltiplas filiais, unidades
operacionais, atividades, responsáveis, evidências e etapas de
aprovação.

> Projeto desenvolvido por Matheus Apestegui como aplicação prática de
> desenvolvimento com PHP, Laravel e Docker.

## Visão geral

O Preventivas organiza o ciclo completo de uma manutenção preventiva:

``` text
Configuração
    │
    ├── Tipos de Unidade
    ├── Perfis Operacionais
    ├── Unidades Operacionais
    ├── Tipos de Preventiva
    └── Atividades
            │
            ▼
      Perfil de Preventiva
            │
       ALL / SPECIFIC
            │
            ▼
        Preventiva
            │
         Snapshot
            │
            ▼
   Execução por unidade
            │
            ▼
        Validação
            │
            ▼
    Aprovação do gestor
```

A aplicação foi estruturada para separar configuração, regras de negócio
e execução. Uma preventiva criada não depende das configurações futuras
para manter seu histórico: a configuração utilizada é materializada em
snapshots no momento da criação.

## Principais funcionalidades

### Gestão de preventivas

-   Criação de preventivas por tipo, filial e responsável.
-   Definição das unidades operacionais participantes.
-   Associação de atividades às unidades.
-   Controle de status da preventiva.
-   Acompanhamento das preventivas em execução.
-   Finalização pelo técnico.
-   Envio para aprovação.
-   Aprovação, reprovação e reabertura pelo gestor.
-   Dashboard com visão geral das preventivas.

### Perfis de preventiva

Os perfis funcionam como templates reutilizáveis para determinar como um
tipo de preventiva será aplicado às unidades de uma ou mais filiais.

O modelo utiliza duas regras principais:

-   `ALL`: configuração padrão para todas as unidades elegíveis.
-   `SPECIFIC`: exceção aplicada a uma ou mais unidades específicas.

Exemplo:

``` text
ALL
├── Teste Operacional
├── Teste de Impressão
└── Teste de SSD

SPECIFIC
└── PDV 05
    └── Teste Operacional
```

Nesse caso, o PDV 05 utiliza somente a configuração específica, enquanto
as demais unidades utilizam a configuração padrão.

### Snapshot

Um dos pontos centrais da arquitetura é a preservação histórica.

Quando uma preventiva é criada, o sistema resolve a configuração do
perfil e grava uma fotografia daquele estado:

``` text
Perfil
  │
  ├── Regras ALL / SPECIFIC
  │
  ▼
Resolução das regras
  │
  ▼
Snapshot da preventiva
  │
  ├── Unidades
  ├── Atividades
  └── Relação unidade × atividade
  │
  ▼
Execução
```

Alterações futuras em perfis, atividades ou unidades não devem modificar
preventivas históricas já criadas.

### Execução

O técnico trabalha somente com as preventivas atribuídas a ele e executa
as atividades por unidade operacional.

O fluxo contempla:

1.  Seleção da preventiva.
2.  Início da execução.
3.  Seleção da unidade.
4.  Execução das atividades.
5.  Registro dos resultados.
6.  Registro de observações.
7.  Inclusão de evidência fotográfica quando aplicável.
8.  Finalização.
9.  Envio para aprovação.

### Validação

Após a finalização pelo técnico, a preventiva passa para análise do
gestor.

``` text
PENDING
   ↓
IN_PROGRESS
   ↓
AWAITING_APPROVAL
   ├──→ APPROVED
   │
   └──→ REOPENED
           ↓
       IN_PROGRESS
```

A reabertura permite que o gestor devolva uma preventiva para correção
ou complementação pelo técnico.

## Arquitetura

A aplicação utiliza uma separação de responsabilidades baseada em
camadas:

``` text
Migration
    ↓
Model
    ↓
Policy
    ↓
Form Request
    ↓
Service / Domain
    ↓
Controller
    ↓
Route
    ↓
Blade / JavaScript
```

Responsabilidades principais:

  Camada         Responsabilidade
  -------------- ---------------------------------------------------
  Migration      Estrutura, relacionamentos e integridade do banco
  Model          Entidades e relacionamentos Eloquent
  Policy         Autorização e controle de acesso
  Form Request   Validação dos dados recebidos
  Service        Regras de negócio e operações transacionais
  Controller     Coordenação do fluxo HTTP
  Route          Definição dos endpoints
  Blade          Interface da aplicação
  JavaScript     Interações e carregamento assíncrono

A aplicação também utiliza Services separados por responsabilidade,
incluindo fluxos de configuração, criação, execução, consulta,
continuação e validação.

## Tecnologias

### Backend

-   PHP 8.3
-   Laravel 13
-   Eloquent ORM
-   PHPUnit
-   Composer

### Banco de dados

-   MariaDB 10.11
-   Migrations
-   Relacionamentos e integridade referencial

### Frontend

-   Blade
-   JavaScript
-   Tailwind CSS 4
-   Vite 8
-   Laravel Vite Plugin

### Imagens e documentos

-   Intervention Image
-   Laravel DomPDF

Utilizados para processamento de evidências fotográficas e geração de
documentos PDF.

### Infraestrutura

-   Docker
-   Docker Compose
-   PHP-FPM
-   Nginx
-   Node.js 22
-   Redis
-   Xdebug
-   Linux

A aplicação possui ambiente Dockerizado com containers separados para
aplicação PHP, Nginx, MariaDB e ambiente Node para desenvolvimento
frontend.

## Estrutura do projeto

A estrutura principal segue o padrão do Laravel, com organização
adicional por domínio e responsabilidade:

``` text
app/
├── Http/
│   ├── Controllers/
│   ├── Requests/
│   └── ...
├── Models/
├── Policies/
├── Services/
│   ├── Configuration/
│   ├── Continuation/
│   ├── Creation/
│   ├── Execution/
│   ├── Query/
│   └── Validation/
└── ...

database/
├── factories/
├── migrations/
└── seeders/

docker/
├── nginx/
└── php/

resources/
├── css/
├── js/
└── views/

routes/
tests/
```

## Ambiente Docker

O projeto utiliza Docker Compose para padronizar o ambiente de
desenvolvimento.

Serviços principais:

``` text
preventivas-php
    PHP 8.3-FPM

preventivas-nginx
    Nginx + HTTPS

preventivas-db
    MariaDB 10.11

preventivas-node
    Node.js 22 + Vite
```

O container PHP também possui extensões necessárias para banco de dados,
imagens, internacionalização, processamento de arquivos e
desenvolvimento.

## Como executar

### Pré-requisitos

-   Docker
-   Docker Compose
-   Git

### Clone

``` bash
git clone https://github.com/matheusapest/Preventivas.git
cd Preventivas
```

### Configuração

``` bash
cp .env.example .env
```

Suba os containers:

``` bash
docker compose up -d --build
```

Instale as dependências PHP:

``` bash
docker compose exec app composer install
```

Gere a chave da aplicação:

``` bash
docker compose exec app php artisan key:generate
```

Execute as migrations:

``` bash
docker compose exec app php artisan migrate
```

Para desenvolvimento frontend, utilize o profile `dev`:

``` bash
docker compose --profile dev up -d node
```

Para gerar os assets:

``` bash
docker compose exec node npm install
docker compose exec node npm run build
```

As configurações de banco, domínio e demais parâmetros da aplicação
devem ser definidas no arquivo `.env`.

## Interface

O sistema possui um dashboard administrativo para acompanhamento do
estado das manutenções e preventivas.

Indicadores apresentados incluem:

-   Preventivas programadas.
-   Preventivas pendentes.
-   Preventivas em execução.
-   Preventivas aguardando aprovação.
-   Preventivas executadas.
-   Preventivas aprovadas.
-   Preventivas reprovadas.
-   Equipamentos aguardando recebimento.
-   Itens que demandam atenção do gestor.

![Dashboard do Preventivas](docs/screenshots/dashboard.png)

## Regras de negócio relevantes

O domínio foi modelado considerando alguns princípios:

-   Unidade operacional representa uma identidade física dentro de uma
    filial.
-   Perfil operacional representa a composição de uma unidade.
-   Tipo de unidade representa sua classificação.
-   Tipo de preventiva define as atividades disponíveis.
-   Perfil de preventiva funciona como template.
-   `ALL` define o comportamento padrão de uma filial.
-   `SPECIFIC` representa exceções à configuração padrão.
-   Uma preventiva recebe uma configuração congelada por snapshot.
-   O Controller não deve concentrar regras complexas de domínio.
-   Operações que alteram múltiplas entidades devem ocorrer dentro de
    transações.
-   Registros históricos não devem depender de alterações futuras em
    configurações.

## Integração com o cenário de ativos

O projeto foi concebido para trabalhar com unidades operacionais sem
transformar o módulo de preventivas em um segundo sistema de inventário.

Em um cenário corporativo, informações detalhadas de ativos podem
permanecer em um sistema de gestão patrimonial, enquanto o Preventivas
mantém o contexto necessário para planejar, executar e auditar as
manutenções.

## Objetivos técnicos do projeto

O desenvolvimento deste projeto teve como foco a aplicação prática de
conceitos de engenharia de software, incluindo:

-   Modelagem de domínio.
-   Separação de responsabilidades.
-   Regras de negócio no backend.
-   Autorização através de Policies.
-   Validação através de Form Requests.
-   Services para operações complexas.
-   Operações transacionais.
-   Snapshots para preservação histórica.
-   Relacionamentos Eloquent.
-   Dockerização do ambiente.
-   Processamento de imagens.
-   Geração de documentos.
-   Interface responsiva.
-   Organização de código para evolução futura.

## Status

O projeto está em desenvolvimento e serve também como projeto de
portfólio para demonstrar experiência prática com PHP, Laravel, Docker,
banco de dados e modelagem de aplicações web.

## Autor

**Matheus Apestegui**

Desenvolvedor PHP / Laravel

-   GitHub: https://github.com/matheusapest
-   Projeto: https://github.com/matheusapest/Preventivas

## Licença

Este projeto utiliza a licença MIT.
