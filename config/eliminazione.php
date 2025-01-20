<?php
session_start();
include 'funzioni.php';

$tabella = '';
$mail = '';
$message_us = '';
$message_c = '';
$message_in = '';
$message_psw = '';
$bottone_login = '<a id="login" href="login.php">Login</a>';
$utenti = '';
$bottone_utente = '<a class="loginn" href="registrazione.php">Registrati</a>';

// Condizione per verificare se l'utente è un amministratore e se la variabile di sessione "amministratore" è impostata a 1
if (isset($_SESSION["amministratore"]) && $_SESSION["amministratore"] == 1) {
    $bottone_login = '<form action="login.php" method="post"><input type="submit" id="loginn" name="logout" value="Logout"></form>';
    $utenti = '<li class=""><a href="areaadmin.php">Area Admin</a></li>';
    $bottone_utente = '';
} elseif (isset($_SESSION["login"])) {
    $username = $_SESSION['login'];
    $tipo = tipoUtente($username); // Funzione per determinare il tipo di utente
    $bottone_login = '<form action="login.php" method="post"><input type="submit" id="logout" name="logout" value="Logout"></form>';

    if ($tipo == 'reg') { 
        // Se è un utente registrato standard, aggiunge il bottone per l'area privata
        $utenti = '<li class=""><a href="utenti.php">Area Privata</a></li>';
        $bottone_utente = '';
    } else { 
        // Se è un amministratore, aggiunge il bottone per l'area admin
        $utenti = '<li class=""><a href="areaadmin.php">Area Admin</a></li>';
        $bottone_utente = '';
    }

    // Logout: se viene cliccato, distrugge la sessione e cambia il bottone in "Login"
    if (isset($_POST['logout']) == true) {
        $bottone_login = '<a id="loginn" href="login.php">Login</a>';
        $utenti = '';
        session_destroy();
    }
}

// Controllo se l'utente è un amministratore e richiama la funzione con la lista dei corsi
if (isset($_SESSION["amministratore"]) && $_SESSION["amministratore"] == 1) {
    $corsi = listaAdmin(); // Funzione che restituisce la lista dei corsi
    $id_corso = '';
}

// Se il bottone submit per la cancellazione è stato cliccato
if (isset($_POST['cancella'])) {
    $id_corsi = isset($_POST['id_corso']) ? $_POST['id_corso'] : array(); // Ottiene gli ID dei corsi selezionati

    $conn = connessione_database(); // Connessione tramite PDO
    foreach ($id_corsi as $id_corso) {
        $risultato = cancellaCorso($id_corso); // Funzione che esegue la cancellazione con PDO

        if ($risultato) { 
            $message_c = '<p class="titolovip">Corso cancellato con successo</p>';
            $corsi = listaAdmin(); // Aggiorna la lista dei corsi
        } else {
            $message_c = '<p>Errore nella cancellazione</p>';
        }
    }
}
?>





for ($i = 0; $i < sizeof($corsi); $i++) {
    $checkbox = '<input type="checkbox" value="' . $corsi[$i]['id_corso'] . '" name="id_corso[]"/>'; //crea checkbox per ogni id corso
    $tabella .= '<tr> 
                      <td>' . $checkbox . '</td>
                      <td>' . $corsi[$i]['nome'] . '</td>
                      <td>' . $corsi[$i]['tipologia'] . '</td>
                      <td>' . $corsi[$i]['partecipanti'] . '</td> 
                       <td>' . $corsi[$i]['orario'] . '</td>
                       <td> <a class="detad" href="paginacorsiadmin.php?id=' . $corsi[$i]['id_corso'] . ' ">Dettagli</a>
</td>

                     
                    </tr>';
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
    <style>
        #table {
            width: 48%;
            border-collapse: collapse;
            margin: 20px 0;
            border-radius: 25px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }

        #table th,
        #table td {
            padding: 10px;
            text-align: center;
            border: 1px solid #000;
            color: #000;
            background-color: #f5f5f5;
        }

        #table th {
            font-weight: bold;
            background-color: #d3d3d3;
            color: #000;
        }

        #table tr:nth-child(even) {
            background-color: #e0e0e0;
        }

        #table tr:hover {
            background-color: #f0f0f0;
        }

        #table td {
            font-weight: bold;
            color: #333;
           
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

        
        <center>

            <h1 class="titolovipad2">Corsi</h1>

            <form action="eliminazione.php" method="post">
                <table id="table">
                    <tr>
                        <th>Seleziona:</th>
                        <th>Nome</th>
                        <th>tipologia</th>
                        <th>Partecipanti</th>
                        <th>Giorno e ora inizio</th>
                        <th>Mostra dettagli</th>
                    </tr>
                    <tr>
                        <?php echo $tabella ?>
                    </tr>
<?php echo $message_c ?> <!-- stampa del messaggio di cancellazione !-->
                </table>
                <div id="login-form5"> <a href="" class="lb1">
                    <input type="submit" value="Cancella" name="cancella" /> </a>
                    
               
            </form>
            </div>

        
        </center>
    </div>

<a href="areaadmin.php" class="rb-margin-2"> <button class="backadmin">  Torna all'area admin </button> </a>
 
<br> <br>  <br>
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