# Módulo de Filiais

## Objetivo

O módulo de Filiais é responsável pelo cadastro das unidades operacionais pertencentes às empresas do Grupo Empresarial.

As filiais representam o ponto central de toda a operação do Preventivas.

Todos os equipamentos, movimentações, preventivas, auditorias e processos de manutenção externa são executados no contexto de uma filial.

---

# Responsabilidades

Este módulo é responsável por:

- cadastrar filiais;
- editar filiais;
- ativar e inativar filiais;
- associar a filial a uma empresa;
- associar um número de filial;
- definir o estado (UF);
- definir o tipo da filial.

---

# Fluxo Operacional

O cadastro de filiais faz parte da estrutura organizacional do sistema.

```
Cadastrar Empresa

↓

Cadastrar Filial

↓

Cadastrar Equipamentos

↓

Executar Operações
```

Nenhuma operação poderá ser realizada sem que exista uma filial cadastrada.

---

# Estrutura de Dados

Entidade principal:

```
Branch
```

Principais atributos:

- Empresa;
- Número da Filial;
- Nome;
- Estado (UF);
- Tipo da Filial;
- Situação (Ativa/Inativa).

Relacionamentos:

```
Company
      │
      ▼
Branch
```

```
BranchCode
      │
      ▼
Branch
```

```
Branch
      │
      ▼
Equipment
```

Uma filial pertence obrigatoriamente a uma empresa e possui um único número identificador.

---

# Regras de Negócio

As seguintes regras são aplicadas ao módulo.

- Toda filial deve pertencer a uma empresa.
- Toda filial deve possuir um número de filial.
- O número de filial não poderá ser reutilizado enquanto estiver vinculado a uma filial ativa.
- Apenas empresas ativas poderão ser utilizadas.
- Apenas números de filiais disponíveis poderão ser selecionados.
- Filiais não devem ser removidas fisicamente.
- O controle é realizado através do campo `active`.

---

# Dependências

Este módulo depende dos seguintes cadastros:

- Empresas;
- Números de Filiais.

Sem esses cadastros não é possível criar uma filial.

---

# Integrações

O módulo é utilizado por praticamente todo o sistema.

Principais integrações:

- Equipamentos;
- Movimentações;
- Manutenção Externa;
- Preventivas;
- Auditorias;
- Dashboard;
- Relatórios.

A filial representa a origem da maior parte das operações do Preventivas.

---

# Decisões Arquiteturais

Durante o desenvolvimento do sistema foi definida uma importante regra de negócio.

O cadastro de filiais representa exclusivamente as unidades pertencentes ao Grupo Empresarial.

Empresas terceirizadas não fazem parte deste módulo.

Essa decisão evita confusão durante as operações e garante que todas as preventivas, movimentações e auditorias ocorram apenas nas filiais do grupo.

---

# Evoluções Futuras

O módulo poderá receber novas funcionalidades conforme a evolução do sistema.

Exemplos:

- Informações complementares da unidade;
- Responsável pela filial;
- Contatos administrativos;
- Indicadores operacionais por filial.

Essas informações somente deverão ser adicionadas caso façam parte do domínio do Preventivas.

---

# Considerações

A filial é a principal unidade operacional do Preventivas.

Embora o GLPI seja responsável pelo inventário patrimonial, todas as operações do sistema Preventivas acontecem sempre no contexto de uma filial.

Essa modelagem permite controlar corretamente movimentações, manutenções externas, preventivas e auditorias sem depender da estrutura física cadastrada no GLPI.

Por esse motivo, a filial representa um elemento do processo operacional e não apenas um cadastro administrativo.
