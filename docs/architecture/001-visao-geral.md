# Visão Geral do Projeto

# Documento

**Projeto:** Preventivas  
**Versão:** 1.0  
**Última atualização:** 07/08/2026  
**Status:** Em elaboração

## Objetivo

O **Preventivas** é um sistema desenvolvido para gerenciar o fluxo operacional de preventivas, movimentações, manutenção externa e auditorias de equipamentos utilizados nas filiais do Grupo Empresarial.

O sistema foi projetado para atuar de forma complementar ao GLPI, concentrando as regras de negócio relacionadas à operação diária das equipes de TI, sem duplicar funcionalidades de inventário já existentes.

---

## Problema

Atualmente, o controle operacional das preventivas é realizado de forma descentralizada, dificultando o acompanhamento de:

- Preventivas executadas;
- Equipamentos enviados para manutenção;
- Histórico de movimentações;
- Auditorias realizadas;
- Cronogramas de execução;
- Indicadores operacionais.

Além disso, parte dessas informações não pertence ao domínio do GLPI, tornando necessária a criação de um sistema especializado.

---

## Objetivos do Sistema

O Preventivas possui como principais objetivos:

- Centralizar o gerenciamento das preventivas;
- Controlar a movimentação dos equipamentos;
- Registrar o fluxo de manutenção externa;
- Permitir auditorias patrimoniais;
- Gerar indicadores e relatórios operacionais;
- Manter histórico completo das operações realizadas.

---

# Escopo

O sistema contempla os seguintes módulos:

## Cadastros

- Empresas
- Filiais
- Números de Filiais
- Categorias
- Fabricantes
- Modelos de Equipamentos
- Equipamentos

## Operação

- Movimentações
- Manutenção Externa
- Preventivas
- Execuções
- Auditorias

## Gestão

- Cronogramas
- Relatórios
- Dashboard

---

# Público-Alvo

O sistema é destinado às equipes responsáveis pela gestão operacional dos equipamentos, incluindo:

- Gestores de TI;
- Técnicos de suporte;
- Equipes responsáveis por preventivas;
- Auditores internos.

---

# Tecnologias Utilizadas

## Backend

- PHP 8.3
- Laravel 13
- Eloquent ORM

## Banco de Dados

- MySQL / MariaDB

## Frontend

- Blade
- Tailwind CSS
- Alpine.js

## Infraestrutura

- Docker
- Docker Compose
- Nginx

---

# Filosofia do Projeto

Durante o desenvolvimento foram definidos alguns princípios fundamentais.

## Separação de responsabilidades

Cada camada possui uma responsabilidade única.

- Models representam entidades.
- Controllers orquestram o fluxo.
- FormRequests realizam validações.
- Policies controlam permissões.
- Services serão utilizados apenas quando envolverem regras de negócio entre múltiplas entidades.

---

## Componentização

Toda a interface deve reutilizar componentes Blade para manter consistência visual e reduzir duplicação de código.

---

## Padronização

O projeto segue um padrão único para todos os módulos.

Cada CRUD é composto por:

- Migration
- Model
- Policy
- StoreRequest
- UpdateRequest
- Controller
- Rotas
- Views
- Documentação

---

## Código limpo

O objetivo é manter um código simples, organizado e de fácil manutenção, priorizando:

- responsabilidade única;
- reutilização;
- baixo acoplamento;
- alta legibilidade.

---

# O que o Preventivas NÃO é

O Preventivas não pretende substituir sistemas especializados já existentes.

Em especial, ele não substitui o GLPI como sistema de inventário.

Informações cadastrais detalhadas dos equipamentos permanecem sob responsabilidade do GLPI.

O Preventivas concentra apenas o fluxo operacional relacionado às preventivas e movimentações.

---

# Evolução do Projeto

O desenvolvimento segue uma abordagem incremental.

Cada módulo somente é considerado concluído após:

1. Implementação;
2. Homologação;
3. Documentação técnica.

Essa abordagem garante consistência entre código, arquitetura e documentação durante toda a evolução do sistema.

---

# Próximos Documentos

Esta documentação é complementada pelos demais documentos presentes na pasta `docs/architecture`.

- 002 - GLPI vs Preventivas
- 003 - Convenções do Projeto
- 004 - Padrão de CRUD
- 005 - Organização do Laravel
- 006 - Arquitetura do Domínio
