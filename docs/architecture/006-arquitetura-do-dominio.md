# Arquitetura do Domínio

## Objetivo

Este documento descreve o domínio de negócio do sistema Preventivas.

Seu objetivo é apresentar as entidades que compõem o sistema, seus relacionamentos, responsabilidades e os princípios arquiteturais adotados durante o desenvolvimento.

Este documento representa a visão conceitual oficial do projeto, sendo independente de linguagem de programação, banco de dados ou framework.

Toda implementação deve respeitar os conceitos definidos nesta arquitetura.

---

# Visão Geral

O Preventivas foi desenvolvido para controlar o ciclo operacional dos equipamentos pertencentes ao Grupo Empresarial.

O sistema não possui como objetivo substituir plataformas de inventário patrimonial, como o GLPI.

Seu foco é controlar os processos operacionais executados diariamente pela equipe de TI.

O domínio foi dividido em quatro grandes áreas:

- Estrutura Organizacional;
- Catálogos;
- Operação;
- Gestão.

Cada área possui responsabilidades próprias e independentes.

---

# Estrutura Organizacional

A estrutura organizacional representa onde os equipamentos pertencem dentro da empresa.

```
Company
    │
    ▼
Branch
```

## Company

Representa uma empresa pertencente ao grupo empresarial.

Responsabilidades:

- cadastro da empresa;
- organização das filiais;
- identificação do grupo empresarial.

Uma empresa pode possuir diversas filiais.

---

## Branch

Representa uma filial pertencente a uma empresa.

Responsabilidades:

- identificar uma unidade física;
- agrupar os equipamentos pertencentes à filial;
- servir como origem e destino das movimentações.

Toda operação realizada no sistema está obrigatoriamente vinculada a uma filial.

---

# Catálogos

Os catálogos representam informações reutilizáveis durante toda a operação do sistema.

```
Manufacturer
       │
       ▼
EquipmentModel
       ▲
       │
Category
```

---

## Category

Representa a categoria funcional do equipamento.

Exemplos:

- Impressora;
- Scanner;
- Monitor;
- Balança;
- Nobreak.

---

## Manufacturer

Representa o fabricante do equipamento.

Exemplos:

- Zebra;
- Honeywell;
- Bematech;
- Elgin;
- Epson.

---

## EquipmentModel

Representa o modelo comercial do equipamento.

Todo modelo pertence obrigatoriamente a:

- um fabricante;
- uma categoria.

Exemplo:

```
Fabricante:
Honeywell

Categoria:
Scanner

Modelo:
Voyager 1250g
```

Os modelos são reutilizados durante o cadastro dos equipamentos.

---

# Equipamentos

O equipamento representa um patrimônio operacional utilizado pela empresa.

```
Branch
      │
      ▼
Equipment
      │
      ▼
EquipmentModel
```

Cada equipamento possui:

- filial atual;
- modelo;
- nome operacional;
- patrimônio;
- número de série;
- observações;
- situação.

O Preventivas controla apenas os dados necessários para sua operação.

Informações patrimoniais completas permanecem sob responsabilidade do GLPI.

---

# Operação

Os módulos operacionais representam os processos executados diariamente pela equipe de TI.

Todos possuem o equipamento como entidade central, porém cada módulo possui responsabilidades independentes.

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

Cada módulo representa um domínio específico.

Nenhum módulo deve assumir responsabilidades pertencentes a outro domínio.

---

## Movimentações

Responsável exclusivamente pelo gerenciamento da localização operacional dos equipamentos.

Seu objetivo é controlar as transferências entre filiais, mantendo o histórico das alterações de localização.

Responsabilidades:

- movimentação individual;
- movimentação em lote;
- alteração da filial atual do equipamento;
- histórico das transferências.

Fluxo simplificado:

```
Filial Origem

↓

Movimentação

↓

Filial Destino

↓

Atualização da filial atual do equipamento
```

Não fazem parte deste módulo:

- envio para assistência técnica;
- retorno de manutenção;
- ordens de serviço;
- fornecedores;
- notas fiscais;
- garantias.

Essas responsabilidades pertencem ao módulo de Reparo Externo.

---

## Reparo Externo

Responsável pelo gerenciamento completo dos equipamentos enviados para assistência técnica.

Fluxo:

```
Equipamento

↓

Abertura do Reparo

↓

Envio

↓

Acompanhamento

↓

Retorno

↓

Conclusão
```

O módulo deverá controlar:

- fornecedor;
- ordem de serviço;
- defeito informado;
- nota fiscal;
- datas de envio e retorno;
- garantia;
- observações;
- anexos.

Este módulo é totalmente independente das movimentações entre filiais.

---

## Preventivas

Responsável pelo planejamento das preventivas.

Entre suas responsabilidades estão:

- cronogramas;
- periodicidade;
- parâmetros;
- atividades;
- equipamentos participantes.

Este módulo representa apenas o planejamento.

---

## Execução das Preventivas

Representa a realização efetiva das preventivas planejadas.

Cada execução registra:

- responsável;
- data;
- respostas;
- evidências;
- observações.

Esse módulo representa o histórico operacional das preventivas executadas.

---

## Auditorias

Responsável pela conferência patrimonial dos equipamentos.

Seu objetivo é validar:

- existência do equipamento;
- localização;
- estado físico;
- conformidade com o cadastro.

As auditorias representam um processo independente dos demais módulos.

---

# Gestão

A camada de gestão consolida todas as informações produzidas pelos módulos operacionais.

```
Movements

External Repairs

Preventives

Preventive Executions

Audits

↓

Dashboards

↓

Relatórios

↓

Indicadores
```

Essa camada possui caráter exclusivamente analítico.

Nenhum dado operacional é produzido diretamente nela.

---

# Relações Entre as Entidades

```
Company
    │
    ▼
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

Relacionamentos dos módulos operacionais:

```
Equipment
    │
    ├──────────────► Movements
    │
    ├──────────────► External Repairs
    │
    ├──────────────► Preventives
    │
    └──────────────► Audits
```

O equipamento representa a entidade central do domínio operacional.

Cada módulo registra apenas informações pertencentes ao seu próprio processo de negócio.

---

# Princípios do Domínio

## Separação de Responsabilidades

Cada entidade representa apenas um conceito do domínio.

Nenhuma entidade deve assumir responsabilidades pertencentes a outro módulo.

---

## Coesão de Domínio

Cada módulo deve resolver apenas um processo de negócio.

Sempre que um novo fluxo possuir regras próprias, ele deverá ser implementado em um módulo específico.

---

## Baixo Acoplamento

As entidades devem depender apenas das informações necessárias para sua operação.

Mudanças em um módulo não devem impactar diretamente outro domínio.

---

## Fonte Única de Informação

O Preventivas não deverá duplicar informações pertencentes ao GLPI.

Sempre que possível, essas informações deverão ser obtidas através de integração.

---

## Evolução Incremental

Novas entidades somente deverão ser adicionadas quando representarem um novo conceito de negócio.

Evita crescimento desnecessário do domínio e mantém a arquitetura simples.

---

# Evolução da Arquitetura

Este documento deverá ser atualizado sempre que uma nova entidade ou um novo domínio operacional fizer parte do sistema.

Toda alteração arquitetural relevante deverá possuir uma ADR correspondente na pasta:

```
docs/decisions/
```

A implementação somente deverá iniciar após o domínio estar devidamente definido e documentado.

---

# Considerações Finais

O domínio do Preventivas foi modelado para refletir o fluxo operacional da equipe de TI.

O sistema não pretende substituir soluções de inventário patrimonial, mas fornecer uma plataforma especializada para:

- movimentações entre filiais;
- manutenção externa;
- preventivas;
- auditorias;
- indicadores operacionais.

A separação clara das responsabilidades entre os módulos garante uma arquitetura coesa, escalável, de fácil manutenção e preparada para futuras integrações com o GLPI e outros sistemas corporativos.
