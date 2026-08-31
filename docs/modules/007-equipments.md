# Módulo - Equipamentos

## Objetivo

O módulo de Equipamentos é responsável pelo cadastro dos equipamentos operacionais utilizados pelas filiais da empresa.

Cada equipamento representa um ativo operacional pertencente a uma filial e vinculado a um modelo previamente cadastrado.

Este módulo fornece a entidade central utilizada pelos demais módulos do sistema.

O Preventivas não possui como objetivo substituir sistemas de inventário patrimonial, como o GLPI.

Seu objetivo é controlar apenas as informações necessárias para a operação da equipe de TI.

---

# Responsabilidades

O módulo é responsável por:

- cadastro de equipamentos;
- associação do equipamento à filial;
- associação do equipamento ao modelo;
- controle do patrimônio;
- controle do número de série;
- controle da situação do equipamento;
- disponibilizar a entidade principal utilizada pelos módulos operacionais.

Não é responsabilidade deste módulo:

- controle da localização física detalhada;
- controle do usuário responsável;
- controle do centro de custo;
- inventário patrimonial;
- histórico de movimentações;
- controle de manutenção externa.

Essas informações permanecem sob responsabilidade do GLPI ou de módulos específicos do Preventivas.

---

# Papel no Domínio

O equipamento representa a entidade central do domínio operacional.

Todos os módulos operacionais utilizam o equipamento como referência.

```
                        Equipment
                             │
        ┌────────────────────┼────────────────────┐
        │                    │                    │
        ▼                    ▼                    ▼
   Movements        External Repairs       Preventives
                                                  │
                                                  ▼
                                     Preventive Executions
                                                  │
                                                  ▼
                                               Audits
```

Cada módulo registra apenas informações pertencentes ao seu próprio processo de negócio.

---

# Estrutura

Relacionamentos:

```
Branch
      │
      ▼
Equipment
      │
      ▼
EquipmentModel
   ├──────────────┐
   ▼              ▼
Manufacturer   Category
```

O equipamento pertence obrigatoriamente a:

- uma filial;
- um modelo.

As informações de fabricante e categoria são obtidas através do modelo do equipamento.

---

# Campos

| Campo | Obrigatório | Descrição |
|--------|-------------|-----------|
| Filial | Sim | Filial onde o equipamento pertence atualmente |
| Modelo | Sim | Modelo comercial do equipamento |
| Nome | Sim | Nome operacional utilizado pela equipe |
| Patrimônio | Não | Número patrimonial do equipamento |
| Número de Série | Não | Número de série do fabricante |
| Observações | Não | Informações adicionais |
| Ativo | Sim | Indica se o equipamento está ativo |

---

# Regras de Negócio

## Nome

Representa um nome operacional utilizado pela equipe de TI.

Exemplos:

- Impressora Caixa 01;
- Scanner Frente de Caixa;
- Balança Hortifruti;
- Monitor PDV 05.

O nome facilita a identificação do equipamento durante a operação.

Ele não é utilizado como identificador único.

---

## Patrimônio

Quando informado, o patrimônio deve ser único.

Essa regra evita duplicidade de equipamentos e facilita futuras integrações com o GLPI.

O número patrimonial não deve ser reutilizado após baixa patrimonial.

---

## Número de Série

Quando informado, deve ser único.

O sistema impede duplicidade para preservar a rastreabilidade do equipamento.

---

## Modelo

Todo equipamento deve possuir um modelo.

Fabricante e categoria são herdados do modelo selecionado.

Não é permitido selecionar fabricante ou categoria diretamente no cadastro do equipamento.

---

## Filial

Todo equipamento pertence obrigatoriamente a uma filial.

A filial representa a localização operacional atual do equipamento.

Sempre que ocorrer uma transferência entre filiais, o módulo de Movimentações atualizará essa informação.

---

# Fluxo de Cadastro

```
Company

↓

Branch

↓

Equipment Model

↓

Equipment

↓

Utilização pelos módulos operacionais
```

Após o cadastro, o equipamento poderá ser utilizado por qualquer módulo do sistema.

---

# Funcionalidades Implementadas

- cadastro;
- edição;
- ativação;
- inativação;
- listagem;
- paginação;
- validação de patrimônio único;
- validação de número de série único;
- relacionamento com filiais;
- relacionamento com modelos;
- Policies;
- Form Requests;
- Eloquent ORM.

---

# Estrutura Implementada

## Migration

- equipments

## Model

- Equipment

## Policy

- EquipmentPolicy

## Controller

- EquipmentController

## Requests

- StoreEquipmentRequest
- UpdateEquipmentRequest

## Views

- index
- create
- edit
- _form

---

# Evoluções Futuras

O módulo foi preparado para suportar:

- integração com GLPI;
- importação de inventário;
- histórico de movimentações;
- manutenção externa;
- preventivas;
- auditorias;
- indicadores operacionais.

Também foi prevista a utilização do campo de etiqueta interna, que permanece disponível para futuras necessidades sem impactar a estrutura atual do sistema.

---

# Observações Arquiteturais

Este módulo segue os padrões arquiteturais definidos para todo o projeto.

- Banco de dados em inglês;
- Models em inglês;
- Controllers em inglês;
- Policies em inglês;
- Form Requests em inglês;
- Views em inglês;
- Rotas em português;
- Interface em português.

O módulo foi projetado para representar exclusivamente o equipamento operacional da empresa.

As informações patrimoniais completas permanecem centralizadas no GLPI, evitando duplicação de responsabilidades e mantendo cada sistema especializado em seu respectivo domínio.
