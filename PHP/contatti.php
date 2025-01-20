<?php
session_start();
include('funzioni.php');
$bottone_login = '<a class="loginn" href="login.php">Login</a>';
$form_ricerca = '';
$elenco_corsi = '';
$utenti = '';
$bottone_utente = '<a class="loginn" href="registrazione.php">Registrati </a>';


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
     <meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="shortcut icon" href="immagini/logo.jpg" type="image/jpg">
    <title> Esame Lazzarotto Francesco (Sito Palestra) </title>

    <link rel="stylesheet" href="style.css" type="text/css">
<style>

  .social-section {
            
            align-items: center;
            
              display: flex;
  align-items: center;
  margin: 20px;
  position: relative; 
  z-index: 1; 
        }

        .photo {
            flex: 1;
            padding: 20px;
            padding-left: 300px;
            
        }
        .image-overlay {
    position: relative;
}

.overlay-text {
    position: absolute;
    top: 0;
    left: 0;
    background: rgba(0, 0, 0, 0.6); 
    color: #fff; 
    padding: 20px; 
    width: 100%; 
    text-align: left; 
       .ft {
    width: 100%;
    margin-top: 100px;
    max-width: 400px; 
    box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.2); 
    border-radius: 8px; 
    transition: transform 0.2s ease; 
}

.ft:hover {
    transform: scale(1.05); 
}


        .social-logos {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .social-logo {
            margin: 10px 0;
            display: flex;
            align-items: center;
        }

        .social-logo img {
            width: 40px;
            height: 40px;
            margin-right: 10px;
        }

        .social-description {
            font-size: 16px;
            text-transform: uppercase;
            font-family: Arial black;
            padding-left: 30px;
        }

        .fa {
  padding: 20px;
  font-size: 30px;
  width: 50px;
  text-align: center;
  text-decoration: none;
  margin: 5px 2px;
}

.fa:hover {
    opacity: 0.7;
}
.fa-linkedin {
  background: #007bb5;
  color: white;
}
 .fa-instagram {
     background: #125688;
     color: white
     ;
 }
 .fa-facebook {
     background: #3B5998;
     color: white;
 }

 .fa-twitter {
     background: #55ACEE;
     color: white;
 }


  </style>
  </head>

  <body>
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
                menu.css('background', '#222');
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

<a name="inizio"></a>


<nav id="navigation" class="menu">

  <table> 
      
         

    <br>
    <td>
    <ul>
      <li><a href="homee.php">Home </a></li>
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
        
     <div id="sfondohome2">   <div class="testo11"> Come trovarci? </div>
      <div class="testopiccolo1">  <br> Tel : 339 241 75 25 <br> Via Silvio Pellico 10/a - Torino <br> <br> </div>
      <div class="testo1">
        Se non trovi informazioni o hai bisogno di aiuto <br>
      contatta l'assistenza! <br> 
       <a href="assistenza.php" class="bottone1">Contatta l'assistenza</a>
      </div> 
    </div>
</div>
<section style="margin-top: 10%; display: flex; justify-content: center; align-items: center; gap: 30px; padding: 20px; margin-bottom: 8%; ">
    <!-- Facebook Icon -->
    <a href="https://www.facebook.com" target="_blank" style="text-decoration: none;">
        <div style="width: 100px; height: 100px; display: flex; justify-content: center; align-items: center; background-color: #4267B2; border-radius: 50%; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); transition: transform 0.3s, box-shadow 0.3s;">
            <img src="https://img.icons8.com/ios-glyphs/60/ffffff/facebook-new.png" alt="Facebook" style="width: 50px; height: 50px;">
        </div>
    </a>
    <!-- Twitter Icon -->
    <a href="https://www.twitter.com" target="_blank" style="text-decoration: none;">
        <div style="width: 100px; height: 100px; display: flex; justify-content: center; align-items: center; background-color: #1DA1F2; border-radius: 50%; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); transition: transform 0.3s, box-shadow 0.3s;">
            <img src="https://img.icons8.com/ios-glyphs/60/ffffff/twitter.png" alt="Twitter" style="width: 50px; height: 50px;">
        </div>
    </a>
    <!-- Instagram Icon -->
    <a href="https://www.instagram.com" target="_blank" style="text-decoration: none;">
        <div style="width: 100px; height: 100px; display: flex; justify-content: center; align-items: center; background: radial-gradient(circle at 30% 30%, #F58529, #DD2A7B, #8134AF); border-radius: 50%; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); transition: transform 0.3s, box-shadow 0.3s;">
            <img src="https://img.icons8.com/ios-glyphs/60/ffffff/instagram-new.png" alt="Instagram" style="width: 50px; height: 50px;">
        </div>
    </a>
    <!-- LinkedIn Icon -->
    <a href="https://www.linkedin.com" target="_blank" style="text-decoration: none;">
        <div style="width: 100px; height: 100px; display: flex; justify-content: center; align-items: center; background-color: #0A66C2; border-radius: 50%; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); transition: transform 0.3s, box-shadow 0.3s;">
            <img src="https://img.icons8.com/ios-glyphs/60/ffffff/linkedin.png" alt="LinkedIn" style="width: 50px; height: 50px;">
        </div>
    </a>
</section>

<script>
    // Aggiunge l'effetto hover per ogni icona
    document.querySelectorAll("a > div").forEach((icon) => {
        icon.addEventListener("mouseenter", () => {
            icon.style.transform = "scale(1.2)";
            icon.style.boxShadow = "0 6px 12px rgba(0, 0, 0, 0.3)";
        });
        icon.addEventListener("mouseleave", () => {
            icon.style.transform = "scale(1)";
            icon.style.boxShadow = "0 4px 8px rgba(0, 0, 0, 0.2)";
        });
    });
</script>


          <footer>
            <a href="#inizio">

                          <div id="tornasu">

                          <img src= "immagini/su.png" class="tornasu" alt="freccia per tornare all'inizio della pagina" title="freccia">

                          </div>
                        </a>

                            <div id="social">

                              <table> <tr> <td>

    <a href="https://www.facebook.com" target="_blank"> <img src="immagini/fb1.png" id="socialimg1" alt="logo facebook" title="contatti">  </a> </td> <td>
    <a href="https://www.instagram.com" target="_blank"> <img src="immagini/ig1.png" id="socialimg2" alt="logo instagram" title="contatti">  </a> </td> <td>
    <a href="https://www.linkedin.com/in/francesco-lazzarotto-a09aa51ba/" target="_blank"> <img src="immagini/ln1.png" id="socialimg3" alt="logo linkedin" title="contatti">

     </a>
   </td>
 </tr>
</table>
</div>



                &copy; <em> 2020 Lazzarotto Gym - Fitness <br>
                Design and Graphics by </em>  <a href="linkedin.com/in/francesco-lazzarotto-a09aa51ba/"target="_blank" class="cop">Francesco Lazzarotto </a> <br>
              <a href="https://mail.google.com/mail/u/0/#inbox" target="_blank" class="cop1" >  francesco.lazzarotto@edu.unito.it <br>
        
    </footer>
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
