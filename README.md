# Api para realização de cadastro de usuários do tipo Comum e Lojista e efetuar transações.

Tem o objeto de demonstrar o funcionamento da api em RESTFul com consulta de serviço externo para concluir a transação
e consulta de serviço externo para enviar a notificação sendo que caso ao enviar a notificação o serviço esteja 
com inconsistencia será enviado para uma fila que tentara enviar a notificação novamente.


### Tecnologias utilizadas:
- PHP na versão 7.4.
- Framework Lumen na versão 8.3.
- Banco de dados MySQL na versão 8.0.
- Docker.
- Docker-compose.

### Requisitos para rodar o projeto na máquina:
- Make.
- Docker.
- Docker-compose

### Instruções de Uso:
- Executar o comando **make start** para iniciar o serviço.
- Executar o comando **make test** para executar os testes.
- Executar o comando **make stop** para parar a execução do serviço.

Após a conclusão de todo o processo, o projeto vai está rodando em http://localhost:8040.

### Observações:
- Clique [aqui](https://github.com/jonasnsilva/transacao/blob/master/swagger/swagger.yaml) para acessar a documentação das api's com swagger: 



