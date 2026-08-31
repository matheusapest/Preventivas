# Organização da Arquitetura Laravel

## Objetivo

Este documento descreve como a arquitetura do Laravel foi organizada no projeto Preventivas.

O objetivo é padronizar a distribuição das responsabilidades entre as camadas da aplicação, garantindo organização, baixo acoplamento e facilidade de manutenção.

Cada diretório possui uma responsabilidade bem definida e deve ser utilizado apenas para sua finalidade.

---

# Visão Geral

O Preventivas adota a arquitetura MVC (Model-View-Controller) proposta pelo Laravel, complementada por camadas específicas para autorização, validação e documentação.

A estrutura da aplicação segue a organização abaixo.

```

app/
├── Http/
│ ├── Controllers/
│ └── Requests/
│
├── Models/
│
├── Policies/
│
├── Services/
│
└── Providers/

resources/
└── views/

routes/

database/
└── migrations/

docs/


#Considerações

Toda nova funcionalidade deverá respeitar esta organização.

Antes de adicionar código a uma determinada camada, o desenvolvedor deve responder à seguinte pergunta:

> Esta responsabilidade realmente pertence a esta camada?

Caso a resposta seja negativa, a implementação deverá ser reavaliada antes de prosseguir.
