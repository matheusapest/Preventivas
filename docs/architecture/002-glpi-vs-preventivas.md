# GLPI vs Preventivas

## Objetivo

Definir claramente as responsabilidades entre o GLPI e o sistema Preventivas, evitando duplicação de informações e garantindo que cada sistema seja responsável apenas pelo seu domínio de negócio.

---

# Visão Geral

O Preventivas foi concebido para atuar de forma complementar ao GLPI.

O GLPI permanece como sistema mestre para o gerenciamento do inventário patrimonial da empresa, enquanto o Preventivas concentra os fluxos operacionais relacionados às preventivas, movimentações e manutenção dos equipamentos.

Essa separação reduz redundância de informações, facilita integrações futuras e diminui a complexidade da manutenção dos dados.

---

# Sistema Mestre (GLPI)

O GLPI é responsável pelo cadastro e gerenciamento do inventário patrimonial.

Entre suas responsabilidades estão:

- Cadastro do patrimônio;
- Localização física do equipamento;
- Setor responsável;
- Centro de custo;
- Usuário responsável;
- Dados técnicos do equipamento;
- Informações detalhadas de hardware;
- Inventário completo dos ativos.

Todas essas informações possuem como fonte oficial o GLPI.

O Preventivas nunca deverá duplicar esses cadastros.

---

# Sistema Preventivas

O Preventivas é responsável pelo gerenciamento operacional dos equipamentos.

Entre suas responsabilidades estão:

## Estrutura Organizacional

- Empresas;
- Filiais;
- Números de Filiais.

## Catálogos

- Categorias;
- Fabricantes;
- Modelos.

## Operação

- Equipamentos;
- Movimentações;
- Manutenção Externa;
- Preventivas;
- Execuções;
- Auditorias.

## Gestão

- Cronogramas;
- Dashboard;
- Relatórios Operacionais.

---

# Integração entre os Sistemas

Os sistemas possuem responsabilidades distintas.

O GLPI fornece as informações cadastrais dos equipamentos.

O Preventivas utiliza essas informações para executar os processos operacionais.

Sempre que possível, o Preventivas deverá consumir dados do GLPI ao invés de manter cópias locais.

---

# Exemplo Prático

## Cadastro

O equipamento é cadastrado no GLPI contendo:

- Patrimônio;
- Localização;
- Usuário responsável;
- Dados técnicos;
- Informações patrimoniais.

---

## Operação

Quando o equipamento precisa ser enviado para manutenção, o processo passa a ser responsabilidade do Preventivas.

Exemplo:

```
Filial Erechim

↓

Scanner Honeywell

↓

Enviar para manutenção
```

A movimentação registra:

```
Origem:
Filial Erechim

Destino:
Assistência Técnica

Status:
Em manutenção
```

Após o retorno:

```
Origem:
Assistência Técnica

Destino:
Filial Erechim
```

Ou ainda:

```
Origem:
Assistência Técnica

Destino:
Filial Passo Fundo
```

Observe que, nesse fluxo, a filial faz parte do processo operacional.

Não representa apenas uma informação cadastral.

---

# Justificativa Arquitetural

O objetivo do Preventivas não é substituir o GLPI.

Duplicar informações patrimoniais geraria problemas como:

- inconsistência de dados;
- duplicidade de cadastros;
- aumento da manutenção;
- divergência entre sistemas.

Ao definir o GLPI como sistema mestre, o Preventivas permanece focado exclusivamente em sua regra de negócio.

---

# Benefícios

A adoção dessa arquitetura proporciona:

- Separação clara de responsabilidades;
- Menor duplicação de dados;
- Maior integridade das informações;
- Facilidade de integração entre sistemas;
- Código mais simples e de fácil manutenção;
- Evolução independente de cada sistema.

---

# Considerações

Sempre que surgir a necessidade de adicionar um novo campo ao Preventivas, a primeira pergunta deverá ser:

> Esta informação pertence ao fluxo operacional ou ao inventário patrimonial?

Se a resposta for **inventário patrimonial**, a informação deverá permanecer no GLPI.

Se a resposta for **fluxo operacional**, ela poderá fazer parte do Preventivas.

Esse princípio deverá nortear toda a evolução do projeto.
