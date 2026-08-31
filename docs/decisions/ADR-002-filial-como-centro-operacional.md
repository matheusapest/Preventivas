# ADR-002 - Filial como Centro Operacional

## Status

Aceito

---

## Data

2026-08-07

---

## Contexto

Durante a modelagem do domínio foi necessário definir qual seria a principal unidade operacional do sistema.

Inicialmente foi considerada a possibilidade de tratar os equipamentos como entidades independentes da estrutura organizacional, permitindo sua existência sem vínculo com uma filial.

Entretanto, essa abordagem dificultaria o controle de movimentações, preventivas, auditorias e manutenções externas, além de não refletir a realidade operacional da empresa.

---

## Decisão

Foi definido que toda operação do Preventivas ocorrerá obrigatoriamente no contexto de uma filial.

Todo equipamento deverá pertencer a uma filial, que será considerada sua origem operacional.

A filial passa a representar o centro de todas as operações executadas pelo sistema.

---

## Justificativa

Essa decisão aproxima o sistema da realidade operacional da empresa.

Entre os benefícios estão:

- simplificação dos fluxos operacionais;
- rastreabilidade dos equipamentos;
- facilidade na execução das preventivas;
- controle consistente das movimentações;
- facilidade para geração de indicadores por unidade.

---

## Consequências

### Benefícios

- todas as operações possuem uma origem definida;
- simplificação das regras de negócio;
- melhor organização dos equipamentos.

### Limitações

Não será permitido cadastrar equipamentos sem vínculo com uma filial.

Equipamentos enviados para manutenção continuarão vinculados à filial de origem durante todo o processo.

---

## Alternativas Consideradas

### Alternativa A

Permitir equipamentos independentes.

**Motivo da rejeição**

Aumentaria a complexidade do domínio e dificultaria o controle operacional.

---

### Alternativa B

Utilizar a filial como unidade operacional.

**Decisão adotada.**

---

## Referências

- docs/architecture/006-arquitetura-do-dominio.md
- docs/modules/002-filiais.md
