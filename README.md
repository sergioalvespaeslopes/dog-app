# Dog Breeds App

Este projeto é uma aplicação web desenvolvida em PHP e JavaScript que permite ao usuário explorar raças de cachorros, estilizar um nome de pet e persistir essas preferências utilizando cookies.

---

## Funcionalidades

-   **Busca de Raças:** Utiliza a [Dog API](https://dog.ceo/dog-api/) para buscar e exibir uma lista completa de raças de cachorros.
-   **Entrada de Nome:** O usuário pode digitar o nome do seu cachorro.
-   **Estilização Dinâmica:**
    -   Seleção de cor da fonte (até 5 opções).
    -   Seleção de estilo da fonte (até 5 opções do Google Fonts).
-   **Visualização em Tempo Real:** A imagem da raça selecionada é exibida, e o nome do cachorro aparece estilizado sobre ela, com atualizações instantâneas sem recarregamento da página.
-   **Persistência de Dados:** As preferências do usuário (raça, nome, cor, fonte) são salvas em **cookies**, garantindo que as informações permaneçam visíveis ao recarregar a página.
-   **Mensagem de Sucesso:** Exibe uma notificação de sucesso temporária após salvar as preferências.

---

## Tecnologias Utilizadas

-   **Backend:** PHP 8.x
    -   Responsável por:
        -   Consumir a Dog API para carregar a lista inicial de raças.
        -   Gerenciar a persistência das preferências do usuário via cookies.
        -   Servir os dados iniciais e as preferências salvas para o frontend.
-   **Frontend:** HTML5, CSS3 (Bootstrap 5.3), JavaScript ES6+
    -   HTML: Estrutura da página e elementos de formulário.
    -   CSS (com Bootstrap): Estilização e responsividade da interface.
    -   JavaScript:
        -   Preenchimento dinâmico do `select` de raças.
        -   Requisição assíncrona para obter imagens de cachorros da Dog API quando a raça é alterada.
        -   Aplicação de estilos em tempo real (cor e fonte) ao nome do cachorro.
        -   Gerenciamento da mensagem de sucesso na interface.

---

## Como Configurar e Rodar o Projeto Localmente

Para configurar e executar este projeto em sua máquina local, siga os passos abaixo:

### Pré-requisitos

Você precisará de um ambiente de servidor web com suporte a PHP. Recomenda-se o uso de:

-   **XAMPP** (Windows, Linux, macOS)
-   **WAMP Server** (Windows)
-   **MAMP** (macOS)
-   **Laragon** (Windows)

Certifique-se de que o **Apache** e o **PHP** estejam em execução.

### Passos de Configuração

1.  **Clone o Repositório:**
    Abra seu terminal ou prompt de comando e clone este repositório para o diretório de documentos do seu servidor web (ex: `htdocs` no XAMPP, `www` no WAMP/Laragon).

    ```bash
    git clone [https://github.com/sergioalvespaeslopes/dog-app.git](https://github.com/sergioalvespaeslopes/dog-app.git) dog-app
    ```

2.  **Verifique a Estrutura de Arquivos:**
    Após clonar, a estrutura de arquivos do projeto deve ser a seguinte dentro da pasta `dog-app`:

    ```
    /dog-app
    ├── index.php
    ├── config/
    │   └── app_logic.php
    ├── css/
    │   └── style.css
    └── js/
        └── script.js
    ```
    Certifique-se de que todos esses arquivos e pastas existam.

3.  **Configuração do PHP (Opcional, mas Recomendado para Debug):**
    No arquivo `config/app_logic.php`, as linhas abaixo já estão configuradas para exibir erros, o que é útil durante o desenvolvimento:

    ```php
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
    ```
    Para um ambiente de produção, essas configurações devem ser desativadas ou ajustadas para não exibir erros diretamente no navegador.

### Execução do Projeto

1.  **Inicie o Servidor Web:**
    Certifique-se de que seu servidor Apache (via XAMPP, WAMP, etc.) esteja em execução.

2.  **Acesse no Navegador:**
    Abra seu navegador web e acesse o projeto através da seguinte URL:

    ```
    http://localhost/dog-app/
    ```

    Você deverá ver a interface do "Dog Breeds App" carregada, com as raças populadas e a funcionalidade interativa em pleno funcionamento.

---

## Boas Práticas e Considerações

-   **Separação de Preocupações:** A lógica de backend (API calls, cookies) está encapsulada em `config/app_logic.php`, enquanto `index.php` foca na renderização HTML e `js/script.js` cuida da interatividade do frontend.
-   **Injeção de Dados PHP no JS:** Os dados iniciais (raças e preferências salvas) são passados do PHP para o JavaScript via `json_encode` no `index.php`, garantindo que o JS tenha os dados necessários ao carregar a página sem requisições adicionais desnecessárias.
-   **Segurança:** Uso de `htmlspecialchars()` para prevenir ataques XSS (Cross-Site Scripting) ao exibir dados vindos de `$_POST` ou da API.
-   **Tratamento de Erros:** Funções de `fetchApiData` incluem tratamento básico para falhas de requisição ou respostas inválidas da API.
-   **Redirecionamento Pós-POST:** Após salvar as preferências, o PHP realiza um redirecionamento (`header('Location: ...')`) para evitar reenvio do formulário ao recarregar a página.

---
