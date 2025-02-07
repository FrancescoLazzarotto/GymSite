<?php
include 'includes/funzioni.php';
session_start();
$bottone_login = '<a class="loginn" href="login.php">Login</a>';
//$form_ricerca = '';
//$elenco_corsi = '';
$utenti = '';
$listaCorsi = listaCorsi();
$corsi = listaAdmin();
$id_corso = '';
$bottone_utente = '<a class="loginn" href="registrazione.php">Registrati </a>';
if (!isset($_SESSION['login'])) {
  header("Location: logincorsi.php"); // Reindirizza alla pagina di login se l'utente non è loggato
  exit;
}
//Condizione per verificare se l'utente è un amministratore e se la variabile di sessione "amministratore" è impostata a 1 se si, metto il tasto Logout e bottone area admin
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

  <body>
    
  


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
        
     <div id="sfondohome1"> 
      <div class="testo"> Lazzarotto Gym <br> Via Silvio Pellico 10, 10121 Torino 
      <br> <br> <br> <p class="testopiccolo"> Tel. 3392417525   
    <br> <br>
        ORARI <br> LUN-VEN 8-22 SAB 9-19 DOM CHIUSO </p>
       <br>  
       
       <a href="Orari Corsi.pdf" target="_blank"  class="bottone1">Scarica calendario corsi</a>
      </div>  </a>
    </div>
</div>



    <div id="content2">

      
<br> <br> 
  


<!-- Form di ricerca -->
<div id="ricerca-corsi">
    <h2 class="titolovip">Ricerca Corsi</h2>
    <form method="GET" action="corsi.php"> 
        <input type="text" name="parola_chiave" placeholder="Parola chiave" class="campo-ricerca">
        <select name="tipologia" class="menu-a-discesa">
            <option value=""> <h4 class="postdisp"> Seleziona il tipo di corso </h4> </option>
            <option value="Fitness">Fitness</option>
            <option value="Yoga">Yoga</option>
            <option value="Anaerobico">Anaerobico</option>
            <option value="Pilates">Pilates</option>
            <option value="Bodybuilding">Bodybuilding</option>
            <option value="Dancing Fitness">Dancing Fitness</option>
            <option value="CrossFit">CrossFit</option>
            <option value="Arti Marziali">Arti Marziali</option>
            <option value="Funzionale">Funzionale</option>
        </select> 
        <h4 class="postidisp">Posti Disponibili : </h4>
        <input type="checkbox" name="partecipanti" value="1" class="casella-controllo">
        <button type="submit" class="pulsante-cerca">Cerca </button>
    </form>
</div>

<ul class="lista-corsi">
    <!-- Qui verranno inseriti i corsi tramite AJAX -->
</ul>


    



<center>
  <!-- stampo tutti i corsi presenti nel database !-->
 <h1 class="titolovip">Elenco completo dei Corsi</h1> </center>
   <ul class="lista-corsi1">
    <?php
    foreach ($listaCorsi as $corso) {
      echo "<li class='corso1'>";
      echo "<strong class='nome-corso1'> " . $corso['nome'] . "</strong>";
      echo "<p class='tipo1'> TIPOLOGIA CORSO : " . $corso['tipologia'] . "</p>";
      echo "<a class='det1' href='paginacorso.php?id=" . $corso['id_corso'] . "'>Dettagli corso</a> <br>";
      echo "</li>";
    }
    ?>
    </ul>






  </div>

          <footer>
            <a href="#inizio">

<div id="tornasu">

<img src= "assets/immagini/su.png" class="tornasu" alt="freccia per tornare all'inizio della pagina" title="freccia">

</div>

</a>
<div id="social"> <table> <tr> <td>

    <a href="https://www.facebook.com" target="_blank"> <img src="assets/immagini/fb1.png" id="socialimg1" alt="logo facebook" title="contatti">  </a> </td> <td>
    <a href="https://www.instagram.com" target="_blank"> <img src="assets/immagini/ig1.png" id="socialimg2" alt="logo instagram" title="contatti">  </a> </td> <td>
    <a href="https://www.linkedin.com/in/francesco-lazzarotto-a09aa51ba/" target="_blank"> <img src="assets/immagini/ln1.png" id="socialimg3" alt="logo linkedin" title="contatti">  </a> </td> </tr> </table>

  </div>
                &copy; <em> 2020 Lazzarotto Gym - Fitness <br>
                Design and Graphics by </em>  <a href="linkedin.com/in/francesco-lazzarotto-a09aa51ba/"target="_blank" class="cop">Francesco Lazzarotto </a> <br>
              <a href="https://mail.google.com/mail/u/0/#inbox" target="_blank" class="cop1" >  francesco.lazzarotto@edu.unito.it <br>

    </footer>
    <!-- includo jquery per ajax -->
     <script src="https://code.jquery.com/jquery-3.6.0.min.js">

    if (typeof jQuery == 'undefined') {
        document.write('<script src="js/jquery-3.7.1.min.js"><\/script>');
    }
   </script>
   <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
   <script>
$(document).ready(function() {
    var menu = $(".menu");
    var lastScrollTop = 0;
    var scrollPoint = 900; 

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
<script src="js/richieste_ajax.js"></script>
    <script>

        window.addEventListener ("scroll",function(){

            if (window.pageYOffset>300) {
            document.getElementById ("tornasu").style.display= "block";
            }

              else if (window.pageYOffset<300) {
              document.getElementById ("tornasu").style.display= "none";
              }});

             

              




    </script>
  </body>
</html>