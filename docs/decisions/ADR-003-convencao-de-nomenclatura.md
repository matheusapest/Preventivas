# ADR-003 - Convenção de Nomenclatura

## Status

Aceito

---

## Data

2026-08-07

---

## Contexto

Durante o desenvolvimento do projeto foi necessário definir um padrão de nomenclatura para banco de dados, código-fonte e interface.

Inicialmente parte das tabelas utilizava nomes em português, enquanto outras já seguiam o padrão do Laravel em inglês.

Era necessário padronizar toda a aplicação.

---

## Decisão

Foi definido o seguinte padrão.

### Código

Utilizar inglês.

Exemplos:

- Company
- Branch
- Equipment
- Manufacturer

### Banco de Dados

Utilizar inglês.

Exemplos:

- companies
- branches
- equipments
- manufacturers

### Interface

Utilizar português.

Exemplos:

- Empresas
- Filiais
- Equipamentos

### Rotas

Utilizar português.

Exemplos:

- empresas
- filiais
- equipamentos

---

## Justificativa

Essa decisão mantém o código alinhado ao padrão do Laravel, sem prejudicar a experiência do usuário.

Também facilita futuras integrações e reduz ambiguidades durante o desenvolvimento.

---

## Consequências

### Benefícios

- maior padronização;
- melhor organização do projeto;
- código alinhado às convenções do framework;
- interface mais intuitiva.

### Limitações

É necessário manter atenção durante a implementação para não misturar idiomas.

---

## Alternativas Consideradas

### Alternativa A

Todo o sistema em português.

**Motivo da rejeição**

Ficaria distante das convenções do Laravel.

---

### Alternativa B

Todo o sistema em inglês.

**Motivo da rejeição**

Reduziria a legibilidade da interface para os usuários.

---

### Alternativa C

Código em inglês e interface em português.

**Decisão adotada.**

---

## Referências

- docs/architecture/003-convencoes-do-projeto.md
