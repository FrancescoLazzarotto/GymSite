<?php
session_start();
include 'includes/funzioni.php';
/* require '../includes/connessione.php'; */


$bottone_login = '<a class="loginn" href="login.php">Login</a>';
$bottone_utente = '<a class="" href="registrazione.php">Registrati</a>';
$utenti = '';


//controllo per verificare se l'utente é amministratore
if (isset($_SESSION["amministratore"]) && $_SESSION["amministratore"] == 1) {
  $bottone_login = '<form action="login.php" method="post"><input type="submit" id="loginn" name="logout" value="Logout"></form>';
  $utenti = '<li class=""><a href="areaadmin.php">Area Admin</a></li>';
  $bottone_utente = '';

  //se è un utente loggato sostituisco il bottone di login con quello di logout e inserisco il bottone per la gestione area personale
} elseif (isset($_SESSION["login"])) {
  $username = $_SESSION['login'];
  $tipo = tipoUtente($username); //richiamo la funzione tipo utente e inserisco il valore in una variabile
  $bottone_login = '<form action="login.php" method="post"><input type="submit" id="logout" name="logout" value="Logout"> </form>';
  if ($tipo == 'reg') { //se il valore che la funzione mi ha restituito e di utente standard genera il bottone area privata
    $utenti = '<li class=""><a href="utenti.php">Area Privata</a></li>';
    $bottone_utente = ''; //tolgo bottone registrati


  } else {
    $utenti = '<li class=""><a href="areaadmin.php">Area Admin</a></li>';
    $bottone_utente = '';

  }
  //nel caso venga cliccato logout distruggo la sessione e genero nuovamente il tasto login invece che log out 
  if (isset($_POST['logout']) == true) {  
    $bottone_login = '<a id="loginn" href="login.php">Login</a>';
    $utenti = '';
    session_destroy();
  }
}

?>
<!DOCTYPE html>
<html lang=it dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta name="keywords" content= "Web design, grafica, html, css" />
    <meta name="description" content="sito web di Francesco Lazzarotto" />
    <meta name=“author" content=“Francesco Lazzarotto" />
    <link rel="shortcut icon" href="immagini/logo.jpg" type="image/jpg">
   
    <title> Esame Lazzarotto Francesco (Sito Palestra) </title>

    <link rel="stylesheet" href="../assets/style.css" type="text/css">

  </head>
  <style>
    body {
      font-family: Arial, sans-serif;
    }

    .benefici-container {
      display: flex;
   
      margin: 80px;
      padding-bottom: 0;
      background-color: #fff;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      border-radius: 10px;
    }

    .benefici-text {
      flex: 1;
      padding: 20px;
      padding-left: 100px;
    }
     .benefici-text1 {
      flex: 1;
      padding: 20px;
      padding-left: 10px;
    }

    .benefici-image-right {
     margin-left: 200px;
     
      height: 600px;
      width: 600px;
      
      box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.2); 
    /* Bordi arrotondati */
    transition: transform 0.2s ease; 
    }

    .benefici-image-right:hover {
      border-radius: 5px;
    }
   
    .benefici-image-left {
    
     margin-right: 100px;
      height: 600px;
      width: 600px;
      max-width: ;
      box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.2); 
    transition: transform 0.2s ease;

    }
    .benefici-image-left:hover {
      border-radius: 5px; 
    }

    .descrizione {
      font-size: 28px;
      font-weight: bold;
      color: #0070cc;
    }
    
    .descrizione1 {
      font-size: 28px;
      font-weight: bold;
      color: #0070cc;
      padding-right: 30px;
      text-align : left;
font-family : Arial black;
font-size : 30px;

text-transform : uppercase;

    }

    .corsi {
      font-size: 16px;
      line-height: 1.4;
      color: #333;
      padding-left: 30px;
    }
      .corsi2 {
      font-size: 16px;
      line-height: 1.6;
      color: #333;
      padding-left: 3px;
    }

    .titolicorsi {
      font-size: 20px;
      font-weight: bold;
      color: #0070cc;
      margin-top: 20px;
    }

    .benefici {
      font-size: 16px;
      line-height: 1.6;
      color: #333;
    }

    #cit {
      font-size: 14px;
      margin-top: 20px;
      color: #777;
    }

    #pt {
      text-decoration: none;
      color: #0070cc;
    }
    </style>


  <body>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    var menu = $(".menu");
    var lastScrollTop = 0;
    var scrollPoint = 875;

    $(window).scroll(function() {
        var st = $(this).scrollTop();

        if (st > lastScrollTop) {
            
            if (st >= scrollPoint) {
                menu.css('background', '#222');
            }
        } else {
            
            if (st < scrollPoint) {
                menu.css('background', 'transparent');
            }
        }

        lastScrollTop = st;
    });
});
</script>

<a name="inizio"></a>

<nav id="navigation" class="menu">
  <table> <tr>
    
    <br>
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

<div class="parallax-container">
        
     <div id="sfondohome4"> 
      <div class="testo2"> 
        
   Ma quali sono i benefici della palestra?




    </div>
       
       
      </div>  
    </div>
  <div class="benefici-container">
  <div class="benefici-text">
    <h1 class="descrizione">I benefici della palestra e del Fitness</h1>
    <p class="corsi"> 
     Il termine "Body Building" significa letteralmente "costruzione del corpo" ed è uno
      sport individuale di tipo anaerobico. <br>  <br> Tuttavia, oggi uno dei luoghi più comuni  
      legati alla tradizione è considerare questa disciplina come un mezzo per esibire il proprio
       corpo in modo narcisistico. <br> <br>  Al contrario, chi frequenta la palestra ha obiettivi specifici 
       per migliorare la propria forma fisica. <br> <br>  Questa attività non solo aumenta la massa muscolare
        e migliora il rapporto tra massa magra e grassa, ma contribuisce anche a rafforzare i vari
         distretti muscolari, migliorare la postura e bilanciare eventuali squilibri muscolari. 
    </p>
  </div>
  <img class="benefici-image-right" src="../assets/immagini/ft1.jpg" alt="Immagine destra">

<br> 
</div>
<div class="benefici-container">
  <img class="benefici-image-left" src="assets/immagini/x2.jpg" alt="">
<div class="benefici-text1">

<h1 class="descrizione1">In conclusione quindi..</h1>

<p class="corsi2">
 Oggi, molti sport di squadra includono allenamenti con pesi 
 per sviluppare forza, aumentare la massa muscolare e migliorare
  il tono muscolare. <br> <br> Questa pratica è una tattica consolidata negli
   Stati Uniti, dove atleti e squadre sfruttano da anni i benefici 
   di un approccio bilanciato che combina l'allenamento con i pesi all'allenamento aerobico. <br> <br>

L'allenamento aerobico con i pesi può offrire notevoli vantaggi per la salute, 
ma è importante introdurre questa modalità di allenamento con cautela, 
specialmente per coloro che sono alle prime armi. <br> Un approccio graduale 
e personalizzato è essenziale per evitare lesioni e ottenere i migliori risultati. <br> <br>

In sintesi, uno stile di vita attivo che include uno sport ben pianificato
 e praticato con impegno può notevolmente migliorare la qualità della vita.
</p>

<h3 id="cit">A cura di: <a href="https://www.facebook.com/fabriziobuttipersonaltrainer1" id="pt">Fabrizio Butti</a> (My personal Trainer)</h3>
</div> </div>



    
    





          <footer>

          <a href="#inizio">

        <div id="tornasu">

        <img src= "assets/immagini/su.png" class="tornasu" title="freccia" alt="freccia per tornare all'inizio della pagina">

        </div>

        </a>
        <div id="social">

           <table> <tr> <td>

            <a href="https://www.facebook.com" target="_blank"> <img src="assets/immagini/fb1.png" id="socialimg1" alt="logo facebook" title="contatti">  </a> </td> <td>
            <a href="https://www.instagram.com" target="_blank"> <img src="assets/immagini/ig1.png" id="socialimg2" alt="logo instagram" title="contatti">  </a> </td> <td>
            <a href="https://www.linkedin.com/in/francesco-lazzarotto-a09aa51ba/" target="_blank"> <img src="assets/immagini/ln1.png" id="socialimg3" alt="logo linkedin" title="contatti">  </a> </td>

          </tr> </table>

          </div>
                &copy; <em> 2020 Lazzarotto Gym - Fitness <br>
                Design and Graphics by :  </em> <a href="linkedin.com/in/francesco-lazzarotto-a09aa51ba/"target="_blank" class="cop">Francesco Lazzarotto </a> <br>
              <a href="https://mail.google.com/mail/u/0/#inbox" target="_blank" class="cop1" >  francesco.lazzarotto@edu.unito.it <br>
        

</footer>
    <script>

        window.addEventListener ("scroll",function(){

            if (window.pageYOffset>300) {
            document.getElementById ("tornasu").style.display= "block";
            }

              else if (window.pageYOffset<300) {
              document.getElementById ("tornasu").style.display= "none";
              }
            });

    </script>
  </body>
</html>
