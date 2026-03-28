<div align="center">

<img src="https://img.shields.io/badge/PHP-5.5.8-777BB4?style=for-the-badge&logo=php&logoColor=white"/>
<img src="https://img.shields.io/badge/MySQL-5.6-4479A1?style=for-the-badge&logo=mysql&logoColor=white"/>
<img src="https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white"/>
<img src="https://img.shields.io/badge/JavaScript-ES6-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black"/>
<img src="https://img.shields.io/badge/status-concluído-brightgreen?style=for-the-badge"/>
<img src="https://img.shields.io/badge/TCC-2025-blue?style=for-the-badge"/>

<br/>
<br/>

# 🏥 MedClick
### Sistema de Gestão de Consultas Médicas

*Trabalho de Conclusão de Curso — 2025*

[Sobre](#-sobre-o-projeto) · [Funcionalidades](#-funcionalidades) · [Tecnologias](#-tecnologias) · [Como Executar](#-como-executar) · [Segurança](#-segurança) · [Autor](#-autor)

</div>

---

## 📌 Sobre o Projeto

O **MedClick** é um sistema web desenvolvido como Trabalho de Conclusão de Curso com o objetivo de modernizar e humanizar o processo de agendamento de consultas médicas no Brasil. A proposta nasce de uma necessidade real: pacientes que enfrentam longas filas de espera, ligações sem retorno e dificuldade em encontrar horários compatíveis com sua rotina.

A plataforma conecta pacientes e médicos em um ambiente digital unificado, onde cada lado tem controle total sobre sua experiência. O paciente encontra o profissional certo, visualiza horários reais disponíveis e confirma a consulta em poucos cliques. O médico, por sua vez, configura sua agenda de forma autônoma, define intervalos de atendimento e acompanha todos os agendamentos sem depender de intermediários.

> 💡 O projeto foi desenvolvido com foco em usabilidade, segurança e praticidade, aplicando conceitos de engenharia de software, modelagem de banco de dados e desenvolvimento web full-stack.

---

## ✨ Funcionalidades

### Para Pacientes

O paciente realiza um cadastro completo com dados pessoais e foto de perfil, e acessa o sistema com login protegido por senha criptografada. Dentro da plataforma, é possível navegar pelos médicos disponíveis filtrando por especialidade, visualizar os horários abertos em tempo real e concluir o agendamento com informações completas sobre o local de atendimento, incluindo endereço do consultório ou hospital.

### Para Médicos

O médico possui um painel exclusivo onde define seu horário de funcionamento com precisão: horário de abertura, encerramento, intervalo de almoço e, opcionalmente, atendimento 24 horas. Os horários são liberados com intervalos personalizáveis — 15, 30, 45 ou 60 minutos — adaptando-se ao ritmo de cada especialidade. O painel também exibe todos os agendamentos confirmados, facilitando a organização da agenda.

### Geral

O sistema cobre múltiplas especialidades médicas, previne automaticamente a duplicidade de agendamentos e foi construído com uma interface responsiva, funcional em qualquer dispositivo — do celular ao desktop.

---

## 🛠️ Tecnologias

O projeto foi desenvolvido com uma stack web clássica, escolhida pela robustez e compatibilidade com o ambiente de hospedagem local via EasyPHP.

| Camada | Tecnologia | Versão |
|--------|-----------|--------|
| Backend | PHP | 5.5.8 |
| Banco de Dados | MySQL | 5.6 |
| Frontend | HTML5 + CSS3 | — |
| Interatividade | JavaScript (ES6) + AJAX | — |
| Componentes | Bootstrap | 5.3 |
| Ícones | Font Awesome | 6.4 |

---

## 🗃️ Banco de Dados

O banco de dados foi modelado para cobrir todos os fluxos do sistema com integridade e sem redundâncias. São sete tabelas principais:

| Tabela | Responsabilidade |
|--------|-----------------|
| `pacientes` | Dados cadastrais e credenciais dos pacientes |
| `medicos` | Informações profissionais dos médicos |
| `especialidades` | Catálogo de especialidades disponíveis |
| `agenda` | Horários liberados por cada médico |
| `consultas` | Registro de todos os agendamentos realizados |
| `horarios_funcionamento` | Configuração do expediente de cada médico |
| `locais_consulta` | Endereços dos consultórios e unidades de atendimento |

---

## 🚀 Como Executar

### Pré-requisitos

Antes de começar, certifique-se de ter instalado o **EasyPHP** (compatível com PHP 5.5.8) e o **MySQL** em sua máquina.

### Passo a passo

**1. Clone o repositório**

```bash
git clone https://github.com/seu-usuario/medclick.git
```

**2. Configure o banco de dados**

Abra o phpMyAdmin, crie um banco de dados chamado `medclick` e importe o arquivo `medclick.sql` disponível na raiz do projeto.

**3. Configure a conexão**

Mova os arquivos para a pasta `www` do EasyPHP e ajuste as credenciais de acesso ao banco no arquivo `conexao.php`.

**4. Acesse o sistema**

```
http://localhost/medclick/
```

### Credenciais de Teste

| Perfil | E-mail | Senha |
|--------|--------|-------|
| Paciente | edu@gmail.com | 123 |
| Médico | edu@gmail.com | 123 |

---

## 🔒 Segurança

A segurança foi tratada como prioridade desde o início do desenvolvimento. As senhas de todos os usuários são armazenadas com `password_hash()` utilizando o algoritmo bcrypt, garantindo que nenhuma senha seja guardada em texto puro. Todas as interações com o banco de dados utilizam *prepared statements*, eliminando vulnerabilidades de SQL injection. O controle de acesso é gerenciado por sessões PHP, impedindo que usuários não autenticados acessem áreas restritas. No cadastro, há validação de CPF e e-mail duplicados para manter a integridade dos dados.

---

## 🎓 Contexto Acadêmico

Este projeto foi desenvolvido como **Trabalho de Conclusão de Curso**, com o objetivo acadêmico de propor uma solução tecnológica aplicada à área da saúde. O desenvolvimento envolveu a aplicação prática de conceitos estudados ao longo do curso, como modelagem relacional de banco de dados, desenvolvimento web full-stack, segurança em aplicações web e experiência do usuário.

A escolha do tema foi motivada pela relevância social do problema: dificuldades no acesso e agendamento de consultas afetam diariamente milhões de brasileiros. O MedClick propõe uma resposta direta a esse cenário, demonstrando como a tecnologia pode ser um instrumento de melhoria real na vida das pessoas.

---

## 👤 Autor

Desenvolvido por **[Igor Marques da Silva e Eduardo Scorpioni]**

Curso: [Analise e Desenvolvimento de Sistemas] · Instituição: [Prof. Dr. Antonio Eufrasio de Toledo] · Ano: 2025

---

<div align="center">

*Projeto desenvolvido para fins acadêmicos. Todos os direitos reservados.*

</div>