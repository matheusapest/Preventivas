# ADR-006 - Responsabilidade do Módulo de Movimentações

## Status

Aceito

---

## Data

2026-08-07

---

## Contexto

Durante a modelagem do sistema Preventivas surgiu a necessidade de definir o escopo do módulo de Movimentações.

Inicialmente foi considerado que este módulo seria responsável por registrar qualquer deslocamento realizado com um equipamento, incluindo transferências entre filiais, envios para manutenção externa, retornos de assistência técnica e demais alterações relacionadas ao ciclo de vida do ativo.

Após a evolução da arquitetura do sistema, percebeu-se que essa abordagem concentraria responsabilidades distintas em um único módulo, tornando sua manutenção mais complexa e aumentando o acoplamento entre funcionalidades que pertencem a domínios diferentes.

---

## Decisão

O módulo de Movimentações será responsável exclusivamente pelas movimentações logísticas entre filiais.

Suas responsabilidades serão:

- movimentação individual de equipamentos;
- movimentação em lote;
- alteração da filial atual do equipamento;
- manutenção do histórico de transferências entre filiais.

O módulo não será responsável por processos relacionados à manutenção externa ou reparos.

Esses processos serão implementados em um módulo específico de Reparo Externo.

---

## Justificativa

A separação de responsabilidades torna cada módulo mais simples, coeso e alinhado aos princípios de arquitetura do sistema.

As movimentações entre filiais representam um processo operacional diferente do fluxo de manutenção externa.

Enquanto uma transferência entre filiais exige apenas o registro da origem, destino e atualização da filial atual do equipamento, um reparo externo envolve informações adicionais, como:

- fornecedor responsável;
- ordem de serviço;
- defeito informado;
- nota fiscal;
- datas de envio e retorno;
- laudo técnico;
- garantia;
- anexos.

Agrupar esses processos em um único módulo aumentaria significativamente sua complexidade.

---

## Consequências

Com essa decisão:

- o módulo de Movimentações permanecerá simples e focado em logística interna;
- o módulo de Reparo Externo possuirá seu próprio fluxo de trabalho;
- o histórico de transferências entre filiais ficará centralizado;
- futuras integrações e evoluções poderão ser implementadas de forma independente.

Além disso, a filial atual do equipamento continuará sendo armazenada diretamente no cadastro do equipamento, sendo atualizada sempre que uma movimentação for concluída.

---

## Alternativas Consideradas

### Alternativa A

Centralizar todas as movimentações do equipamento em um único módulo.

**Não adotada**, pois aumentaria o acoplamento entre processos distintos e dificultaria a evolução do sistema.

---

### Alternativa B

Criar um módulo exclusivo para movimentações entre filiais e outro para reparos externos.

**Adotada**, pois mantém cada módulo responsável por um único domínio de negócio.

---

### Alternativa C

Alterar diretamente a filial do equipamento sem registrar histórico.

**Não adotada**, pois impediria rastrear as transferências realizadas ao longo do tempo.

---

## Referências

- docs/architecture/005-domain-architecture.md
- docs/modules/005-equipments.md
