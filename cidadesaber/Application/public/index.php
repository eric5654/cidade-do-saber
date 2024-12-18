<?php


// Verifique se o usuário deve ser redirecionado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  header("Location: ../views/user/cadastro.php");
  exit();

}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Filas</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap"
    rel="stylesheet">
  <link rel="stylesheet" href="./assets/css/bootstrap.min.css">
  <style>
    body {
      font-family: 'Roboto', sans-serif;
      margin: 0;
      padding: 0;
      background-color: #f4f4f4;
      color: #333;
    }

    .cabecalho {
      background-color: #3d9dd9;
      color: white;
      text-align: center;
      padding: 20px 0;
    }

    .titulo {
      font-size: 2.5em;
      margin: 0;
    }

    main {
      padding: 3rem;
      padding-bottom: 0;
    }

    .saber {
      background-color: white;
      padding: 20px;
      margin-bottom: 20px;
      border-radius: 8px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .saber p {
      font-size: 1.1em;
      line-height: 1.6;
    }

    .matricula {
      background-color: white;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .matricula h3 {
      font-size: 2em;
      color: #3d9dd9;
      margin-bottom: 15px;
    }

    .matricula p {
      font-size: 1.1em;
      line-height: 1.6;
    }

    .matricula .documentos {
      display: flex;
      flex-direction: column;
      align-items: center;
      list-style-type: none;
      padding: 0 50px;
      margin: 30px 0;
      gap: 5px 0;
    }

    .matricula .documentos li {
      text-align: left;
      width: 50%;
    }

    .orgao {
      font-weight: bold;
      color: #3d9dd9;
      font-size: 25px;
      display: flex;
      justify-content: center;
    }

    .matricula .aulas {
      list-style-type: none;
      padding: 0;
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    /* Aula */
    .aula {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 15px;
      padding: 1rem;
      width: 50%;
      border: 1px solid #3333338a;
      border-radius: 5px;
    }

    .aula li {
      font-size: 1.1em;
    }

    .botao {
      display: flex;
      justify-content: center;
    }

    button {
      background-color: #3d9dd9;
      color: white;
      border: none;
      margin: 2rem;
      padding: 20px 40px;
      cursor: pointer;
      font-size: 1em;
      border-radius: 5px;
      transition: background-color 0.3s, transform 0.3s;
    }

    button:hover {
      background-color: #3173a3;
      transform: scale(1.2);
    }

    footer {
      background-color: #3173a3;
      color: white;
      padding: 30px 0;
      text-align: center;
    }

    footer nav ul {
      list-style-type: none;
      padding: 0;
      margin: 0;
      display: flex;
      justify-content: center;
      gap: 20px;
    }

    footer nav ul li {
      display: inline-block;
    }

    footer nav ul li a {
      color: white;
      text-decoration: none;
      font-size: 1.1em;
      transition: color 0.3s;
    }

    footer nav ul li a:hover {
      color: #3d9dd9;
    }

    .social-media {
      margin-top: 20px;
    }

    .social-media a {
      margin: 0 10px;
      text-decoration: none;
      color: white;
    }

    .social-media a img {
      width: 30px;
      height: 30px;
      transition: transform 0.3s;
    }

    .social-media a img:hover {
      transform: scale(1.1);
    }

    .newsletter {
      margin-top: 30px;
    }

    .newsletter input[type="email"] {
      padding: 10px;
      width: 250px;
      font-size: 1em;
      border: 2px solid #ddd;
      border-radius: 5px;
      margin-right: 10px;
    }

    .newsletter button {
      padding: 10px 20px;
      background-color: #3d9dd9;
      color: white;
      border: none;
      font-size: 1em;
      border-radius: 5px;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    .newsletter button:hover {
      background-color: #3173a3;
    }


    /* Responsividade */
    @media (max-width: 768px) {
      .cabecalho {
        padding: 15px 0;
      }

      .titulo {
        font-size: 2em;
      }

      .matricula h3 {
        font-size: 1.6em;
      }

      .matricula ul li {
        font-size: 1em;
      }

      .aula button {
        width: 100%;
        padding: 12px;
      }
    }
  </style>
</head>

<body>
  <header class="cabecalho">
    <h1 class="titulo">Cidade do Saber</h1>
  </header>
  <main>
    <section class="saber">
      <p>A Cidade do Saber é um complexo considerado um dos maiores centros de inclusão social da América Latina,
        com um espaço de 22 mil metros quadrados...</p>
    </section>
    <section class="matricula">
      <h3>Matrículas</h3>
      <p>No local, seguindo o calendário de matrículas divulgado com antecedência no Portal da Secult e nesta página...
      </p>
      <ul class="documentos">
        <li>Comprovante de residência atualizado</li>
        <li>Registro Geral (RG)</li>
        <li>Cadastro de Pessoa Física (CPF)</li>
        <li>Atestado de matrícula (para menores de 18 anos)</li>
        <li>RG do responsável legal (para menores de 18 anos)</li>
        <li>CPF do responsável legal (para menores de 18 anos)</li>
        <li>Atestado dermatológico (apenas para matrículas com uso da piscina)</li>
        <li>Atestado urológico (apenas para matrículas com uso da piscina, para alunos homens maiores de 18 anos)</li>
        <li>Atestado ginecológico (apenas para matrículas com uso da piscina, para alunas mulheres maiores de 18 anos)
        </li>
      </ul>
      <p>Observação: matrículas de menores devem ser feitas pelo responsável legal.</p>
      <p>São desenvolvidas no espaço, cursos culturais e esportivos para crianças...</p>
      <h4 class="orgao">SECULT:</h4>
      <ul class="aulas">
        <li class="aula">
          <span>Bateria: a partir de 12 anos</span>
        </li>

        <li class="aula">
          <span>Teclado: 12 a 40 anos</span>
        </li>

        <li class="aula">
          <span>Violão: 12 a 40 anos</span>
        </li>

        <li class="aula">
          <span>Canto: a partir de 10 anos</span>
        </li>

        <li class="aula">
          <span>Teatro: 12 a 17 anos</span>
        </li>

        <li class="aula">
          <span>Flauta: 7 a 30 anos</span>
        </li>

        <li class="aula">
          <span>Violino: 7 a 30 anos</span>
        </li>

        <li class="aula">
          <span>Viola: 7 a 30 anos</span>
        </li>

        <li class="aula">
          <span>Trompa: 7 a 30 anos</span>
        </li>

        <li class="aula">
          <span>Trompete: 7 a 30 anos</span>
        </li>

        <li class="aula">
          <span>Saxofone: 7 a 30 anos</span>
        </li>

        <li class="aula">
          <span>Contrabaixo: 7 a 30 anos</span>
        </li>

        <li class="aula">
          <span>Violoncelo: 7 a 30 anos</span>
        </li>

        <li class="aula">
          <span>Ballet: a partir de 6 anos</span>
        </li>

        <li class="aula">
          <span>Ballet Que Dança: a partir de 15 anos</span>
        </li>

        <li class="aula">
          <span>Ballet Fitness: a partir de 17 anos</span>
        </li>

        <li class="aula">
          <span>Dança Contemporânea: a partir de 6 anos</span>
        </li>

        <li class="aula">
          <span>Dança de Salão: a partir de 15 anos</span>
        </li>

        <li class="aula">
          <span>Dança do ventre: a partir de 15 anos</span>
        </li>

        <li class="aula">
          <span>Pilates: a partir de 17 anos</span>
        </li>

        <li class="aula">
          <span>Zumba: a partir de 15 anos</span>
        </li>

        <li class="aula">
          <span>Capoeira para mulheres: a partir de 16 anos</span>
        </li>
      </ul>

      <h4 class="orgao">SEJUV:</h4>
      <ul class="aulas">
        <li class="aula">
          <span>Teakwondo: 8 a 17 anos</span>
        </li>

        <li class="aula">
          <span>Futsal: 6 a 17 anos</span>
        </li>

        <li class="aula">
          <span>Karatê: 6 a 17 anos</span>
        </li>

        <li class="aula">
          <span>Natação: a partir de 8 anos</span>
        </li>

        <li class="aula">
          <span>Hidroginástica: a partir de 18 anos</span>
        </li>

        <li class="aula">
          <span>Capoeira: a partir de 6 anos</span>
        </li>
      </ul>

      <h4 class="orgao">SPEAK OUT:</h4>
      <ul class="aulas">
        <li class="aula">
          <span>Inglês: a partir de 6 anos.</span>
        </li>
      </ul>
    </section>

    <nav class="botao">
    <button onclick="window.location.href='../views/user/cadastro.php'">Inscreva-se</button>

    </nav>
  </main>
  <footer>
    <nav>
      <ul>
        <li><a href="#">Sobre</a></li>
        <li><a href="#">Contato</a></li>
        <li><a href="#">Ajuda</a></li>
      </ul>
    </nav>
    <div class="social-media">
      <a href="#"><img src="img/facebook.png" alt="Facebook"></a>
      <a href="#"><img src="img/twitter.png" alt="Twitter"></a>
      <a href="#"><img src="img/instagram.png" alt="Instagram"></a>
    </div>
  </footer>
  <script src="assets/js/bootstrap.bundle.min.js"></script>
</body>

</html>