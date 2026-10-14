# FalaQ — Sprint 06 implementada

O projeto original foi preservado, com alterações integradas para os Tickets #009 a #012.

## Para executar

1. Instale PHP e Composer compatíveis com `composer.json`.
2. Na raiz do projeto: `composer install`.
3. Crie `.env` usando `.env.example` e configure o banco de dados.
4. Execute `php artisan key:generate`.
5. Execute `php artisan migrate`.
6. Execute `php artisan test --filter=UpvotePerguntaTest`.
7. Execute `php artisan serve` e acesse a aplicação.

A pasta `vendor` e o arquivo `.env` não fazem parte do ZIP original e não foram inventados ou incluídos.

## Implementado

- Pivot `pergunta_user` com unicidade (`pergunta_id`, `user_id`) e exclusão em cascata.
- Relacionamentos Eloquent `votos()` e `perguntasVotadas()`.
- POST autenticado `perguntas.votar`, com toggle e feedback.
- Perguntas filtradas por evento e ordenadas por quantidade de votos.
- Interface com contador e estado do voto do usuário.
- Testes automatizados de voto, remoção e ranking.

## Validação

A validação de sintaxe dos arquivos PHP pode ser feita com `php -l`. A execução dos testes completos requer dependências instaladas e banco de dados configurado.

Outros itens incompletos de sprints anteriores (por exemplo, regras TODO em `StorePerguntaRequest`) foram preservados e não fazem parte da Sprint 06.
