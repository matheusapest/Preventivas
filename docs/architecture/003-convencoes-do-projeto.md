# Convenções do Projeto

## Objetivo

Este documento define os padrões adotados durante o desenvolvimento do sistema Preventivas.

O objetivo é garantir consistência entre todos os módulos da aplicação, facilitar a manutenção do código e permitir que qualquer desenvolvedor compreenda rapidamente os padrões utilizados.

Todas as novas funcionalidades deverão seguir estas convenções.

---

# Convenções de Nomenclatura

## Banco de Dados

As tabelas devem utilizar nomes em inglês e no plural.

Exemplos:

- companies
- branches
- branch_codes
- categories
- manufacturers
- models
- equipments

As colunas também devem utilizar nomes em inglês.

Exemplos:

- name
- description
- active
- company_id
- branch_id
- equipment_model_id

---

## Models

As Models seguem o padrão do Laravel.

Devem possuir nomes em inglês e no singular.

Exemplos:

- Company
- Branch
- BranchCode
- Category
- Manufacturer
- EquipmentModel
- Equipment

Sempre que possível, deve-se utilizar a convenção padrão do Eloquent, evitando definir manualmente o atributo:

```php
protected $table
```

Apenas quando realmente necessário.

---

## Controllers

Os Controllers representam uma única entidade.

Exemplos:

- CompanyController
- BranchController
- CategoryController
- EquipmentController

Cada Controller deve possuir apenas as responsabilidades relacionadas à sua entidade.

---

## Policies

Cada entidade possui sua própria Policy.

Exemplo:

- CompanyPolicy
- BranchPolicy
- EquipmentPolicy

As Policies são responsáveis exclusivamente pelas regras de autorização.

---

## Form Requests

Cada CRUD possui dois FormRequests independentes.

Exemplo:

- StoreEquipmentRequest
- UpdateEquipmentRequest

Essa separação permite que regras diferentes sejam aplicadas durante cadastro e edição.

---

# Convenções do Banco

## Chaves Primárias

Todas as tabelas utilizam:

```php
$table->id();
```

---

## Foreign Keys

Todas as chaves estrangeiras seguem o padrão:

```php
company_id

branch_id

manufacturer_id

equipment_model_id
```

Sempre utilizando:

```php
->constrained()
->restrictOnDelete()
->cascadeOnUpdate()
```

Salvo quando a regra de negócio exigir comportamento diferente.

---

## Campo Active

Todos os cadastros administrativos possuem o campo:

```php
active
```

Esse campo representa se o registro está disponível para utilização no sistema.

A exclusão física de registros deve ser evitada sempre que possível.

---

## Timestamps

Todas as tabelas devem possuir:

```php
$table->timestamps();
```

O método `timestamps()` deve ser declarado ao final da definição da tabela.

---

# Convenções de Desenvolvimento

## Controllers

As Controllers devem apenas orquestrar o fluxo da requisição.

Não devem conter regras complexas de negócio.

Sempre que uma regra envolver múltiplas entidades, deverá ser avaliada a criação de um Service.

---

## Models

As Models devem conter:

- relacionamentos;
- scopes;
- casts;
- pequenas consultas reutilizáveis.

Não devem concentrar regras de negócio complexas.

---

## Services

Services serão utilizados apenas quando houver necessidade de encapsular regras de negócio que envolvam mais de uma entidade.

Enquanto a lógica pertencer apenas a uma entidade, ela deverá permanecer na própria Model.

---

## Blade

A interface deverá reutilizar componentes Blade sempre que possível.

Exemplos:

- x-forms.input
- x-forms.select
- x-forms.checkbox
- x-cards.card

Evita duplicação de código e mantém consistência visual.

---

# Convenções de Interface

## Idioma

O projeto utiliza dois idiomas de forma padronizada.

### Código

Inglês.

Exemplos:

- Company
- Branch
- Equipment

### Interface

Português.

Exemplos:

- Empresas
- Filiais
- Equipamentos

Essa separação facilita a manutenção do código e melhora a experiência dos usuários.

---

## Rotas

As rotas devem utilizar nomes em português.

Exemplos:

```php
route('empresas.index')

route('filiais.index')

route('equipamentos.index')
```

As URLs também devem permanecer em português sempre que fizer sentido para o domínio da aplicação.

---

## Componentização

Sempre que um elemento visual puder ser reutilizado em dois ou mais módulos, deverá ser transformado em um componente Blade.

---

# Organização do Código

Todos os módulos deverão seguir exatamente a mesma estrutura.

```
Migration

↓

Model

↓

Policy

↓

StoreRequest

↓

UpdateRequest

↓

Controller

↓

Routes

↓

Views

↓

Homologação

↓

Documentação
```

Nenhum módulo será considerado concluído antes de passar por todas essas etapas.

---

# Princípios Arquiteturais

Durante o desenvolvimento do Preventivas, devem ser observados os seguintes princípios:

- responsabilidade única;
- baixo acoplamento;
- alta coesão;
- reutilização de componentes;
- simplicidade;
- legibilidade;
- padronização;
- documentação contínua.

Esses princípios deverão orientar toda a evolução do sistema.

## Aprendizado Contínuo

O desenvolvimento do sistema prioriza o entendimento da arquitetura antes da implementação de novas abstrações.

Novas camadas, como Services, Events, Jobs ou outras funcionalidades do framework, serão introduzidas apenas quando houver uma necessidade real identificada durante a evolução do projeto.

Essa abordagem garante uma curva de aprendizado consistente e evita a criação de abstrações desnecessárias.
