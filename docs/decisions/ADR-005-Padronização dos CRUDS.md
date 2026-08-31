# ADR-005 - Padronização dos CRUDs

## Status

Aceito

---

## Data

2026-08-07

---

## Contexto

Durante os primeiros módulos do projeto foi necessário definir uma estrutura única para desenvolvimento dos CRUDs.

Sem uma padronização, cada módulo poderia ser implementado de maneira diferente, aumentando a complexidade de manutenção.

---

## Decisão

Todo CRUD do sistema seguirá obrigatoriamente a seguinte estrutura:

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

Essa sequência passa a ser o padrão oficial do projeto.

---

## Justificativa

A padronização facilita o desenvolvimento, reduz erros e torna todos os módulos previsíveis.

Também melhora a curva de aprendizado para novos desenvolvedores.

---

## Consequências

### Benefícios

- organização consistente;
- facilidade de manutenção;
- menor retrabalho;
- maior reutilização de código;
- melhor legibilidade.

### Limitações

Todos os módulos devem respeitar essa estrutura, mesmo quando forem pequenos.

---

## Alternativas Consideradas

### Alternativa A

Cada módulo definir sua própria estrutura.

**Motivo da rejeição**

Aumentaria a inconsistência do projeto.

---

### Alternativa B

Padronizar toda a implementação.

**Decisão adotada.**

---

## Referências

- docs/architecture/004-padrao-crud.md
- docs/architecture/005-organizacao-laravel.md3
