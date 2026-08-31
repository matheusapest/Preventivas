# Módulo de Modelos de Equipamentos

## Objetivo

O módulo de Modelos de Equipamentos é responsável pelo cadastro dos modelos utilizados pelos equipamentos do sistema.

Ele conecta fabricantes e categorias, formando o catálogo técnico dos equipamentos.

Cada equipamento cadastrado deverá obrigatoriamente utilizar um modelo previamente cadastrado.

---

# Responsabilidades

Este módulo é responsável por:

- cadastrar modelos;
- editar modelos;
- ativar e inativar modelos;
- associar um fabricante;
- associar uma categoria.

---

# Fluxo Operacional

O fluxo ocorre da seguinte forma.

```
Cadastrar Categoria

↓

Cadastrar Fabricante

↓

Cadastrar Modelo

↓

Cadastrar Equipamento
```

---

# Estrutura de Dados

Entidade principal:

```
EquipmentModel
```

Principais atributos:

- Fabricante;
- Categoria;
- Nome;
- Situação (Ativo/Inativo).

Relacionamentos:

```
Manufacturer
        │
        ▼
EquipmentModel
        ▲
        │
Category
```

Cada modelo pertence obrigatoriamente a:

- um fabricante;
- uma categoria.

---

# Regras de Negócio

As seguintes regras são aplicadas ao módulo.

- Todo modelo deve possuir um fabricante.
- Todo modelo deve possuir uma categoria.
- Apenas fabricantes ativos poderão ser utilizados.
- Apenas categorias ativas poderão ser utilizadas.
- Modelos não devem ser removidos fisicamente.
- O controle é realizado através do campo `active`.

---

# Dependências

Este módulo depende dos seguintes cadastros.

- Categorias;
- Fabricantes.

---

# Integrações

É utilizado por:

- Equipamentos;
- Preventivas;
- Auditorias;
- Relatórios.

Todo equipamento cadastrado utiliza obrigatoriamente um modelo.

---

# Decisões Arquiteturais

O sistema diferencia claramente os conceitos de:

```
Categoria

↓

Fabricante

↓

Modelo

↓

Equipamento
```

Exemplo:

```
Categoria

Impressora

↓

Fabricante

Zebra

↓

Modelo

ZD220

↓

Equipamento

Patrimônio 10235
```

Essa separação evita duplicidade de informações e permite reutilizar o mesmo modelo para diversos equipamentos.

---

# Evoluções Futuras

O módulo poderá receber informações complementares como:

- Código do fabricante;
- Descrição técnica;
- Compatibilidade;
- Manual do equipamento;
- Imagem ilustrativa.

---

# Considerações

O modelo representa a identidade técnica do equipamento.

Ele não identifica um equipamento específico, mas sim um padrão reutilizado por diversos equipamentos cadastrados no sistema.
