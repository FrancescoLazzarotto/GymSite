<?php
include 'includes/funzioni.php';
session_start();
/*
error_reporting(E_ALL);
ini_set('display_errors', 1);
*/

$bottone_login = '<a class="loginn" href="login.php">Login</a>';
$bottone_utente = '<a class="" href="registrazione.php">Registrati</a>';
$bottone_login_registrazione = '<a class="loginn" href="login.php">Login</a>';
$utenti ='';


if (isset($_SESSION["login"])) { //se un utente standard loggato rimando alla homee
  header("Location: homee.php"); //se amministratore rimando ad area admin 
} else if (isset($_SESSION["amministratore"]) && $_SESSION["amministratore"] == "1") {
  header("Location: areaadmin.php");
}


// inizializzo variabile per errori 
$testo_errore = "";

//se premuto il tasto per registrarsi e la richiesta è post
if (isset($_POST["registrazione"]) && $_SERVER["REQUEST_METHOD"] == "POST") { 
  //prendo i dati dal form e li metto nelle variabili 
  $nome = $_POST["name"];
  $cognome = $_POST["surname"];
  $username = $_POST["username"];
  $password = $_POST["password"];
  $mail = $_POST["mail"];
  $data = $_POST["data"];

  //richiamo la funzione nuovoUtente con i parametri 
  $risultato = nuovoUtente($nome, $cognome, $username, $password, $mail, $data);

  if ($risultato == true) { //gestisco l'iscrizione
    $testo_errore = "<strong><p class='testo-errore'>Ti sei registrato con successo, effettua il login <a href='login.php'> qua </a> </p></strong>";
  } else if ($risultato == false) {
      $testo_errore = "<strong><p class='testo-errore'>Esiste già un utente con questo username o con questa email</p></strong>";
    } else {
      $testo_errore = "<strong><p class='testo-errore'>Dati incompleti o errati</strong></p>";
    }
  
}

?>
<!DOCTYPE html>
<html lang=it dir="ltr">

<head>
  <meta charset="utf-8">
  <meta name="keywords" content="Web design, grafica, html, css" />
  <meta name="description" content="sito web di Francesco Lazzarotto" />
  <meta name=“author" content=“Francesco Lazzarotto" />
  <link rel="shortcut icon" href="immagini/logo.jpg" type="image/jpg">
  <title> Esame Lazzarotto Francesco (Sito Palestra) </title>

  <link rel="stylesheet" href="../assets/style.css" type="text/css">

</head>

<body>

  <a name="inizio"></a>

  <nav id="navigation" class="menu1">
    <table>
      <tr>

        <br> <td class="spazio-vuoto"></td>
        <td>
          <ul>
               <li><a href="index.php">Home </a></li>
                        <li><a href="corsi.php"> Corsi</a></li>
                      <li> <a href="benefici.php"> Benefici </a> </li>   
                        <li><a href="contatti.php">Contatti </a></li>
            <?php echo $utenti ?>
              <li id="logoutpos">
                <?php echo $bottone_login ?>
              </li>
              <li>
                <?php echo $bottone_utente ?>
              </li>
          </ul>
        </td>
      </tr>
    </table>

  </nav>



  <div id="content1"> <br> <br>
    

    <main id="log-main">
      <br> <br>
      <center>
        <!-- form di registrazione -->
 <div id="login-form">
  <h2 class="titolovip">Registrati!</h2>
  <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"> <!-- rimanda i dati a se stessa per elaborarli, più sicuro rispetto all'action normale !-->
     <p><label for="name">Nome:
     <input type="text" placeholder="Inserisci il tuo nome" name="name" required></label></p>
     <p><label for="surname">Cognome:
	 <input type="text" placeholder="Inserisci il tuo cognome" name="surname" required></label></p>
     <p><label for="username">Username:
	 <input type="text" placeholder="Username" name="username" required></label></p>
	 <p><label for="password">Password:
	 <input type="password" placeholder="Password" name="password" required></label></p>
     <p><label for="mail">Mail:
     <input type="email" placeholder="Inserisci la tua mail" name="mail" required></label></p>
	 <p><label for="data">Data di nascita:
     <input type="date" name="data" required></label></p>
	 <input type="submit" name="registrazione" value="Registrati">
    </form>
    <?php echo $testo_errore ?>
    <br> <br>
	<p id="reg">Hai gia un account? Effettua il <a href="login.php" class="linkstilizzato1">Login!</a></p>
	</div>
   </center>
    


</div>

    
  <footer>
    <a href="#inizio">

      <div id="tornasu">

        <img src="assets/immagini/su.png" class="tornasu" alt="freccia per tornare all'inizio della pagina" title="freccia">

      </div>

    </a>
    <div id="social">
      <table>
        <tr>
          <td>
 <a href="https://www.facebook.com" target="_blank"> <img src="../assets/immagini/fb1.png" id="socialimg1"
                                alt="logo facebook" title="contatti"> </a>
                    </td>
                    <td>
                        <a href="https://www.instagram.com" target="_blank"> <img src="../assets/immagini/ig1.png" id="socialimg2"
                                alt="logo instagram" title="contatti"> </a>
                    </td>
                    <td>
                        <a href="https://www.linkedin.com/in/francesco-lazzarotto-a09aa51ba/" target="_blank"> <img
                                src="../assets/immagini/ln1.png" id="socialimg3" alt="logo linkedin" title="contatti"> </a>
                    </td>
        </tr>
      </table>

    </div>
    &copy; <em> 2020 Lazzarotto Gym - Fitness <br>
      Design and Graphics by </em> <a href="linkedin.com/in/francesco-lazzarotto-a09aa51ba/" target="_blank"
      class="cop">Francesco Lazzarotto </a> <br>
    <a href="https://mail.google.com/mail/u/0/#inbox" target="_blank" class="cop1"> francesco.lazzarotto@edu.unito.it
      <br>

  </footer>
  <script>







    window.addEventListener("scroll", function () {

      if (window.pageYOffset > 300) {
        document.getElementById("tornasu").style.display = "block";
      }

      else if (window.pageYOffset < 300) {
        document.getElementById("tornasu").style.display = "none";
      }

    });

  </script>
</body>

</html>