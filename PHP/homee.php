<?php
session_start();
include ('funzioni.php');
$bottone_login = '<a class="loginn" href="login.php">Login</a>';
$form_ricerca = '';
$elenco_corsi = '';
$utenti='';
$bottone_utente = '<a class="" href="registrazione.php">Registrati </a>';

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
    <meta name=“author” content=“FrancescoLazzarotto“ />
    <link rel="shortcut icon" href="immagini/logo.jpg" type="image/jpg">
    <title> Esame Lazzarotto Francesco (Sito Palestra) </title>

    <link rel="stylesheet" href="style.css" type="text/css">
 

  </head>

  <body>
  
<a name="inizio"></a>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    var menu = $(".menu");
    var lastScrollTop = 0;
    var scrollPoint = 875; // Altezza dello scroll in pixel a cui cambiare l'opacità

    $(window).scroll(function() {
        var st = $(this).scrollTop();

        if (st > lastScrollTop) {
            // Scorrimento verso il basso
            if (st >= scrollPoint) {
                menu.css('background', ' #333333');
            }
        } else {
            // Scorrimento verso l'alto
            if (st < scrollPoint) {
                menu.css('background', 'transparent');
            }
        }

        lastScrollTop = st;
    });
});
</script>



<nav id="navigation" class="menu">
    <ul>
        <li><a href="homee.php">Home</a></li>
        <li><a href="corsi.php">Corsi</a></li>
        <li><a href="benefici.php">Benefici</a></li>
        <li><a href="contatti.php">Contatti</a></li>
      <?php echo $utenti; ?>
    <li id="logoutpos"><?php echo $bottone_login; ?></li>
    <li><?php echo $bottone_utente; ?></li>
  </ul>
</nav>


  </nav>


    <div id="content1">
<div class="parallax-container">
        
     <div id="sfondohome"> <div class="testo"> Eleva il tuo allenamento a un livello superiore  <br>  <a href="corsi.php" class="bottone">Scopri la nostra palestra</a></div> </div>
</div>

              <h2 class="titolovip"> Vogliamo farti sentire un vip! </h2>
              <p class="corsi1"> Ecco alcuni vantaggi e particolarità che otterrai dopo esserti iscritto alla nostra palestra! </p>

      <center>
        <table id="benefit">
          <tr>

            <td> <img src="immagini/l1.png" class="benefits" alt="logo benefici" title="logo" > </td>

            <td> Otterrai una scheda personale per tracciare i tuoi progressi! <br>
             E un braccialetto elettronico per accedere alla palestra! </td>

             <td><img src="immagini/l2.png" class="benefits" alt="logo benefici" title="logo"></td>
             <td> Avrai sempre un istruttore pronto ad aiutarti e a risolvere i tuoi problemi! </td>

           </tr>
        <tr>

            <td><img src="immagini/l3.png" class="benefits" alt="logo benefici" title="logo"></td>
            <td> Sconti dedicati a tutte le fascie d'età, la palestra è per tutti! </td>
            <td><img src="immagini/l4.png" class="benefits" alt="logo benefici" title="logo"></td>
            <td> Servizi aggiunti, prova di alcuni corsi, e tante altre cose da scoprire! </td>

          </tr>


 </table> </center>



 <div class="maps">
                  <h2 class="titolovip2"> Quattro sale diverse, una per ogni necessità! </h2>
   <center>

      <table class="tabellaarea"> <tr> <td> <img src="immagini/cardio.jpg" class="area" alt="Cardio tapis roulante" title="area cardio" style=" border-radius: 15px; 
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);"> </td>
              <td>

                  <h2 id="titoloarea1"> Sala Cardio Fitness </h2>
                  <hr class="divisore">
                  <p class="corsiarea"> Le migliori attrezzature per il tuo workour! </p>

            </td>
          </tr>
          <tr>
             <td>

                 <h2 id="titoloarea2"> Sala Pesi </h2>
                 <hr class="divisore">
                 <p class="corsiarea"> Attrezzi e pesi di ottima qualità, adatti a tutte le persone!</p>

            </td>
              <td>

                <img src="immagini/pesi.jpg" class="area" alt="Cardio tapis roulante" title="area cardio" style=" border-radius: 15px; 
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);">

          </td>
        </tr>
           <tr>
             <td>

               <img src="immagini/iso2.jpg" class="area" alt="Cardio tapis roulante" title="area cardio" style=" border-radius: 15px; 
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);">

            </td>
              <td>

                 <h2 id="titoloarea3"> Sala Macchinari isotonici </h2>
                   <hr class="divisore">
                 <p class="corsiarea"> Macchinari di ultima generazione, <br> perfetti per allenare parte alta e bassa del corpo!  </p>

           </td>
         </tr>
            <tr>
              <td>

               <h2 id="titoloarea4"> Sala Funzionale </h2>

               <hr class="divisore">

               <p class="corsiarea"> L'allenamento più completo <br>
                 Allena tutto il corpo in un momento solo! </p>

         </td>
            <td>

              <img src="immagini/funz.jpg" class="area" alt="Cardio tapis roulante" title="area cardio" style=" border-radius: 15px;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);">

          </td>
        </tr>
      </table>
    </center>

              <!-- <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d837.9091126077964!2d7.68170082548788!3d45.05782076080746!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47886d42d9a34551%3A0x382e94c64ecdf69b!2sVia%20Silvio%20Pellico%2C%20Torino%20TO!5e0!3m2!1sit!2sit!4v1609709529434!5m2!1sit!2sit" width="1155" height="500" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
              --> <div style="width: 100%"><iframe width="100%" height="600" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?width=100%25&amp;height=600&amp;hl=en&amp;q=Via%20Silvio%20Pellico%2012%20+(Iframe%20maps%20sito)&amp;t=&amp;z=14&amp;ie=UTF8&amp;iwloc=B&amp;output=embed"><a href="https://www.gps.ie/">gps trackers</a></iframe></div> </div>
</div>
              
          <footer>
            <a href="#inizio">
                <div id="tornasu">

                  <img src= "immagini/su.png" class="tornasu" alt="freccia per tornare all'inizio della pagina" title="freccia">
              </div>
            </a>
            


            <div id="social"> <table> <tr> <td>

                    <a href="https://www.facebook.com" target="_blank"> <img src="immagini/fb1.png" id="socialimg1" alt="logo facebook" title="contatti">  </a> </td> <td>
                    <a href="https://www.instagram.com" target="_blank"> <img src="immagini/ig1.png" id="socialimg2" alt="logo instagram" title="contatti">  </a> </td> <td>
                    <a href="https://www.linkedin.com/in/francesco-lazzarotto-a09aa51ba/" target="_blank"> <img src="immagini/ln1.png" id="socialimg3" alt="logo linkedin" title="contatti">  </a> </td> </tr> </table>
                  </div>

                &copy; <em> 2020 Lazzarotto Gym - Fitness <br>
                Design and Graphics by </em> <a href="linkedin.com/in/francesco-lazzarotto-a09aa51ba/"target="_blank" class="cop">Francesco Lazzarotto </a> <br>
              
<a href="mailto:francesco.lazzarotto@edu.unito.it" id="myEmail" class="cop1">francesco.lazzarotto@edu.unito.it</a>




        
    </footer>


    <script type="text/javascript">

        window.addEventListener ("scroll",function(){  // imposto la funzione

            if (window.pageYOffset>200) { // Ciclo if ( se lo scroll supera i 200px verticalmente il documento riceve l'elemento attraverso l'id)
            document.getElementById ("tornasu").style.display= "block"; //
            }

              else if (window.pageYOffset<200) { // Sennò se lo scroll è inferiore ai 200 l'elemento non viene visualizzato nel display
              document.getElementById ("tornasu").style.display= "none";
            }});



    </script>
  </body>
</html>
