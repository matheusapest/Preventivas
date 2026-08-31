# Módulo de Números de Filiais

## Objetivo

O módulo de Números de Filiais é responsável pelo gerenciamento dos identificadores utilizados pelas filiais do Grupo Empresarial.

Seu objetivo é centralizar a administração desses números, evitando duplicidade e garantindo padronização entre todas as filiais cadastradas.

Esse módulo funciona como um cadastro auxiliar para o módulo de Filiais.

---

# Responsabilidades

Este módulo é responsável por:

- cadastrar novos números de filiais;
- editar números existentes;
- ativar e inativar números;
- disponibilizar números para o cadastro de filiais.

Não possui responsabilidades relacionadas à operação do sistema.

---

# Fluxo Operacional

O cadastro ocorre antes da criação da filial.

```
Cadastrar Número da Filial

↓

Cadastrar Filial

↓

Utilizar a Filial nas Operações
```

Cada número poderá ser utilizado por apenas uma filial ativa.

---

# Estrutura de Dados

Entidade principal:

```
BranchCode
```

Principais atributos:

- Código da Filial;
- Situação (Ativo/Inativo).

Relacionamentos:

```
BranchCode
      │
      ▼
Branch
```

Um número poderá estar associado a uma única filial.

---

# Regras de Negócio

As seguintes regras são aplicadas ao módulo.

- O código deve ser único.
- Apenas códigos ativos podem ser utilizados.
- Um código não pode estar vinculado simultaneamente a duas filiais ativas.
- Códigos não devem ser removidos fisicamente.
- O controle é realizado através do campo `active`.

---

# Dependências

Este módulo não depende de nenhum outro cadastro.

---

# Integrações

É utilizado exclusivamente pelo módulo de Filiais.

Durante o cadastro da filial, somente números disponíveis são apresentados ao usuário.

---

# Justificativa

A separação entre Filiais e Números de Filiais permite administrar previamente todos os códigos utilizados pela organização.

Essa abordagem evita cadastros duplicados, facilita futuras expansões e mantém a consistência das informações.

---

# Evoluções Futuras

Poderão ser adicionadas funcionalidades como:

- Reserva de números;
- Histórico de utilização;
- Importação em lote;
- Associação automática durante integrações.

---

# Considerações

O módulo possui finalidade exclusivamente administrativa.

Ele existe para garantir a integridade dos identificadores utilizados pelas filiais do Grupo Empresarial.
