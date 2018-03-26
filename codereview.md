### Code review

- Aplicação das rotas corretamente de acordo com o padrão Rest.
- Implementação de Entidades ou Services e encapsulamento das regras de negócio neles para a consulta ou cadastramento das tasks, regra de negócio não pode ser colocada no controller.
- Implementação de typehint e return types como boas práticas.
- Singleton usado em conexão com banco de dados, este padrão não está mais em uso e seu uso não é recomendado.
- `Symfony\Component\HttpFoundation\Request` não usado no `TaskController`, remover a menção a ele ou usar ele no lugar de `php://input` em `createAction`, sendo recomendável a primeira opção.
- `TaskController:51`: Não é necessário `else` neste contexto.
- Docblock sempre é bem vindo.
- Não é necessário `TRUE` e `NULL` em caixa alta.
- Códigos de resposta HTTP está hardcoded, isto é contra indicado.
- A Task tem `description`, mas está sendo usado `title` no controller,
o que pode deixar confuso, sugiro alterar para `description` para ficar tudo uniforme.
- Existe um atributo `isDone` e métodos relacionados em Task que não está sendo usado, melhor retirar ou implementar se estiver na história da sprint.
- Executar PHP CodeSniffer para identificar quebra aos padrões PSR-2 e corrigi-las.

### Notas

- Não acho que `422 UNPROCESSABLE ENTITY` seja o código correto,
sendo `400 BAD REQUEST` mais aceitável por conta da requisição não
estar de acordo com as regras.
- Recomendo usar Presenter para formatar a resposta da API.

### PSR 2
- `TaskController:12`: chaves na linha de baixo.
- `TaskController:14`: chaves na linha de baixo.
- `TaskController:16`: Espaço entre `,` e a chamada.


