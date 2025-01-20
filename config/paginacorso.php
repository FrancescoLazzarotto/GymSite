<?php
include("funzioni.php");
include "iscrizione.php";
session_start();
$testo_errore = '';
$bottone_login = '<a class="" href="login.php">Login</a>';
$bottone_utente = '<a class="" href="registrazione.php">Registrati </a>';
$utenti = '';
$messaggio = '';
$corso_id = '';

if (isset($_SESSION['id_utente'])) {
  $user_id = $_SESSION['id_utente']; // Ottieni l'ID utente dalla sessione

  // Procedi con l'iscrizione al corso
  $connessione = connessione_database();
  try {
    $query = "INSERT INTO iscrizioni (id_utente, id_corso) VALUES (:user_id, :corso_id)";
    $stmt = $connessione->prepare($query);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':corso_id', $corso_id, PDO::PARAM_INT); // Assicurati che $corso_id sia definito

    $stmt->execute();
    echo json_encode(['status' => 'success', 'message' => 'Iscrizione avvenuta con successo.']);
  } catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Errore durante l\'iscrizione: ' . $e->getMessage()]);
  } finally {
    $connessione = null; // Chiudo la connessione
  }
} else {
  echo json_encode(['status' => 'error', 'message' => 'Utente non autenticato.']);
}




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

// Verifica se è presente l'id nella richiesta GET e nel caso lo ottiene
if (isset($_GET['id'])) {
  $corso_id = $_GET['id'];

  // Connessione al database
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
    $connessione = null; // Chiudo la connessione
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
  <link rel="stylesheet" href="style.css" type="text/css">
</head>

<body>
  <a name="inizio"></a>
  <nav id="navigation" class="menu1">
    <table>
      <tr>
         
        <br>
        <td>
          <ul>
            <li><a href="homee.php">Home</a></li>
            <li><a href="corsi.php">Corsi</a></li>
            <li> <a href="benefici.php"> Benefici </a> </li>   

            <li><a href="contatti.php">Contatti</a></li>
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
  <br> <br> <br> <br> <br>

  <div id="content2" class="course-details">
    <?php
    if (isset($corso)) {
      echo "<div class='course-info'>";
      echo "<h1 class='course-title'>" . htmlspecialchars($corso['nome']) . "</h1>";
      echo "<p class='course-type'><strong>Tipologia:</strong> " . htmlspecialchars($corso['tipologia']) . "</p>";
      echo "<p class='course-participants'><strong>Partecipanti:</strong> " . htmlspecialchars($corso['partecipanti']) . "</p>";
      echo "</div>";
      echo "<p class='course-description'> <strong> Descrizione: </strong>" . htmlspecialchars($corso['descrizione']) . "</p>";
      echo "<button id='iscriviti' data-corso-id='" . htmlspecialchars($corso['id_corso']) . "'>Iscriviti al Corso</button>";
    }
    ?>
    <a href="corsi.php" class="return-button">
    <button class="back">Torna ai corsi</button>
  </a>
  </div>
 


  <footer>
    <a href="#inizio">

      <div id="tornasu">

        <img src="immagini/su.png" class="tornasu" alt="freccia per tornare all'inizio della pagina" title="freccia">

      </div>

    </a>
    <div id="social">
      <table>
        <tr>
          <td>

            <a href="https://www.facebook.com" target="_blank"> <img src="immagini/fb1.png" id="socialimg1"
                alt="logo facebook" title="contatti"> </a>
          </td>
          <td>
            <a href="https://www.instagram.com" target="_blank"> <img src="immagini/ig1.png" id="socialimg2"
                alt="logo instagram" title="contatti"> </a>
          </td>
          <td>
            <a href="https://www.linkedin.com/in/francesco-lazzarotto-a09aa51ba/" target="_blank"> <img
                src="immagini/ln1.png" id="socialimg3" alt="logo linkedin" title="contatti"> </a>
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
   <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
   $(document).ready(function() {
    $('#iscriviti').on('click', function() {
        var corsoId = $(this).data('corso-id');

        $.ajax({
            url: 'iscrizione.php',
            type: 'POST',
            data: { id_corso: corsoId },
            success: function(response) {
                console.log("Risposta:", response);
                if (response.status === 'success') {
                    alert(response.message);
                } else {
                    alert(response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error("Errore AJAX:", error);
                console.error("Stato:", status);
                console.error("Dettaglio:", xhr.responseText);
                alert('Errore durante l\'invio della richiesta. Verifica la console per dettagli.');
            }
        });
    });
});
  </script>
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