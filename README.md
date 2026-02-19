# API SAMP Launcher

## Como fazer o deploy no Railway:

1. Suba todos esses arquivos no GitHub
2. No Railway conecte o repositorio
3. Apos o deploy copie a URL gerada (ex: https://samp-api.railway.app)
4. Substitua "SUA_URL_RAILWAY" nos arquivos abaixo pela URL do Railway:
   - client_config.json
   - generate_files.php
   - news.json

## Endpoints:

- /players.php          → players online (consultado direto no servidor SAMP)
- /client_config.json   → configuracao de update do APK
- /generate_files.php   → lista de arquivos para download
- /news.json            → noticias do launcher
