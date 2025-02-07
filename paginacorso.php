<?php
include 'includes/funzioni.php';
session_start();
$testo_errore = '';
$bottone_login = '<a class="" href="login.php">Login</a>';
$bottone_utente = '<a class="" href="registrazione.php">Registrati </a>';
$utenti = '';
$messaggio = '';
$corso_id = '';

if (isset($_SESSION['id_utente'])) {
  $user_id = $_SESSION['id_utente']; // Ottieni l'ID utente dalla sessione


  // Condizione per verificare se l'utente è un amministratore
  if (isset($_SESSION["amministratore"]) && $_SESSION["amministratore"] == 1) {
    $bottone_login = '<form action="login.php" method="post"><input type="submit" id="loginn" name="logout" value="Logout"></form>';
    $utenti = '<li class=""><a href="areaadmin.php">Area Admin</a></li>';
    $bottone_utente = '';
  } elseif (isset($_SESSION["login"])) {
    $username = $_SESSION['login'];
    $tipo = tipoUtente($username); // Richiamo la funzione tipoUtente e inserisco il valore in una variabile
    $bottone_login = '<form action="login.php" method="post"><input type="submit" id="logout" name="logout" value="Logout"> </form>';

    if ($tipo == 'reg') { // Se è un utente standard, genera il bottone Area Privata
      $utenti = '<li class=""><a href="utenti.php">Area Privata</a></li>';
      $bottone_utente = ''; // Tolgo bottone registrati
    } else {
      $utenti = '<li class=""><a href="areaadmin.php">Area Admin</a></li>';
      $bottone_utente = '';
    }

    // Gestione del logout: distruggo la sessione e genero nuovamente il tasto login
    if (isset($_POST['logout'])) {
      $bottone_login = '<a id="loginn" href="login.php">Login</a>';
      $utenti = '';
      session_destroy();
    }
  }

}

if (isset($_SESSION['id_utente'])) {
  $user_id = $_SESSION['id_utente'];

  // Verifica se l'utente è già iscritto al corso
  try {
    $connessione = connessione_database();
    $query = "SELECT * FROM iscrizioni WHERE id_utente = :user_id AND id_corso = :corso_id";
    $stmt = $connessione->prepare($query);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':corso_id', $corso_id, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      // L'utente è già iscritto al corso, imposta una variabile per mostrare "Disiscriviti"
      $is_iscritto = true;
    } else {
      $is_iscritto = false;
    }
  } catch (PDOException $e) {
    echo "Errore nella query: " . $e->getMessage();
  } finally {
    $connessione = null;
  }
}




// Verifica se è presente l'id nella richiesta GET e nel caso lo ottiene
if (isset($_GET['id'])) {
  $corso_id = $_GET['id'];
  $connessione = connessione_database();

  try {
    $query = "SELECT * FROM Corso WHERE id_Corso = :corso_id";
    $stmt = $connessione->prepare($query);
    $stmt->bindParam(':corso_id', $corso_id, PDO::PARAM_INT);
    $stmt->execute();

    // Se ci sono risultati, estrae i dati del corso
    if ($stmt->rowCount() > 0) {
      $corso = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
      echo "Corso non trovato.";
    }
  } catch (PDOException $e) {
    echo "Errore nella query: " . $e->getMessage();
  } finally {
    $connessione = null;
  }
} else {
  echo "ID del corso non specificato.";
}
?>

<!DOCTYPE html>
<html lang=it dir="ltr">

<head>
  <meta charset="utf-8">
  <meta name="keywords" content="Web design, grafica, html, css" />
  <meta name="description" content="sito web di Francesco Lazzarotto" />
  <meta name="author" content="Francesco Lazzarotto" />
  <link rel="shortcut icon" href="immagini/logo.jpg" type="image/jpg">
  <title>Dettagli Corso</title>
  <link rel="stylesheet" href="assets/style.css" type="text/css">
<style>
.modal {
    display: none; 
    position: fixed;
    z-index: 9999; 
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.6);
    overflow: auto;
    padding-top: 50px; 
    transition: opacity 0.3s ease; 
}


.modal-content {
    background-color: #fff;
    margin: auto;
    margin-top: 10%;
    padding: 30px;
    border-radius: 10px;
    width: 90%; 
    max-width: 500px; 
    text-align: center;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1); 
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    font-size: 16px;
    line-height: 1.5;
    animation: fadeIn 0.5s ease-out; 
}


.close {
    color: #333;
    font-size: 30px;
    font-weight: bold;
    position: relative;
    right: -250px;
    top: 20px;
    cursor: pointer;
    transition: color 0.3s ease;
}

.close:hover {
    color: #e74c3c; 
}


.success {
    color: #155724;
    background-color: #d4edda;
    padding: 15px;
    border-radius: 5px;
    font-size: 18px;
    text-align: center;
    font-weight: bold;
}

.error {
    color: #721c24;
    background-color: #f8d7da;
    padding: 15px;
    border-radius: 5px;
    font-size: 18px;
    text-align: center;
    font-weight: bold;
}



#modal-message {
    font-size: 18px;
    font-weight: bold;
    margin-bottom: 40px;
}


@keyframes fadeIn {
    0% {
        opacity: 0;
        transform: translateY(-20px);
    }
    100% {
        opacity: 1;
        transform: translateY(0);
    }
}


.modal-message-box {
    display: inline-block;
    background-color: #f0f0f0;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
    width: 80%;
    max-width: 500px;
}


@media (max-width: 600px) {
    .modal-content {
        width: 90%;
        padding: 20px;
    }
    .close {
        font-size: 24px;
    }
    #modal-message {
        font-size: 16px;
    }
}

</style>
</head>

<body>
  <a name="inizio"></a>
  <nav id="navigation" class="menu1">
    <table>
      <tr>
         
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
   <div id="modal" class="modal">
    <div class="modal-content">
        <span id="close-modal" class="close">&times;</span>
        <p id="modal-message"></p>
    </div>
</div>
  <br> <br> <br> <br> <br>
<!-- stampo le informazioni del corso !-->
  <div id="content2" class="course-details">
    <?php
    if (isset($corso)) {
      echo "<div class='course-info'>";
      echo "<h1 class='course-title'>" . htmlspecialchars($corso['nome']) . "</h1>";
      echo "<p class='course-type'><strong>Tipologia:</strong> " . htmlspecialchars($corso['tipologia']) . "</p>";
      echo "<p class='course-participants'><strong>Partecipanti:</strong> " . htmlspecialchars($corso['partecipanti']) . "</p>";
      echo "</div>";
      echo "<p class='course-description'> <strong> Descrizione: </strong>" . htmlspecialchars($corso['descrizione']) . "</p>";
      echo "<button id='iscriviti' class='back' data-corso-id='" . htmlspecialchars($corso['id_corso']) . "'>Iscriviti al Corso</button>";
      echo "<button id='disiscriviti' class='back' data-corso-id='" . htmlspecialchars($corso['id_corso']) . "'>Disiscriviti dal Corso</button>";
      
        
      
    }
    ?>
    <a href="corsi.php" class="return-button">
    <button class="back">Torna ai corsi</button>
  </a>
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

            <a href="https://www.facebook.com" target="_blank"> <img src="assets/immagini/fb1.png" id="socialimg1"
                alt="logo facebook" title="contatti"> </a>
          </td>
          <td>
            <a href="https://www.instagram.com" target="_blank"> <img src="assets/immagini/ig1.png" id="socialimg2"
                alt="logo instagram" title="contatti"> </a>
          </td>
          <td>
            <a href="https://www.linkedin.com/in/francesco-lazzarotto-a09aa51ba/" target="_blank"> <img
                src="assets/immagini/ln1.png" id="socialimg3" alt="logo linkedin" title="contatti"> </a>
          </td>
        </tr>
      </table>

    </div>
    &copy; <em> 2020 Lazzarotto Gym - Fitness <br>
      Design and Graphics by </em> <a href="linkedin.com/in/francesco-lazzarotto-a09aa51ba/" target="_blank"
      class="cop">Francesco Lazzarotto </a> <br>
    <a href="https://mail.google.com/mail/u/0/#inbox" target="_blank" class="cop1">
      francesco.lazzarotto@edu.unito.it
      <br>

  </footer>
  <!-- includo jquery per ajax -->
   <script src="https://code.jquery.com/jquery-3.6.0.min.js">
    if (typeof jQuery == 'undefined') {
        document.write('<script src="js/jquery-3.7.1.min.js"><\/script>');
    }
   </script>
<script src="js/richieste_ajax.js"></script>
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