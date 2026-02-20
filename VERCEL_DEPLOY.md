# Deploy YOURLS no Vercel

Esta adaptação permite executar YOURLS (PHP + MySQL) no Vercel usando o runtime [vercel-php](https://github.com/vercel-community/php).

## Pré-requisitos

1. **Banco MySQL** – O Vercel não oferece MySQL. Use um destes serviços:
   - [PlanetScale](https://planetscale.com) (MySQL compatível, tem free tier)
   - [Railway](https://railway.app) (MySQL)
   - [Aiven](https://aiven.io) (MySQL)
   - Qualquer provedor MySQL (DigitalOcean, etc.)

2. **Conta no Vercel** – [vercel.com](https://vercel.com)

## Configuração

### 1. Banco de dados

Crie um banco MySQL e guarde:
- Host
- Usuário
- Senha
- Nome do banco

### 2. Variáveis de ambiente no Vercel

Em **Project Settings > Environment Variables**, configure:

| Variável | Obrigatório | Descrição |
|----------|-------------|-----------|
| `YOURLS_DB_HOST` | Sim | Host do MySQL (ex: `us-east.connect.psdb.cloud` para PlanetScale) |
| `YOURLS_DB_USER` | Sim | Usuário do banco |
| `YOURLS_DB_PASS` | Sim | Senha do banco |
| `YOURLS_DB_NAME` | Sim | Nome do banco |
| `YOURLS_SITE` | Recomendado | URL do site (ex: `https://seu-projeto.vercel.app`) |
| `YOURLS_ADMIN_USER` | Recomendado | Usuário admin (padrão: `admin`) |
| `YOURLS_ADMIN_PASS` | Recomendado | Senha admin (padrão: `changeme`) |
| `YOURLS_COOKIEKEY` | Recomendado | Chave de cookies (gere em https://yourls.org/cookie) |

### 3. Deploy

1. Conecte o repositório ao Vercel
2. Configure as variáveis
3. Faça o deploy

### 4. Instalação

Na primeira execução, acesse:

```
https://seu-projeto.vercel.app/admin/
```

O YOURLS vai redirecionar para `/admin/install.php` e concluir a configuração do banco.

## Nota: PlanetScale

O PlanetScale usa MySQL via proxy. Use o **host de conexão** fornecido pelo dashboard (ex: `xxx.connect.psdb.cloud`) e garanta que o banco aceita conexões externas.

## Limitações

- **Filesystem read-only**: Plugins que graham arquivos podem ter restrições.
- **Sessões**: PHP sessions em serverless podem ter limites.
- **Cold start**: Primeira requisição pode levar ~250 ms.

## Estrutura técnica

- `api/index.php` – Router que encaminha todas as requisições
- `user/config-vercel.php` – Config que lê das variáveis de ambiente
- `vercel.json` – Configuração do projeto e runtime PHP
