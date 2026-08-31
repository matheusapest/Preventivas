# Padrão de Desenvolvimento dos CRUDs

## Checklist de Conclusão

- [ ] Migration criada
- [ ] Model criada
- [ ] Policy criada
- [ ] StoreRequest criada
- [ ] UpdateRequest criado
- [ ] Controller implementada
- [ ] Rotas configuradas
- [ ] Views implementadas
- [ ] Homologação realizada
- [ ] Documentação concluída

## Objetivo

Este documento define o padrão oficial utilizado para o desenvolvimento de todos os módulos administrativos do sistema Preventivas.

A padronização garante consistência entre os módulos, reduz retrabalho, facilita a manutenção e acelera o desenvolvimento de novas funcionalidades.

Todos os CRUDs da aplicação deverão seguir esta arquitetura.

---

# Fluxo de Desenvolvimento

Todo novo módulo deverá ser desenvolvido seguindo obrigatoriamente a sequência abaixo.

```
Migration
    ↓
Model
    ↓
Policy
    ↓
Store FormRequest
    ↓
Update FormRequest
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

Nenhuma etapa deverá ser ignorada.

---

# 1. Migration

A Migration é responsável pela criação da estrutura da tabela no banco de dados.

Deve conter:

- Estrutura da tabela;
- Chaves primárias;
- Chaves estrangeiras;
- Índices;
- Constraints;
- Comentários;
- Campo active quando aplicável;
- timestamps().

Exemplo:

```php
$table->id();

$table->string('name');

$table->boolean('active')
    ->default(true);

$table->timestamps();
```

---

# 2. Model

A Model representa a entidade da aplicação.

É responsável por:

- Relacionamentos;
- Scopes;
- Casts;
- Fillable;
- Pequenas consultas reutilizáveis.

A Model não deve conter regras de negócio complexas.

---

# 3. Policy

Cada entidade deve possuir sua própria Policy.

Responsabilidades:

- Visualizar;
- Criar;
- Editar;
- Ativar/Inativar.

A Policy nunca deverá realizar validações de dados.

Sua responsabilidade é exclusivamente autorização.

---

# 4. Form Requests

Todo CRUD deverá possuir dois FormRequests independentes.

```
StoreEntityRequest

UpdateEntityRequest
```

Responsabilidades:

- Validação;
- Mensagens personalizadas;
- Atributos amigáveis.

Nunca realizar validações diretamente na Controller.

---

# 5. Controller

A Controller deve apenas orquestrar o fluxo da requisição.

Responsabilidades:

- Autorizar operação;
- Receber o FormRequest;
- Executar operações simples;
- Retornar View ou Redirect.

Não deve conter regras de negócio complexas.

Sempre que uma lógica envolver múltiplas entidades, deverá ser avaliada a criação de um Service.

---

# 6. Rotas

As rotas deverão seguir o padrão REST.

Exemplo:

```php
Route::resource('categorias', CategoryController::class);

Route::patch(
    'categorias/{category}/toggle-active',
    ...
);
```

Sempre que necessário, utilizar ações específicas para funcionalidades que não fazem parte do CRUD tradicional.

---

# 7. Views

Todos os módulos devem reutilizar componentes Blade.

Estrutura padrão:

```
index.blade.php

create.blade.php

edit.blade.php

_form.blade.php
```

Sempre reutilizar:

- Cards;
- Inputs;
- Selects;
- Checkboxes;
- Botões.

Evitar duplicação de HTML.

---

# 8. Homologação

Após finalizar a implementação, o módulo deverá ser validado manualmente.

Checklist:

- Cadastro;
- Edição;
- Ativação;
- Inativação;
- Validações;
- Policies;
- Mensagens;
- Navegação.

Somente após a homologação o módulo poderá ser considerado concluído.

---

# 9. Documentação

Todo módulo homologado deverá possuir documentação.

A documentação deverá conter:

- Objetivo;
- Regras de negócio;
- Fluxo operacional;
- Dependências;
- Observações importantes.

Nenhum módulo será considerado finalizado sem documentação.

---

# Estrutura Esperada

Ao final da implementação, o módulo deverá possuir a seguinte estrutura.

```
app/
├── Http/
│   ├── Controllers/
│   └── Requests/
│
├── Models/
│
├── Policies/
│
resources/
└── views/
    └── modulo/
        ├── index.blade.php
        ├── create.blade.php
        ├── edit.blade.php
        └── _form.blade.php
```

---

# Benefícios da Padronização

A adoção deste padrão proporciona:

- Código consistente;
- Facilidade de manutenção;
- Menor curva de aprendizado;
- Redução de retrabalho;
- Maior reutilização de componentes;
- Melhor organização do projeto;
- Facilidade para identificar problemas.

---

# Considerações

Este padrão deverá ser utilizado em todos os módulos administrativos do Preventivas.

Novas abstrações somente deverão ser adicionadas quando houver uma necessidade real identificada durante a evolução do projeto.

O objetivo é manter a arquitetura simples, organizada e de fácil manutenção, priorizando sempre a clareza do código e a separação adequada das responsabilidades.
