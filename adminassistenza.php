<?php
session_start();
include 'includes/funzioni.php';
$bottone_login = '<a class="loginn" href="login.php">Login</a>';
$bottone_utente = '<a class="" href="registrazione.php">Registrati</a>';
$utenti = '';
$message_c = '';

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
<html lang="it" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta name="keywords" content="Web design, grafica, html, css" />
    <meta name="description" content="Sito web di Francesco Lazzarotto" />
    <meta name="author" content="Francesco Lazzarotto" />
    <link rel="shortcut icon" href="immagini/logo.jpg" type="image/jpg">
    <title>Esame Lazzarotto Francesco (Sito Palestra)</title>
    <link rel="stylesheet" href="assets/style.css" type="text/css">
    <style>
        #table {
            width: 80%;
            height: 80%;
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
                         <li><a href="../../index.php">Home </a></li>
                        <li><a href="../public/corsi.php"> Corsi</a></li>
                      <li> <a href="../public/benefici.php"> Benefici </a> </li>   
                        <li><a href="../public/contatti.php">Contatti </a></li>
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
    <div id="content2">
        <br>
        <br>
        <br>
        <br>
        <center>
            <form action="adminassistenza.php" method="POST">
                <h1 class="titolovipad1">Assistenza</h1>

                <table id="table">
                    <tr>
                        
                        <th>ID Assistenza</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Richiesta</th>
                        <th>Risposta</th>
                        <th>Invia Risposta</th>
                    </tr>
                    <?php
                  



                    // richiamo la funzione recuperaAssistenza per ottenere i dati dalla tabella assistenza
                    $assistenzaData = recuperaAssistenza();

                    //stampa le assistenze generando un continuo della tabella precedentemente creata 
                    foreach ($assistenzaData as $row) {
                        //$checkbox = '<input type="checkbox" value="' . $row['id_assistenza'] . '" name="id_assistenza[]"/>'; //genero l'id prendendolo dalla query della funz
                        echo '<tr>';

                        
                        echo '<td>' . $row['id_assistenza'] . '</td>';
                        echo '<td>' . $row['username_ass'] . '</td>';
                        echo '<td>' . $row['mail_ass'] . '</td>';
                        echo '<td>' . $row['assistenza_richiesta'] . '</td>';
                        echo '<td>
                <form action="adminassistenza.php" method="POST">
                <textarea name="risposta_' . $row['id_assistenza'] . '" rows="2" cols="30">'
                            . (isset($row['risposta']) ? htmlspecialchars($row['risposta']) : '') .
                            '</textarea>    
                
              </td>';

                        
                        echo '<td>
                    <button type="submit" name="invia_risposta" value="' . $row['id_assistenza'] . '">Invia Risposta</button>
                </form>
              </td>';
                        echo '</tr>';
                    }
                    //se viene cliccato il bottone invia risposta viene richiamata la funzione rispondiAssistenza
                    if (isset($_POST['invia_risposta'])) {
                        $id_assistenza = $_POST['invia_risposta'];
                        $messaggio_risposta = $_POST['risposta_' . $id_assistenza];

                        if (!empty($messaggio_risposta)) {
                            $risultato = rispondiAssistenza($id_assistenza, $messaggio_risposta);
                            if ($risultato) {
                                $message_c = '<p class="titolovip">Risposta inviata con successo.</p>';
                            } else {
                                $message_c = '<p>Errore nell\'invio della risposta.</p>';
                            }
                        } else {
                            $message_c = '<p>Errore: la risposta non può essere vuota.</p>';
                        }
                    }


                    ?>
                </table>
                </form>
                <?php echo $message_c ?> 
        </center>
        <br> <br>
        <div id="login-form5">
            <a href="" class="lb2">
                
            </form>
        </div>
        <a href="areaadmin.php" class="rb-margin"> <button class="backadmin"> Torna all'area admin </button> </a>


    </div>
    <footer>
        <a href="#inizio">
            <div id="tornasu">
                <img src="assets/immagini/su.png" class="tornasu" alt="freccia per tornare all'inizio della pagina"
                    title="freccia">
            </div>
        </a>
        <div id="social">
            <table>
                <tr>
                    <td>
                        <a href="https://www.facebook.com" target="_blank"><img src="assets/immagini/fb1.png" id="socialimg1"
                                alt="logo facebook" title="contatti"></a>
                    </td>
                    <td>
                        <a href="https://www.instagram.com" target="_blank"><img src="assets/immagini/ig1.png" id="socialimg2"
                                alt="logo instagram" title="contatti"></a>
                    </td>
                    <td>
                        <a href="https://www.linkedin.com/in/francesco-lazzarotto-a09aa51ba/" target="_blank"><img
                                src="assets/immagini/ln1.png" id="socialimg3" alt="logo linkedin" title="contatti"></a>
                    </td>
                </tr>
            </table>
        </div>
        &copy; <em> 2020 Lazzarotto Gym - Fitness <br> Design and Graphics by </em> <a
            href="linkedin.com/in/francesco-lazzarotto-a09aa51ba/" target="_blank" class="cop">Francesco Lazzarotto </a>
        <br>
        <a href="https://mail.google.com/mail/u/0/#inbox" target="_blank"
            class="cop1">francesco.lazzarotto@edu.unito.it<br>
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