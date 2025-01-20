<?php
include("funzioni.php");
session_start();

$bottone_login = '<a class="loginn" href="login.php">Login</a>';
$bottone_utente = '<a class="" href="registrazione.php">Registrati</a>';
$utenti = '';

if (!isset($_SESSION['login'])) {
    header("Location: loginass.php"); // Reindirizza alla pagina di login se l'utente non è loggato
    exit;
}

//Condizione per verificare se l'utente è un amministratore
if (isset($_SESSION["amministratore"]) && $_SESSION["amministratore"] == 1) {
    $bottone_login = '<form action="login.php" method="post"><input type="submit" id="loginn" name="logout" value="Logout"></form>';
    $utenti = '<li class=""><a href="areaadmin.php">Area Admin</a></li>';
    $bottone_utente = '';
} elseif (isset($_SESSION["login"])) {
    $username = $_SESSION['login'];
    $tipo = tipoUtente($username);
    $bottone_login = '<form action="login.php" method="post"><input type="submit" id="logout" name="logout" value="Logout"> </form>';
    if ($tipo == 'reg') {
        $utenti = '<li class=""><a href="utenti.php">Area Privata</a></li>';
        $bottone_utente = '';
    } else {
        $utenti = '<li class=""><a href="areaadmin.php">Area Admin</a></li>';
        $bottone_utente = '';
    }

    if (isset($_POST['logout']) == true) {
        $bottone_login = '<a id="loginn" href="login.php">Login</a>';
        $utenti = '';
        session_destroy();
    }
}

$testo_errore = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["assistenza"])) {
    $username = $_POST["username"];
    $mail = $_POST["mail"];
    $assistenza_ric = $_POST["ass"];

    // Connessione e query con PDO
    $connessione = connessione_database();
    $query = "INSERT INTO assistenza (username_ass, mail_ass, assistenza_richiesta) VALUES (:username, :mail, :assistenza_ric)";

    try {
        $stmt = $connessione->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':mail', $mail);
        $stmt->bindParam(':assistenza_ric', $assistenza_ric);

        if ($stmt->execute()) {
            $testo_errore = "<strong><p class='testo-errore'>Richiesta di assistenza inviata con successo! <br> La contatteremo a breve via Email</p></strong>";
        } else {
            $testo_errore = "<strong><p class='testo-errore'>Errore nell'invio della richiesta di assistenza.</p></strong>";
        }
    } catch (PDOException $e) {
        $testo_errore = "<strong><p class='testo-errore'>Errore di connessione: " . $e->getMessage() . "</p></strong>";
    }
    $connessione = null; // Chiude la connessione
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

    <link rel="stylesheet" href="style.css" type="text/css">

</head>

<body>

    <a name="inizio"></a>

    <nav id="navigation" class="menu1">
        <table>
            <tr>
               

                <br>
                <td class="spazio-vuoto"></td>
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



    <div id="content1"> <br> <br>


        <main id="log-main">
            <br> <br>
            <center>
                <div id="login-form3">
                    <h2 class="titolovip"> Come possiamo aiutarti? </h2>
                    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                        <!-- PER PREVENIRE CROSS-SITE INJECTION-->
                        <p><label for="username">Username
                                <input type="text" placeholder="Inserisci il tuo username" name="username" required></label></p>
                        <p><label for="mail">Mail:
                                <input type="email" placeholder="Inserisci la tua mail" name="mail" required></label>
                        </p>
                        <p>
                        <label for="ass">Di cosa hai bisogno?</label>

                    <textarea id="ass" name="ass" rows="8" cols="80">
                    
                    </textarea>
                        </p>
                        <input type="submit" name="assistenza" value="Richiedi Assistenza">
                    </form>
                    <?php echo $testo_errore ?>
                   
                </div>
            </center>



    </div>

 
    <footer>
        <a href="#inizio">

            <div id="tornasu">

                <img src="immagini/su.png" class="tornasu" alt="freccia per tornare all'inizio della pagina"
                    title="freccia">

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