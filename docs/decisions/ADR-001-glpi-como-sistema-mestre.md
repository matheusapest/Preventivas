# ADR-001 - GLPI como Sistema Mestre

## Status

Aceito

---

## Data

2026-08-07

---

## Contexto

Durante a modelagem do Preventivas surgiu uma decisão importante sobre a responsabilidade de cada sistema.

Inicialmente foi considerada a possibilidade de replicar no Preventivas diversas informações já existentes no GLPI, como patrimônio, localização física, setor, usuário responsável e demais informações cadastrais dos equipamentos.

Essa abordagem transformaria o Preventivas em um segundo sistema de inventário, gerando duplicidade de informações e aumentando significativamente a complexidade da aplicação.

Era necessário definir claramente qual seria o domínio de responsabilidade de cada sistema.

---

## Decisão

Foi definido que o **GLPI será o sistema mestre do inventário patrimonial**, enquanto o **Preventivas será responsável exclusivamente pelo fluxo operacional dos equipamentos**.

O Preventivas armazenará apenas as informações necessárias para executar seus processos de negócio, como preventivas, movimentações, manutenção externa, auditorias e histórico operacional.

Todas as informações patrimoniais continuarão sendo responsabilidade do GLPI.

---

## Justificativa

Essa decisão foi tomada para manter o escopo do projeto simples e bem definido.

Os principais benefícios são:

- evitar duplicação de dados;
- reduzir inconsistências entre sistemas;
- facilitar futuras integrações com o GLPI;
- manter cada sistema responsável apenas pelo seu domínio de negócio;
- diminuir a complexidade da aplicação;
- facilitar a manutenção e evolução do projeto.

Essa separação também segue o princípio da responsabilidade única, onde cada sistema possui um propósito claramente definido.

---

## Consequências

A adoção dessa decisão trouxe os seguintes impactos.

### Benefícios

- arquitetura mais simples;
- menor quantidade de dados armazenados;
- redução de redundância;
- integração mais simples com o GLPI;
- menor esforço de manutenção.

### Limitações

O Preventivas não armazenará informações como:

- localização física detalhada;
- setor;
- centro de custo;
- usuário responsável;
- inventário patrimonial completo.

Esses dados deverão permanecer no GLPI.

---

## Alternativas Consideradas

### Alternativa A

Replicar no Preventivas todas as informações existentes no GLPI.

**Motivo da rejeição**

Geraria duplicidade de dados, maior complexidade e risco de inconsistências entre os sistemas.

---

### Alternativa B

Criar um sistema simples, focado apenas no fluxo operacional dos equipamentos.

**Decisão adotada.**

Essa abordagem mantém o escopo do projeto bem definido e permite que a aplicação evolua de forma incremental.

---

### Alternativa C

Desenvolver apenas funcionalidades essenciais e adicionar novas capacidades conforme surgirem necessidades reais.

Essa alternativa foi incorporada à decisão adotada, tornando-se um dos princípios de evolução do projeto.

---

## Referências

- docs/architecture/001-visao-geral.md
- docs/architecture/002-glpi-vs-preventivas.md
- docs/architecture/006-arquitetura-do-dominio.md
