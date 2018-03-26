# Instalação

- Clone do repositório.
- Instalação dos pacotes do composer, `composer install --no-dev`.
- Criação do arquivo de configurações na pasta `config`.
- Criação do banco de dados com o comando `bin/console db:database:create`. 
- Criação do schema no banco com o comando `bin/console db:schema:create`.


# Execução

Para execução da API em ambiente de dev, `php -S 0.0.0.0:9000 -t web/`.

Para visualização dos comandos no terminal, `bin/console`.

# Outros comandos

Para verificar se o código está no padrão PSR-2, considerar os comandos abaixo:

- `composer install --dev`.
- `composer run psr2`.