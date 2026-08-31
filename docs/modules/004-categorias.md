# Módulo de Categorias

## Objetivo

O módulo de Categorias é responsável por classificar os equipamentos utilizados no sistema.

As categorias organizam os modelos de equipamentos e permitem que diferentes funcionalidades sejam aplicadas conforme o tipo de equipamento.

Esse módulo representa um catálogo reutilizável por todo o sistema.

---

# Responsabilidades

Este módulo é responsável por:

- cadastrar categorias;
- editar categorias;
- ativar e inativar categorias;
- disponibilizar categorias para o cadastro de modelos.

Não controla equipamentos diretamente.

---

# Fluxo Operacional

O fluxo de utilização ocorre da seguinte forma.

```
Cadastrar Categoria

↓

Cadastrar Modelo

↓

Cadastrar Equipamento

↓

Utilizar Equipamento nas Operações
```

A categoria representa apenas a classificação do equipamento.

---

# Estrutura de Dados

Entidade principal:

```
Category
```

Principais atributos:

- Nome;
- Situação (Ativa/Inativa).

Relacionamentos:

```
Category
      │
      ▼
EquipmentModel
```

Uma categoria pode possuir diversos modelos.

---

# Exemplos de Categorias

Exemplos de categorias utilizadas no Preventivas.

- Impressora;
- Scanner;
- Monitor;
- Computador;
- Notebook;
- Servidor;
- Balança;
- Cancela;
- Nobreak;
- Pin Pad.

---

# Regras de Negócio

As seguintes regras são aplicadas ao módulo.

- O nome da categoria deve ser único.
- Apenas categorias ativas podem ser utilizadas.
- Categorias não devem ser removidas fisicamente.
- O controle é realizado através do campo `active`.

---

# Dependências

Este módulo não depende de nenhum outro cadastro.

---

# Integrações

O módulo é utilizado por:

- Modelos de Equipamentos;
- Equipamentos;
- Preventivas;
- Auditorias;
- Relatórios.

Toda classificação de equipamentos parte da categoria.

---

# Decisões Arquiteturais

As categorias representam conceitos reutilizáveis do domínio.

Elas não identificam um equipamento específico.

Exemplo:

```
Categoria

↓

Impressora
```

Modelos diferentes poderão pertencer à mesma categoria.

Exemplo:

```
Categoria

↓

Impressora

↓

Zebra ZD220

↓

Elgin L42

↓

Bematech MP-4200
```

Essa separação reduz redundância e facilita a manutenção do catálogo de equipamentos.

---

# Evoluções Futuras

Está prevista a possibilidade de restringir categorias por filial.

Exemplo:

Uma categoria como "Cancela de Estacionamento" poderá estar disponível apenas para filiais que realmente possuam esse tipo de equipamento.

Essa funcionalidade permitirá maior aderência à realidade operacional da empresa.

---

# Considerações

As categorias representam a primeira camada de classificação dos equipamentos.

Sua principal função é organizar os modelos e permitir que futuras regras de negócio sejam aplicadas de forma consistente para grupos de equipamentos semelhantes.
