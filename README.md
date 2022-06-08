1. Create temp file `auth.json`
2. Fill with `{"github-oauth": {"github.com": "token"}}`
3. Run:
```
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v $(pwd):/var/www/html \
    -w /var/www/html \
    laravelsail/php81-composer:latest \
    composer update --ignore-platform-reqs
```
4. fill .env file
5. sail up -d
6. sail npm i
7. sail npm run dev
