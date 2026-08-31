# Módulo de Fabricantes

## Objetivo

O módulo de Fabricantes é responsável pelo cadastro das empresas fabricantes dos equipamentos utilizados pelo sistema.

Seu objetivo é padronizar as informações dos fabricantes, evitando duplicidade de cadastros e permitindo que diversos modelos sejam associados a um mesmo fabricante.

Este módulo representa um catálogo reutilizável por toda a aplicação.

---

# Responsabilidades

Este módulo é responsável por:

- cadastrar fabricantes;
- editar fabricantes;
- ativar e inativar fabricantes;
- disponibilizar fabricantes para o cadastro de modelos.

Não possui responsabilidades relacionadas à operação dos equipamentos.

---

# Fluxo Operacional

O fluxo ocorre da seguinte forma.

```
Cadastrar Fabricante

↓

Cadastrar Modelo

↓

Cadastrar Equipamento
```

O fabricante representa apenas a empresa responsável pela fabricação do equipamento.

---

# Estrutura de Dados

Entidade principal:

```
Manufacturer
```

Principais atributos:

- Nome;
- Situação (Ativo/Inativo).

Relacionamentos:

```
Manufacturer
        │
        ▼
EquipmentModel
```

Um fabricante pode possuir diversos modelos cadastrados.

---

# Exemplos de Fabricantes

Exemplos utilizados no sistema.

- Zebra;
- Honeywell;
- Elgin;
- Bematech;
- Epson;
- HP;
- Dell;
- Lenovo.

---

# Regras de Negócio

As seguintes regras são aplicadas ao módulo.

- O nome do fabricante deve ser único.
- Apenas fabricantes ativos poderão ser utilizados.
- Fabricantes não devem ser removidos fisicamente.
- O controle é realizado através do campo `active`.

---

# Dependências

Este módulo não depende de nenhum outro cadastro.

---

# Integrações

É utilizado por:

- Modelos de Equipamentos;
- Equipamentos;
- Relatórios.

---

# Evoluções Futuras

Poderão ser adicionadas informações como:

- Site do fabricante;
- País de origem;
- Suporte técnico;
- Contatos.

Essas informações somente deverão ser adicionadas caso tragam valor ao domínio do Preventivas.

---

# Considerações

O fabricante representa apenas uma classificação administrativa dos modelos de equipamentos.

Ele não possui responsabilidade sobre o fluxo operacional do sistema.
