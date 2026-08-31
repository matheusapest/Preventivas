# Módulo de Empresas

## Objetivo

O módulo de Empresas é responsável pelo cadastro das empresas pertencentes ao Grupo Empresarial.

Ele representa o nível mais alto da estrutura organizacional do sistema Preventivas, servindo como base para o cadastro das filiais.

Cada filial pertence obrigatoriamente a uma empresa.

---

# Responsabilidades

Este módulo é responsável por:

- cadastrar empresas;
- editar empresas;
- ativar e inativar empresas;
- disponibilizar empresas para o cadastro de filiais.

O módulo não possui responsabilidades relacionadas à operação dos equipamentos.

---

# Fluxo Operacional

O fluxo de utilização é simples.

```
Cadastrar Empresa

↓

Cadastrar Filiais

↓

Cadastrar Equipamentos nas Filiais
```

As empresas representam apenas a estrutura organizacional do grupo empresarial.

---

# Estrutura de Dados

Entidade principal:

```
Company
```

Principais atributos:

- Nome;
- Situação (Ativa/Inativa).

Relacionamentos:

```
Company

↓

Branch
```

Uma empresa pode possuir várias filiais.

---

# Regras de Negócio

As seguintes regras são aplicadas ao módulo.

- O nome da empresa deve ser único.
- Apenas empresas ativas podem ser utilizadas no cadastro de novas filiais.
- Empresas não devem ser removidas fisicamente.
- O controle é realizado através do campo `active`.

---

# Dependências

Este módulo não depende de nenhum outro módulo.

Ele representa a primeira camada da estrutura organizacional do sistema.

---

# Integrações

O módulo é utilizado por:

- Filiais.

Toda filial pertence obrigatoriamente a uma empresa.

---

# Evoluções Futuras

Poderão ser adicionadas novas informações administrativas, caso sejam necessárias ao domínio do sistema.

Exemplos:

- Razão Social;
- CNPJ;
- Endereço;
- Contatos.

Essas informações somente deverão ser adicionadas caso façam parte das regras de negócio do Preventivas.

---

# Considerações

O cadastro de empresas possui finalidade exclusivamente organizacional.

Ele não representa unidades operacionais.

As operações do sistema ocorrem sempre no contexto de uma filial.
