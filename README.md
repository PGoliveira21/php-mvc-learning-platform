# 📚 Onki - Sistema de Estudo com Repetição Espaçada

> Um sistema web de flashcards inspirado no Anki, desenvolvido com PHP puro e arquitetura MVC, focado em aprendizado ativo e acompanhamento de desempenho.

![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![MVC](https://img.shields.io/badge/Arquitetura-MVC-blue?style=for-the-badge)
![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)

---

##  Sobre o Projeto

O **Onki** é uma aplicação web completa para criação e estudo de flashcards com múltipla escolha. O sistema utiliza o conceito de **repetição espaçada** para otimizar o aprendizado: perguntas acertadas são removidas da sessão do dia, enquanto as erradas retornam para reforço imediato.

Desenvolvido do zero com **PHP puro** (sem frameworks), o projeto demonstra domínio de conceitos fundamentais como **arquitetura MVC**, **PDO com prepared statements**, **segurança de dados** e **design responsivo mobile-first**.

🌐 **Demo ao vivo**: [onki.infy.click](https://onki.infy.click)

---

## ✨ Funcionalidades

### 🔐 Autenticação e Usuários
- Cadastro e login com senhas hasheadas (`password_hash`)
- Sessões PHP para controle de acesso
- Dados isolados por usuário (multi-tenant)

### 📁 Categorias
- Criação, edição e exclusão de categorias/matérias
- Associação de perguntas a categorias específicas
- Filtro de estudo por categoria

###  Gerenciamento de Perguntas
- CRUD completo de perguntas de múltipla escolha (4 alternativas)
- Interface de listagem com **accordion** por categoria
- Estatísticas individuais por pergunta (tentativas, acertos, erros)
- Edição e exclusão com confirmação

### 🎓 Sessão de Estudo (Core)
- Algoritmo de priorização inteligente:
  - 🥇 **Prioridade 1**: Perguntas novas
  - 🥈 **Prioridade 2**: Perguntas corretas de dias anteriores
  - 🥉 **Prioridade 3**: Perguntas erradas hoje (vão para o final da fila)
- Perguntas acertadas **não reaparecem no mesmo dia**
- Perguntas erradas **retornam na mesma sessão**
- Validação **100% no servidor** (anti-cheat: a resposta correta nunca é enviada ao frontend)

### 📊 Dashboard de Estatísticas
- Métricas em tempo real: acertos, erros e precisão do dia
- Barras de progresso visuais (hoje e geral)
- **Histórico de erros por categoria**: identifica as perguntas que o usuário mais erra
- Opção de "Revisar" para resetar perguntas acertadas

### 📱 Interface Responsiva
- Layout **desktop** com navbar superior fixa
- Layout **mobile** com **bottom navigation bar** estilo app nativo
- Design moderno com gradientes, glassmorphism e animações suaves
- Totalmente responsivo (mobile-first)

---

## 🛠️ Tecnologias Utilizadas

| Tecnologia | Uso |
|------------|-----|
| **PHP 7.4+** | Backend, lógica de negócio, roteamento |
| **MySQL** | Banco de dados relacional |
| **PDO** | Conexão segura com prepared statements |
| **HTML5 + CSS3** | Estrutura e estilização (CSS inline para performance) |
| **JavaScript (Vanilla)** | Interatividade, chamadas fetch API |
| **Arquitetura MVC** | Separação de responsabilidades |
| **InfinityFree** | Hospedagem gratuita (produção) |

---

## 🏗️ Arquitetura do Projeto

O projeto segue o padrão **MVC (Model-View-Controller)**:


---
