# Domínio do Módulo Preventivas

Documento reestruturado a partir do levantamento original, organizado por tópicos, sem comentários soltos ou marcações de pergunta/resposta.

## Sumário

1. [Visão Geral](#1-visão-geral)
2. [O Processo Real da Empresa](#2-o-processo-real-da-empresa)
3. [Modelagem do Domínio Técnico](#3-modelagem-do-domínio-técnico)
4. [Perfil de Preventiva — Regras de Negócio](#4-perfil-de-preventiva--regras-de-negócio)
5. [A Preventiva — Entidade e Snapshot](#5-a-preventiva--entidade-e-snapshot)
6. [Estrutura de Dados e Código](#6-estrutura-de-dados-e-código)
7. [Fluxo Consolidado do Domínio](#7-fluxo-consolidado-do-domínio)
8. [Pontos em Aberto](#8-pontos-em-aberto)

---

## 1. Visão Geral

- Objetivo: definir o domínio do módulo Preventivas antes de iniciar qualquer implementação.
- A definição deve considerar o processo real da empresa, não a implementação atual do sistema (GLPI).
- Contexto do negócio: rede de mercados com 9 filiais em Erechim, suporte de TI composto por duas pessoas.

---

## 2. O Processo Real da Empresa

### 2.1 O que é uma Preventiva

- Preventiva é a ação de testes e análises sobre um conjunto de equipamentos, separado por escopo e categoria do item.
- Um PDV (ponto de venda de mercado) é um **conjunto de equipamentos**, não um equipamento único. Compõem um PDV, por exemplo: monitor, impressora, gaveta, scanner, CPU, teclado, balança, e um equipamento híbrido (scanner + balança ao mesmo tempo).
- PDVs possuem numeração inicialmente fixa por filial (ex.: 1 a 30 na matriz), mas essa numeração pode variar entre filiais e pode pular números — por exemplo, por causa do self-checkout, que é uma subcategoria de PDV.
- O sistema não deve se comportar como um GLPI de controle de ativos, tentando cadastrar todos os detalhes de cada equipamento individualmente.
- Necessidade central: o sistema precisa saber "o que é um PDV" (um conjunto de equipamentos) sem engessar o modelo apenas para esse caso específico, permitindo criar preventivas para categorias de equipamento ainda não cadastradas.

### 2.2 Quem Cria uma Preventiva

- Responsável pela criação: gestor, supervisor ou administrador. O técnico apenas executa.
- Hoje o sistema possui apenas dois papéis (roles): administrador e técnico — o administrador acumula a função de gestor/supervisor.
- Separar esses papéis em roles distintas (ex.: gestor vs. supervisor) só deve ser feito quando houver necessidade real, para não gerar complexidade desnecessária.
- No momento da criação, é preciso definir: data de início da atividade e tipo de preventiva (a categoria do item fica atrelada ao tipo de preventiva).
- É necessário existir entidades estáticas por filial (equipamentos/unidades) para servir de referência — sem essa referência, não é possível controlar quantas preventivas já foram executadas sobre determinado item.
- O cadastro dessas entidades deve ser enxuto; um cadastro físico e detalhado de cada item já existe no GLPI da empresa e não deve ser duplicado aqui.

### 2.3 O que é uma Atividade

- Atividade é uma ação executada sobre um equipamento ou unidade dentro de uma preventiva.

**Exemplos reais de atividades:**

- **Venda Teste**: simulação de venda no PDV feita pelo técnico. Ele abre o caixa (lendo o crachá de acesso no scanner e clicando na tecla "abrir caixa"), pesa um produto na balança, finaliza a compra em cartão de crédito (testando o PINPAD), cancela a compra, passa o crachá novamente para confirmar o cancelamento e fecha o caixa sem nenhuma venda no turno. Ao fechar, o relatório de fechamento é impresso (testando a impressora) e a gaveta é fechada.
- **Kitbackup Verificação**: cada filial possui uma sala de TI com um kit de equipamentos de reposição para o PDV. A atividade consiste em verificar se todos os itens de reposição estão presentes no kit.
- **Validar Kitbackup**: testa cada item do kit de reposição, validando se está funcional para uso futuro. O técnico pode retirar um item do kit quando necessário.
- **Validar Balanças Etiquetadoras**: toda filial possui balanças de pesagem para itens pesáveis e perecíveis (ex.: padaria, açougue). A atividade valida se a balança pesa corretamente, imprime corretamente, recebe carga de parâmetros/preços e se as teclas funcionais operam.
- **Validação MITs**: o MIT é o equipamento que exibe preços e propagandas nos setores de padaria e açougue. A atividade valida se está ligado e exibindo as informações corretamente.
- **Validação de Impressoras de Terminal de Preço**: valida se as impressoras de etiquetas e cartazes estão imprimindo corretamente.

### 2.4 O que é uma Pergunta/Checklist

- Perguntas representam ações de verificação sobre o equipamento, dentro de uma atividade.
- Perguntas genéricas sobre "o equipamento" só funcionam quando o sistema conhece entidades concretas (ex.: PDV por loja, self-checkout); sem essas entidades, o processo não avança de forma prática.

**Exemplos:**
- Atividade "Kitbackup Verificação" → pergunta: "O kit está completo? Marque os itens presentes no kitbackup."
- Atividade "Validar Kitbackup" → pergunta: "O kitbackup está funcional? Marque todos os itens funcionais do kitbackup."

### 2.5 Como uma Preventiva Define os Equipamentos

- Hoje, a definição ocorre geralmente por escopo e categoria de equipamento.
- Este ponto ficou em aberto para melhoria de modelagem (ver [Pontos em Aberto](#8-pontos-em-aberto)): busca-se uma solução que não seja genérica demais, nem excessivamente complexa.

### 2.6 Cronograma e Periodicidade

- Hoje não existe um processo formalmente definido para agendar preventivas — essa é uma das razões da criação do módulo.
- Atualmente, o processo é informal: cria-se um chamado genérico no GLPI do tipo "preventiva semanal filial X", listando de forma genérica os equipamentos a validar (PDVs, balanças, MIT, setores, impressoras de terminal de preço).

### 2.7 Nascimento de uma Execução

- Hoje não existe distinção formal entre preventiva planejada e preventiva executada; o processo é aprendido informalmente por quem entra na empresa.
- Um cronograma fixo e rígido engessaria o processo, considerando a realidade operacional:
  - Apenas duas pessoas atuam no suporte de loja.
  - Existem 9 filiais em Erechim.
  - Uma preventiva de PDV pode envolver até 40 unidades, nem sempre testadas de uma só vez.
  - Se o técnico está testando equipamentos em uma filial e surge um problema urgente em outra, ele precisa interromper a preventiva em andamento para atender à demanda emergencial.
- Um cronograma fixo, sem considerar essa dinâmica humana, sobrecarrega o processo e não entrega um sistema funcional na prática.

### 2.8 Quem Executa a Preventiva

- O técnico designado na criação da preventiva sempre executa a preventiva.

### 2.9 Como o Técnico Registra uma Resposta

- O técnico utiliza um coletor da Yep (dispositivo Android que bipa patrimônio e registra no sistema).
- Fluxo esperado: o técnico seleciona os equipamentos que estão funcionais; equipamentos com defeito não são marcados e recebem uma justificativa no campo de resposta (ex.: "impressora do PDV com falha, será efetuada a troca pela impressora do kitbackup").
- O processo deve ser transparente e simples, sem exigir um relatório completo a cada execução. Um resumo descritivo semanal é suficiente para consolidar os problemas encontrados naquela preventiva.

### 2.10 Tipos de Resposta

- Respostas variáveis, incluindo seleção (select) e fotos (podendo ser obrigatórias conforme a atividade).
- O técnico pode anexar evidência fotográfica, já que o coletor possui câmera.

**Fluxo sugerido:**
- O técnico recebe a lista completa de unidades a testar (ex.: 40 PDVs) e marca as que já testou, avançando ao longo de vários dias.
- Ao encontrar um defeito, pode registrar uma observação livre (ex.: "testei os caixas 10 e 15, ambos não ligavam; após apertar o botão, os dois ligaram").
- O técnico também pode criar uma observação personalizada vinculando uma unidade e um equipamento específico (ex.: "caixa 10 com falha na impressora; enviado para reparo externo"), anexando foto ou evidência.

### 2.11 Não Conformidades

- Um problema resolvido ou uma observação deve ser registrado dentro da própria preventiva.
- O fluxo de tratamento de não conformidade deve seguir um modelo de domínio semelhante ao de uma OS (ordem de serviço) de reparo externo, centralizando o histórico do equipamento.
- Deve existir um fluxo colaborativo entre gestor e técnico: o gestor pode ir à loja, validar o trabalho registrado pelo técnico e apontar uma revisão necessária (como uma "errata" sobre o checklist já preenchido); o técnico então corrige e reenvia.

### 2.12 Quando uma Preventiva é Considerada Concluída

- Uma preventiva só é finalizada quando todos os equipamentos previstos são validados — não é viável testar, por exemplo, 40 caixas em um dia em uma filial, mais outras dezenas em outras filiais.
- Enquanto a preventiva não for aprovada, o gestor não pode criar uma nova preventiva do mesmo tipo.
- O objetivo do módulo não é gerar centenas de preventivas nunca finalizadas, e sim medir qualidade e rastrear gaps de manutenção, reduzindo o tempo de indisponibilidade dos equipamentos.
- O processo não deve ser engessado desde o início. A ferramenta deve começar simples, priorizando a adoção pelo time, e evoluir gradualmente — melhor uma ferramenta simples e utilizada do que uma ferramenta complexa que não entrega o domínio.

### 2.13 Preventivas Atrasadas e Prioridade

- Antes de criar um fluxo de reagendamento, é necessário classificar a preventiva por nível de prioridade:
  - Preventivas com impacto direto na operação (ex.: servidores, nobreaks) possuem prazos mais rígidos, pois sua parada impacta diretamente a operação.
  - Preventivas de rotina (ex.: teste de SSD do PDV, limpeza de equipamentos) são menos urgentes.
- O gestor deve ser notificado no dashboard sobre preventivas pendentes de aprovação e sobre preventivas com prazo definido (ex.: "preventiva de nobreak da filial X deve ser feita até o dia X — nenhum técnico foi designado, direcione um responsável").
- Toda preventiva com prazo definido precisa de um responsável inicial designado.

### 2.14 Equipamentos que Não Podem ser Verificados

- O PDV é considerado o "coração" da operação do mercado: um PDV parado representa perda financeira direta e é prioridade máxima.
- O técnico pode registrar uma observação, mas nunca deve deixar o PDV parado — sempre precisa restabelecer o funcionamento do item.
- Uma preventiva não pode ser encerrada com um PDV não conforme ou com verificação pendente.
- Em cenários em que o técnico não consegue testar um PDV no momento (ex.: caixa em uso por uma operadora de venda), ele deve poder avançar para o próximo item e deixar aquele pendente.
- Divergências entre o que foi reportado como testado e o estado real do equipamento (ex.: reclamação da loja sobre equipamento parado há dias, mesmo constando como testado) devem ser rastreáveis pelo sistema, permitindo gerar indicadores de qualidade dos testes realizados.

### 2.15 Preservação do Histórico

- Uma preventiva é uma entidade equivalente à OS de reparo externo, mantendo dados históricos: quando o equipamento foi preventivado, quais equipamentos foram testados e quantos apresentaram defeito.
- O técnico pode encerrar uma preventiva sem testar todos os equipamentos; o gestor consegue identificar o que ficou pendente (ex.: "faltaram 15 PDVs da loja matriz") e solicitar que sejam testados antes de aprovar.
- Ao tentar finalizar com itens pendentes, o sistema deve notificar o técnico (ex.: "existem 10 equipamentos para serem testados, deseja mesmo finalizar?").
- Deve existir um relatório por preventiva, para uso do gestor em reuniões com os responsáveis do grupo empresarial.

### 2.16 Configuração vs. Execução

**Pertence à configuração:**
- Tipo de preventiva e categoria do equipamento.
- Entidades relacionadas ao equipamento. Exemplo: PDV (entidade) na Loja 001 possui 40 PDVs, sendo 30 PDVs e 10 self-checkouts.
  - Composição possível de um PDV: monitor, teclado, scanner, balança, impressora, CPU, gaveta, PINPAD, monitor secundário (quando houver), scanner bióptico (balança e scanner combinados).
  - Composição possível de um self-checkout: monitor, impressora, balança, scanner, scanner bióptico, balança de conferência.

**Pertence à execução:**
- A ação criada sobre a preventiva: o técnico sabe que precisa realizar a preventiva de PDV em determinadas filiais naquela semana, executando ao longo dos dias conforme consegue.
- O gestor não pode criar outra preventiva do mesmo tipo enquanto a anterior não for aprovada.

**Modelo de configuração adotado:**
- Separar por categoria de equipamento e, a partir dela, criar entidades imutáveis — só podendo ser editadas ou desativadas, como os demais cadastros do sistema.

**Ordem de implementação sugerida:**
1. Preventiva de PDV (fluxo completo, para validar o modelo).
2. Preventiva de balanças.
3. Preventiva de kitbackup.

### 2.17 Resumo do Fluxo do Processo Real

```
Tipo de Preventiva
      │
      ▼
Preventiva (início, prazo, prioridade, responsável, status)
      │
   ┌──┴──────────────┐
   ▼                 ▼
Escopo            Atividades (Venda Teste, Verificar SSD etc.)
(Filiais,               │
 Entidades)             ▼
   │            Verificações / Perguntas
   │                     │
   └─────────┬───────────┘
             ▼
         Execução (Técnico, Entidade, Data, Resultado, Observação, Evidência)
             │
             ▼
         Aprovação (Gestor: Aprovado ou Reaberto)
```

---

## 3. Modelagem do Domínio Técnico

### 3.1 Decisão Estrutural de Base

- O sistema não deve tentar transformar cada equipamento individual em uma entidade do domínio — isso o transformaria em um segundo GLPI.
- O sistema trabalha com **unidades operacionais** e suas **composições**.
- O GLPI é uma integração externa, não uma dependência estrutural do domínio de Preventivas.

### 3.2 Tipo de Unidade (UnitType)

- Representa o conceito operacional existente no mundo real (ex.: PDV, Self Checkout, Kit Backup, Servidor, Balança Etiquetadora).
- Não conhece equipamentos específicos nem suas características completas (modelo, marca, IP, placa de rede, SSD, MAC, patrimônio, número de série etc.) — esses dados de cadastro detalhado já existem no GLPI.
- Um Tipo de Unidade não precisa estar vinculado obrigatoriamente a uma única filial. Entidades podem ser compartilhadas entre filiais quando forem de âmbito geral (ex.: "Cancela do Estacionamento"), evitando cadastro duplicado — nesse caso, basta selecionar quais filiais podem usar aquele tipo/perfil.
- Um mouse ou teclado isolados não são unidades operacionais — eles apenas compõem uma unidade; não representam sozinhos o conjunto.

**Permissões de gestão do tipo:**
- Criação, alteração e exclusão restritas a administradores, inicialmente. A evolução futura pode considerar roles mais granulares (ex.: técnico N1, N2, supervisor).
- O técnico não deve poder alterar essas configurações, nem visualizar o módulo de configurações na navegação; qualquer necessidade deve passar pelo gestor.
- Um tipo já utilizado em preventivas pode ter nomenclatura/contexto alterado, mas não deve ser excluído — apenas inativado, preservando o histórico. O identificador (ID) e o modo operacional não devem ser alterados.

### 3.3 Perfil Operacional

- Representa uma composição operacional específica de um determinado Tipo de Unidade.
- Pertence a um único Tipo de Unidade, que não pode ser alterado após a criação do perfil.
- Pode ser inativado; não deve ser excluído.
- Uma alteração que represente uma nova composição operacional deve gerar um **novo perfil**, e não a edição do perfil existente.

**Exemplo — Tipo "PDV":**

| Perfil | Composição |
|---|---|
| PDV Padrão | Monitor (1), Teclado (1), CPU (1), Scanner (1), Balança (1), Impressora (1), Gaveta (1), Pinpad (1) |
| PDV Bióptico | Monitor, Teclado, CPU, Scanner Bióptico (scanner + balança), Impressora, Gaveta, Pinpad |
| PDV 2 Monitores | Monitor Primário, Monitor Secundário, Scanner, Balança, Teclado, Gaveta, CPU, Pinpad |

**Regra de decisão — novo perfil vs. nova unidade:** antes de criar um perfil novo, verificar: *"Isso ainda é a mesma unidade operacional, apenas com uma composição diferente?"*
- Se **sim** → criar um novo perfil.
- Se **não** → criar uma nova unidade operacional (tipo de unidade).

Essa regra evita a proliferação desnecessária de perfis para representar entidades operacionais realmente diferentes.

### 3.4 Perfil Operacional Categoria (Composição)

- Tabela que materializa a composição de um perfil, contendo: `perfil_operacional_id`, `categoria_equipamento_id`, `quantidade`.

**Exemplo — Perfil "PDV Padrão":**

| Categoria | Quantidade |
|---|---|
| CPU | 1 |
| Monitor | 1 |
| Teclado | 1 |
| Scanner | 1 |
| Balança | 1 |
| Impressora | 1 |
| Gaveta | 1 |
| Pinpad | 1 |

### 3.5 Unidade Operacional

- É a transformação do modelo abstrato (Tipo + Perfil) em algo que existe fisicamente em uma filial.
- Composta por: identificador, filial, tipo de unidade e perfil operacional atual.
- A unicidade do identificador é contextual: `filial + tipo_unidade + identificador` deve ser único (o mesmo número de identificador pode existir em filiais diferentes).

**Exemplo:** Tipo = PDV, Perfil = PDV Bióptico, Filial = Matriz, Identificador = 18 → representa "PDV 18 da Matriz, seguindo atualmente o perfil PDV Bióptico".

**Regras de domínio:**
- Representa uma unidade física identificável no mundo real.
- Pertence a uma filial.
- Possui um Tipo de Unidade imutável.
- Possui um identificador imutável.
- Possui um Perfil Operacional atual, que pode ser alterado ao longo do tempo.
- Pode ser inativada.
- Não deve ser excluída.
- Não deve ser reutilizada para representar outra unidade física.

**Distinção conceitual central:**
- **Unidade** = identidade física.
- **Perfil** = configuração operacional dessa identidade.
- **Tipo** = classificação conceitual da unidade.
- **Composição** = o que aquele perfil espera encontrar.

### 3.6 Fluxo Administrativo de Cadastro

1. Cadastrar Categorias de Equipamentos.
2. Cadastrar Tipo de Unidade.
3. Dentro do Tipo, cadastrar Perfis Operacionais.
4. Definir a composição de cada Perfil.
5. Cadastrar as Unidades Operacionais por filial.
6. Selecionar o Tipo.
7. Selecionar o Perfil.
8. Definir o Identificador.

### 3.7 Relacionamentos do Modelo (Eloquent)

```
TipoUnidade
    hasMany PerfilOperacional

PerfilOperacional
    belongsTo TipoUnidade
    hasMany PerfilOperacionalCategoria
    hasMany UnidadesOperacionais

PerfilOperacionalCategoria
    belongsTo PerfilOperacional
    belongsTo Categoria

UnidadeOperacional
    belongsTo Filial
    belongsTo TipoUnidade
    belongsTo PerfilOperacional

TipoPreventiva
    belongsTo TipoUnidade
    hasMany Atividades

Atividade
    belongsTo TipoPreventiva
```

### 3.8 Tipo de Preventiva e Atividades

- Se duas verificações pertencem à mesma rotina de manutenção e são executadas sobre a mesma unidade, elas devem ser **atividades da mesma preventiva**, e não tipos de preventiva diferentes.
- Estrutura de uma Atividade: nome, descrição, tipo de resposta.

**Tipos de resposta disponíveis (planejados):**
- Composição Operacional
- Booleano
- Texto
- Número
- Foto

**Escopo do MVP:** implementar inicialmente apenas **Composição Operacional** e **Foto**, deixando os demais tipos preparados para evolução futura.

### 3.9 Status da Execução

Estados adotados:
- **DISPONÍVEL**
- **EM_EXECUÇÃO**
- **AGUARDANDO_APROVAÇÃO**
- **REPROVADA**
- **APROVADA**
- **FINALIZADA**

Estados descartados e motivo:
- **RASCUNHO**: não é necessário — a preventiva, ao ser criada, já nasce disponível.
- **PAUSADA**: não é necessário. Exigir que o técnico documente cada pausa (ex.: "pausei a preventiva porque fui resolver outro problema em outra filial") sobrecarregaria o registro de cada ação do mundo real. O técnico simplesmente executa quando pode; ao decidir finalizar, o sistema pergunta se ele confirma o encerramento mesmo com unidades pendentes.

### 3.10 Snapshot

- A preventiva é a fonte da verdade: se o técnico souber que uma atividade existia antes e foi removida/desativada pelo gestor, a ferramenta perde credibilidade.
- O snapshot deve preservar, no mínimo:
  - Tipo de preventiva utilizado.
  - Atividades selecionadas.
  - Unidades selecionadas.
  - Perfil das unidades.
  - Composição dos perfis.
  - Configurações necessárias das atividades.
- Alterações futuras nas configurações não devem alterar uma preventiva histórica já criada.

### 3.11 Resultado da Composição Operacional

- Resultado mínimo definido: **CONFORME** / **NÃO CONFORME**.
- Mantém-se abstrato de propósito, permitindo indicar que a unidade está operacional ou com defeito sem exigir uma descrição obrigatória a cada resposta.

### 3.12 Evidência Fotográfica

- Não se deseja múltiplas fotos por teste.
- Exemplo de configuração: Atividade "Teste de Hardware", Tipo "Foto", Foto obrigatória, máximo de 1 foto por atividade/unidade.

### 3.13 Permissões

**Administrador/Gestor:**
- Acessa configurações.
- Cria preventiva.
- Seleciona técnico.
- Aprova/reprova.

**Técnico:**
- Visualiza preventivas atribuídas.
- Inicia.
- Executa.
- Finaliza.

*(Sem estado de "pausa" — ver justificativa em [3.9 Status da Execução](#39-status-da-execução).)*

### 3.14 Estrutura Inicial do Módulo

```
Configurações
├── Tipos de Unidade
├── Perfis Operacionais
├── Unidades Operacionais
└── Tipos de Preventiva
    └── Atividades

Preventivas
└── Execuções
```

**Domínios (estrutura física inicial):**

```
Configuração
├── TipoUnidade
├── PerfilOperacional
├── PerfilOperacionalCategoria
├── UnidadeOperacional
├── TipoPreventiva
├── TipoPreventivaFilial
└── Atividade

Execução
├── Preventiva
├── PreventivaUnidade
├── PreventivaAtividade
└── PreventivaResposta
```

Uma execução de preventiva representa uma fotografia (snapshot) da configuração utilizada no momento de sua criação.

### 3.15 Fluxo de Criação de Preventiva pelo Gestor

```
Gestor
  │
  ▼
Nova Preventiva
  │
  ├── Seleciona Tipo
  ├── Seleciona Filial
  ├── Seleciona Data Inicial
  ├── Seleciona Responsável
  ▼
Sistema
  │
  ├── Carrega atividades do tipo
  └── Carrega unidades operacionais compatíveis
  ▼
Gestor define a composição
  │
  ├── PDV 04 → Atividade A
  ├── PDV 05 → Atividade A
  ├── PDV 18 → Atividade A + B
  └── PDV 24 → Atividade A + B + C
  ▼
Preventiva criada
  │
  ▼
Técnico executa por unidade
  │
  ▼
Resultados
  │
  ▼
Técnico finaliza
  │
  ▼
Gestor valida (Aprova / Reprova / Reabre)
```

- Regra de domínio: uma execução somente pode existir se possuir pelo menos uma atividade delegada.

---

## 4. Perfil de Preventiva — Regras de Negócio

### 4.1 Conceito

- O Perfil de Preventiva é um template reutilizável que define como uma preventiva normalmente será aplicada às unidades operacionais de uma ou mais filiais.
- Não representa uma preventiva executada; representa uma configuração prévia usada para criar futuras instâncias de Preventiva.

### 4.2 Relação com o Tipo de Preventiva

- Pertence obrigatoriamente a um único Tipo de Preventiva.
- O Tipo de Preventiva determina: qual Tipo de Unidade Operacional será utilizado, quais atividades estão disponíveis e qual domínio operacional será validado.
- O Perfil de Preventiva não pode utilizar unidades operacionais de outro tipo (ex.: um perfil de "Preventiva de PDV" só pode usar unidades do Tipo "PDV").

### 4.3 Filiais Participantes

- Um perfil pode estar associado a várias filiais; a mesma filial não pode ser adicionada duas vezes ao mesmo perfil.
- Cada filial participante possui sua própria configuração de regras — as configurações de uma filial não interferem nas configurações de outra.
- As unidades operacionais não são copiadas nem recriadas dentro do perfil; o perfil apenas referencia unidades já existentes.
- As unidades elegíveis são determinadas por: filial, tipo de unidade operacional e situação ativa da unidade.

### 4.4 Regra ALL (Regra Padrão)

- Cada filial participante deve possuir **exatamente uma** regra ALL.
- Representa o comportamento aplicado a "todas as unidades operacionais elegíveis" daquela filial, sem necessidade de cadastrar unidades individualmente (evitando criar, por exemplo, 40 registros para representar 40 PDVs).
- Deve possuir pelo menos uma atividade.
- Novas unidades operacionais cadastradas posteriormente, desde que elegíveis, passam a obedecer automaticamente à regra ALL — nenhum registro adicional precisa ser criado para isso.

### 4.5 Regra SPECIFIC (Exceção)

- Representa uma exceção à regra ALL, aplicável a uma ou várias unidades operacionais.
- Deve possuir pelo menos uma atividade.
- Uma unidade operacional não pode participar de duas regras SPECIFIC dentro da mesma filial/perfil.
- Uma unidade com regra SPECIFIC deixa de utilizar a configuração da regra ALL e passa a usar exclusivamente sua configuração específica.
- Uma regra SPECIFIC só deve existir quando representar uma exceção real ao comportamento padrão; não deve duplicar o que a regra ALL já cobre (evita configuração redundante).
- Pode ser aplicada a várias unidades ao mesmo tempo quando compartilham exatamente a mesma exceção, evitando regras individuais repetidas.

**Exemplo:**

```
ALL:       Teste Operacional, Teste de Impressão, Teste de SSD
SPECIFIC:  PDV 05 → Teste Operacional

Resultado:
  PDV 05      → Teste Operacional
  Demais PDVs → Teste Operacional + Teste de Impressão + Teste de SSD
```

### 4.6 Precedência das Regras

1. Regra SPECIFIC da unidade.
2. Regra ALL da filial.

Uma unidade operacional não pode receber simultaneamente duas regras SPECIFIC conflitantes.

### 4.7 Elegibilidade das Unidades Operacionais

- Devem pertencer ao mesmo Tipo de Unidade do Tipo de Preventiva.
- Devem pertencer à mesma filial da configuração do perfil.
- Não podem aparecer em duas regras conflitantes da mesma filial.
- Não precisam de configuração própria quando cobertas pela regra ALL.

### 4.8 Atividades do Perfil

- As atividades disponíveis para configuração são exclusivamente as atividades pertencentes ao Tipo de Preventiva selecionado — o perfil não pode inventar ou cadastrar atividades próprias.
- Uma atividade pode ser utilizada em várias regras; uma regra pode possuir várias atividades.
- A ordenação das atividades não é responsabilidade da regra neste primeiro momento, podendo evoluir caso o domínio exija sequência no futuro.

### 4.9 O Perfil não Altera o Tipo de Preventiva

- O Perfil de Preventiva utiliza a configuração do Tipo de Preventiva, mas não altera sua definição.
- O perfil serve para determinar: *"Como este Tipo de Preventiva será aplicado às unidades operacionais desta filial?"*

### 4.10 O Perfil não é uma Execução

O Perfil de Preventiva **não possui**:
- Técnico responsável pela execução.
- Data de execução.
- Resultados, respostas ou observações de execução.
- Evidências.
- Aprovação.
- Status de execução.

Essas informações pertencem exclusivamente à Preventiva criada a partir do perfil.

### 4.11 Criação da Preventiva a partir do Perfil

- Ao criar uma nova Preventiva, o gestor pode utilizar um Perfil de Preventiva como configuração inicial.
- O sistema carrega as regras configuradas no perfil; o gestor pode revisar e, quando o fluxo permitir, ajustar a composição antes da criação final.
- O Perfil permanece como template e não é alterado pela criação da Preventiva.

### 4.12 Snapshot na Criação da Preventiva

- Ao criar uma Preventiva a partir de um Perfil, a configuração utilizada deve ser materializada em um snapshot próprio da Preventiva, preservando ao menos:
  - Tipo de Preventiva utilizado.
  - Atividades utilizadas.
  - Regras de aplicação.
  - Unidades operacionais selecionadas.
  - Relação entre unidades e atividades.
- Após a criação, alterações futuras no Perfil, no Tipo de Preventiva ou nas Atividades **não devem** modificar a Preventiva já criada (independência histórica).

### 4.13 Consistência entre Filial, Unidade e Tipo

Deve ser sempre verdadeiro:
- `OperationalUnit.unit_type_id == PreventiveType.unit_type_id`
- `OperationalUnit.branch_id == PreventiveProfileBranch.branch_id`

### 4.14 Composição Mínima de um Perfil Válido

```
PreventiveProfile
    ├── PreventiveType
    └── pelo menos uma filial

Cada filial precisa possuir:
    └── exatamente uma regra ALL
        └── uma ou mais atividades

Podem existir:
    └── zero ou várias regras SPECIFIC
        ├── uma ou várias unidades
        └── uma ou várias atividades
```

### 4.15 Regras de Consistência que o Sistema Deve Impedir

- Perfil sem Tipo de Preventiva.
- Perfil sem filial participante.
- Filial sem regra padrão (ALL).
- Mais de uma regra ALL para a mesma filial.
- Unidade pertencente a outra filial sendo usada na regra.
- Unidade pertencente a outro tipo de unidade sendo usada na regra.
- Atividade pertencente a outro Tipo de Preventiva sendo usada na regra.
- Duas regras SPECIFIC conflitantes para a mesma unidade.
- Regra SPECIFIC idêntica à regra ALL, sem necessidade real.

### 4.16 Nova Unidade Operacional

Quando uma nova unidade operacional é cadastrada:
1. Verificar seu Tipo de Unidade.
2. Verificar sua filial.
3. Localizar perfis compatíveis.
4. Se a filial participar do perfil, a unidade passa automaticamente a estar coberta pela regra ALL.

Nenhum registro precisa ser criado em `preventive_profile_rule_units` para a regra ALL.

Caso a nova unidade precise de comportamento diferente, o gestor pode criar uma regra SPECIFIC para ela, que passa a ter prioridade sobre a regra ALL.

### 4.17 Alteração do Perfil

- O perfil é um template reutilizável.
- Alterar o perfil **não** altera preventivas já criadas.
- Alterar o perfil afeta somente **futuras** preventivas que utilizarem aquele perfil.

### 4.18 Perfil Ativo/Inativo

- Perfil inativo não pode ser utilizado para criação de novas preventivas.
- Um perfil inativo pode: ser editado; receber novas regras; alterar regras existentes; criar regras específicas; alterar configurações de filiais/unidades; ser reativado.
- Perfil já utilizado por uma Preventiva não deve ser apagado fisicamente — apenas desativado.
- A única diferença ao usar um perfil inativo está no momento da execução da preventiva, quanto ao que deve ser considerado válido *(este ponto não foi finalizado no material original — ver [Pontos em Aberto](#8-pontos-em-aberto))*.

### 4.19 Responsabilidade do Domínio

- A validade da composição do Perfil de Preventiva não deve depender exclusivamente do Controller.
- O domínio (Service/Action) deve garantir que a configuração seja consistente antes de permitir sua utilização na criação de uma Preventiva.
- O Controller deve apenas coordenar o fluxo da aplicação, sem conhecer as regras de composição.

### 4.20 Resolução Final de uma Unidade

Para descobrir quais atividades uma unidade deverá executar:

```
Unidade pertence à filial?
        ↓
Unidade é compatível com o Tipo de Unidade?
        ↓
Existe SPECIFIC para a unidade?
        ├── SIM → atividades da SPECIFIC
        └── NÃO → atividades da ALL
```

Resultado: unidade operacional → regra aplicável → atividades → execução da preventiva.

### 4.21 Três Conceitos que Não Devem ser Confundidos

- **Perfil**: "Como queremos configurar esse tipo de preventiva atualmente."
- **Snapshot**: "Como essa preventiva foi efetivamente configurada no momento em que foi criada."
- **Execução**: "O que realmente aconteceu durante a realização daquela preventiva."

### 4.22 Vínculo entre Filial e Perfil

- Uma filial só pode ser vinculada a um Perfil de Preventiva se possuir pelo menos uma Unidade Operacional ativa compatível com o tipo de unidade definido pelo Tipo de Preventiva.

```
Tipo de Preventiva
    │
    └── define o Tipo de Unidade
              │
              ▼
        Perfil de Preventiva
              │
              └── Filiais elegíveis
                    │
                    └── precisam possuir
                        ≥ 1 unidade operacional compatível
                              │
                              ▼
                        Regras da filial
                              │
                              ├── ALL → obrigatória
                              │
                              └── SPECIFIC → exceções
                                      │
                                      ▼
                                  Execução
                                      │
                                      └── valida novamente
                                          unidades elegíveis
```

### 4.23 Princípio Central do Perfil de Preventiva

- Funciona como um template de configuração.
- A regra geral é definida uma única vez através do grupo "TODOS" (ALL).
- As regras específicas (SPECIFIC) existem exclusivamente para representar exceções.
- Uma nova unidade operacional herda automaticamente a regra padrão da filial.
- A Preventiva criada posteriormente recebe um snapshot próprio, garantindo histórico e independência das alterações futuras no Perfil.
- Objetivo: permitir que o gestor configure uma Preventiva complexa uma única vez e reutilize essa configuração, sem precisar cadastrar manualmente cada unidade operacional.

---

## 5. A Preventiva — Entidade e Snapshot

### 5.1 Entidade Preventive

Estrutura da entidade principal:

```
Preventive
├── filial
├── técnico responsável
├── tipo de preventiva
├── perfil de preventiva utilizado como origem
├── data de início
├── data de conclusão prevista (futura/opcional)
├── status
└── snapshot da configuração utilizada
```

Exemplo:

```
Preventiva #15
Filial: Matriz
Tipo: Preventiva de PDV
Perfil: Preventiva PDV - Padrão
Técnico: João
Início: 25/08/2026
Prazo: NULL
Status: Pendente
```

- Essa estrutura representa a "capa" da preventiva.
- A preventiva não deve depender do perfil para saber o que precisa ser executado — esse é o ponto central do desenho.

### 5.2 O Perfil é Apenas a Origem

- Ao criar uma preventiva a partir de um perfil, o sistema resolve as regras (ALL/SPECIFIC) no momento da criação, e esse resultado deve ser congelado.
- Não deve ser salvo apenas `preventive_profile_id`, consultando as regras do perfil durante a execução — se o gestor alterar o perfil meses depois, a preventiva já criada mudaria indiretamente, violando o princípio de snapshot definido em [4.12](#412-snapshot-na-criação-da-preventiva).

Exemplo de resolução no momento da criação:

```
Perfil "Preventiva PDV - Padrão" (Matriz)
  ALL:      Teste Operacional, Teste de Impressão, Organização de Cabos
  PDV 05:   Teste Operacional, Teste de SSD

Resolvido na criação da preventiva:
  PDV 01 → Teste Operacional, Teste de Impressão, Organização de Cabos
  PDV 02 → Teste Operacional, Teste de Impressão, Organização de Cabos
  PDV 03 → Teste Operacional, Teste de Impressão, Organização de Cabos
  PDV 05 → Teste Operacional, Teste de SSD
```

### 5.3 Snapshot em Três Níveis

Estrutura proposta:

```
preventives
    │
    ├── preventive_snapshot_units
    │       │
    │       └── preventive_snapshot_unit_activities
    │
    └── preventive_snapshot_activities
```

Os dados de execução (respostas, resultados) ficam separados dessa estrutura de snapshot.

**preventive_snapshot_units**
- Campos: `id`, `preventive_id`, `operational_unit_id`, `unit_type_id`, `unit_type_name`, `operational_profile_id`, `operational_profile_name`, `identifier`, `name`.
- Congela quais unidades existiam no escopo da preventiva naquele momento — necessário porque a unidade operacional também pode mudar no futuro. Se o gestor alterar o perfil operacional do PDV 05 depois, a preventiva antiga não pode passar a enxergar a nova composição; o snapshot representa o estado da unidade naquele momento.

**preventive_snapshot_activities**
- Campos: `id`, `preventive_id`, `activity_id`, `activity_category_id`, `category_name`, `name`, `description`, `type`.
- O `activity_id` original é mantido para rastreabilidade, mas `name`, `description`, `type` e `category` devem ser copiados (snapshot) — assim, uma alteração futura na atividade original (ex.: renomear "Teste de Impressão" para "Teste completo de impressão fiscal") não altera a preventiva antiga.

**preventive_snapshot_unit_activities**
- Campos: `id`, `preventive_snapshot_unit_id`, `preventive_snapshot_activity_id`, `source_rule_id`, `created_at`.
- Responde à pergunta: "quais atividades exatamente essa unidade precisa executar nesta preventiva?"

```
PDV 01 → Teste Operacional, Teste de Impressão, Organização de Cabos
PDV 02 → Teste Operacional, Teste de Impressão, Organização de Cabos
PDV 05 → Teste Operacional, Teste de SSD
```

Essa tabela resolve a relação entre ALL/SPECIFIC de forma definitiva no momento da criação.

### 5.4 Comportamento ALL/SPECIFIC na Criação

- O conceito ALL/SPECIFIC pertence exclusivamente à configuração do perfil.
- A instância (preventiva) recebe o resultado já resolvido dessa configuração.
- Após a criação, a preventiva não precisa mais interpretar ALL ou SPECIFIC para ser executada — ela já possui a lista final de unidade × atividades.

### 5.5 Personalização pelo Gestor na Criação

- Na tela de criação, ao selecionar Filial, Tipo e Perfil, o sistema carrega a configuração do perfil (ALL e SPECIFIC) já resolvida.
- O gestor pode optar por usar a configuração padrão ou alterar pontualmente as atividades de cada unidade (marcando/desmarcando) antes da criação definitiva.
- Essa personalização não altera o perfil — afeta apenas o que será gravado no snapshot da nova preventiva.

```
PERFIL       → template
PREVENTIVA   → cópia personalizada/congelada
```

### 5.6 Momento da Criação do Snapshot

- O snapshot deve ser gravado somente na criação definitiva da preventiva — não durante o preenchimento do formulário.

```
GET create
    ↓
carrega perfil
    ↓
resolve regras
    ↓
exibe formulário
    ↓
gestor personaliza
    ↓
POST
    ↓
valida
    ↓
transaction
    ↓
cria Preventive
    ↓
cria snapshots
    ↓
commit
```

- Se qualquer etapa falhar dentro da transação, ocorre rollback, evitando uma preventiva parcialmente criada.

### 5.7 Edição Posterior da Preventiva

- Regra definida: após criada, o gestor poderá alterar a preventiva enquanto o técnico não tiver finalizado todas as atividades.
- Abordagem faseada:
  1. Implementar primeiro o fluxo básico: criar preventiva → gerar snapshot → visualizar → editar atividades → salvar → executar.
  2. Depois, acrescentar as regras de bloqueio:

| Campo | Situação |
|---|---|
| Filial | Bloqueada |
| Técnico | Bloqueado |
| Tipo | Bloqueado |
| Perfil | Bloqueado |
| Data | Bloqueada |
| Atividades pendentes | Editáveis |
| Unidades não validadas | Editáveis |
| Unidades já validadas | Bloqueadas |

### 5.8 Data de Conclusão

- O banco deve já ser preparado com os campos `started_at`, `due_at` (nullable) e `completed_at` (nullable).
- O comportamento de prazo (`due_at`) não precisa ser implementado agora, mas a estrutura já fica pronta para isso, sem necessidade de alterar o modelo fundamental da preventiva mais adiante.

### 5.9 Status da Preventiva

Enumeração definida:
- `PENDING`
- `IN_PROGRESS`
- `AWAITING_APPROVAL`
- `APPROVED`
- `REOPENED`
- `CANCELLED`

```
PENDING → IN_PROGRESS → AWAITING_APPROVAL → APPROVED

AWAITING_APPROVAL → REOPENED → IN_PROGRESS
```

- A aprovação não precisa ser implementada de imediato, mas deixar o domínio preparado evita refatoração posterior.
- *(Este status refina o conjunto definido em [3.9](#39-status-da-execução), acrescentando `REOPENED` e `CANCELLED`.)*

### 5.10 Definição Consolidada

Preventive representa uma execução planejada de manutenção preventiva para uma determinada filial, atribuída a um técnico, originada de um tipo e perfil de preventiva, cuja configuração operacional é materializada em snapshots no momento de sua criação.

```
Tipo de Unidade
      ↓
Perfil Operacional
      ↓
Unidade Operacional
      ↓
Tipo de Preventiva
      ↓
Atividades
      ↓
Perfil de Preventiva
      ↓
Regras ALL / SPECIFIC
      ↓
──────────────────────────
   Criação da Preventiva
──────────────────────────
      ↓
   Preventive
      ↓
Resolve regras
      ↓
Snapshot das unidades
      ↓
Snapshot das atividades
      ↓
Unidade × Atividade
      ↓
──────────────────────────
        Execução
──────────────────────────
```

---

## 6. Estrutura de Dados e Código

### 6.1 Estrutura de Tabelas Proposta

```
preventive_profiles
--------------------
id
preventive_type_id
name
description
active
created_at
updated_at

preventive_profile_branches
----------------------------
id
preventive_profile_id
branch_id
created_at
updated_at

preventive_profile_rules
-------------------------
id
preventive_profile_branch_id
type              // all | specific
created_at
updated_at

preventive_profile_rule_units
-------------------------------
id
preventive_profile_rule_id
operational_unit_id
```

Além dessas, é necessária uma tabela equivalente para vincular atividades a cada regra (`preventive_profile_rule_activities`).

**Essa estrutura permite representar:**
- Um perfil reutilizável.
- Várias filiais.
- Uma regra padrão por filial.
- Várias regras específicas por filial.
- Várias unidades dentro de uma regra específica.
- Várias atividades dentro de qualquer regra.
- Prioridade natural da regra específica sobre a ALL.
- Novas unidades herdando automaticamente a ALL.
- Nenhuma necessidade de criar 40 configurações para 40 PDVs.

### 6.2 Responsabilidade por Camada (Backend)

```
Migration → Model → Policy → Form Request → Service → Controller → Routes → Blade
```

| Camada | Responsabilidade |
|---|---|
| **Migration** | Estrutura e integridade do banco. |
| **Model** | Relacionamentos e representação dos dados. |
| **Policy** | Quem pode visualizar/criar/alterar/excluir. |
| **Form Request** | Valida estrutura, tipos, campos obrigatórios e formatos dos dados recebidos. |
| **Service / Domínio** | Valida regras de negócio, resolve precedência, valida compatibilidade, cria a composição e executa a transação (filiais, regras ALL/SPECIFIC, unidades, atividades). |
| **Controller** | Recebe o Request, chama a Policy, chama o Service, retorna a resposta ou a View. Não deve conhecer as regras de composição. |
| **Routes** | Define os endpoints. |
| **Blade** | Interface do gestor. |

### 6.3 Transação

- A criação ou atualização do perfil deve ocorrer dentro de uma única transação.
- Se qualquer parte falhar (perfil, filiais, regras, unidades, atividades), todas devem ser revertidas.

### 6.4 Organização do Front-end (JavaScript)

Para evitar um arquivo único e grande (`preventive-profile.js`), recomenda-se separar por responsabilidade:

```
resources/js/preventive-profile/
├── form-data.js
├── branches.js
├── rules.js
├── form.js
└── index.js
```

| Arquivo | Responsabilidade |
|---|---|
| `form-data.js` | Requisições AJAX. Busca os dados do formulário no backend. |
| `branches.js` | Filiais: seleção/deseleção e carregamento das unidades operacionais. |
| `rules.js` | Configuração das regras ALL/SPECIFIC, seleção de unidades e atividades, validação visual de conflitos. |
| `form.js` | Orquestra o formulário: inicialização, eventos gerais, integração de `form-data` + `branches` + `rules`, preparação para create/edit. |
| `index.js` | Entry point do módulo. Inicializa o comportamento quando a página correspondente estiver presente. |

**Divisão de responsabilidades:**
- AJAX → `form-data.js`
- DOM / interface → `branches.js`, `rules.js`
- Orquestração → `form.js`
- Entrada do módulo → `index.js`

### 6.5 Separação de Domínio Reforçada

- Perfil de Preventiva diz **onde** a preventiva pode ser aplicada.
- Regra ALL diz qual é a **configuração padrão** daquela filial.
- Regra SPECIFIC **modifica** essa configuração para uma unidade operacional específica.
- A execução só acontece depois que a **configuração mínima da filial está válida**.

---

## 7. Fluxo Consolidado do Domínio

```
Tipo de Unidade
      ↓
Perfil Operacional
      ↓
Composição do Perfil
      ↓
Unidade Operacional
      ↓
Tipo de Preventiva
      ↓
Atividades
      ↓
Perfil de Preventiva
      ↓
Regras por filial
      ├── ALL → atividades padrão
      └── SPECIFIC → exceções
      ↓
Preventiva
      ↓
Snapshot
      ↓
Execuções por Unidade Operacional
      ↓
Validações
      ↓
Finalização
      ↓
Aprovação do Gestor
```

**Diagrama de validação final:**

```
Resultado
   ↓
Validação do Gestor
   │
   ├── Aprovada  → Finalizada
   └── Reprovada → Retorna ao técnico
```

---

## 8. Pontos em Aberto

- **Definição de equipamentos por preventiva** ([2.5](#25-como-uma-preventiva-define-os-equipamentos)): a modelagem de como uma preventiva define exatamente quais equipamentos/unidades participam (por escopo e categoria) ainda carece de uma solução final que equilibre simplicidade e flexibilidade.
- **Perfil inativo em execução** ([4.18](#418-perfil-ativoinativo)): o comportamento exato do sistema ao considerar um perfil inativo no momento da execução de uma preventiva não foi finalizado no material original — o texto original é interrompido nesse ponto.
- **Tabela de vínculo atividade-regra** ([6.1](#61-estrutura-de-tabelas-proposta)): mencionada como necessária (`preventive_profile_rule_activities`), mas sem estrutura de colunas detalhada no material original.
- **Dados textuais adicionais do snapshot** ([5.3](#53-snapshot-em-três-níveis)): cogitou-se guardar `preventive_type_name` e `preventive_profile_name` na própria preventiva para reforçar rastreabilidade, mas a decisão ficou para o momento de desenho da migration.
