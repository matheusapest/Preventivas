# ADR-004 - Desenvolvimento Incremental

## Status

Aceito

---

## Data

2026-08-07

---

## Contexto

No início do projeto existia a possibilidade de desenvolver diversos módulos simultaneamente.

Essa abordagem aumentaria a quantidade de código em desenvolvimento, dificultando testes, homologação e entendimento da arquitetura.

---

## Decisão

Foi definido que o sistema será desenvolvido de forma incremental.

Cada módulo somente será considerado concluído após passar pelas seguintes etapas:

- implementação;
- homologação;
- documentação.

Somente após a conclusão completa de um módulo será iniciado o desenvolvimento do próximo.

---

## Justificativa

Essa abordagem reduz retrabalho e garante maior qualidade durante o desenvolvimento.

Também favorece o aprendizado progressivo da arquitetura do Laravel.

---

## Consequências

### Benefícios

- menor acoplamento;
- maior estabilidade;
- facilidade para localizar erros;
- evolução consistente da arquitetura;
- documentação sempre atualizada.

### Limitações

O desenvolvimento inicial pode parecer mais lento, porém reduz significativamente problemas futuros.

---

## Alternativas Consideradas

### Alternativa A

Desenvolver vários módulos simultaneamente.

**Motivo da rejeição**

Maior risco de retrabalho e inconsistências.

---

### Alternativa B

Desenvolvimento incremental.

**Decisão adotada.**

---

## Referências

- docs/architecture/004-padrao-crud.md
