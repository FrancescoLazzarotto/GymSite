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


if (isset($_POST['inserisci'])) { //se è stato effetuato un inserimento 

    //richiamo la funzione con i parametri dei valori presi dal form 
    $risultato = nuovoCorso($_POST['nome'], $_POST['tipologia'], $_POST['descrizione'], $_POST['partecipanti'], $_POST['orario']); 

    if ($risultato == true) { //gestisco la query 
        $message_in = '<p> Inserimento avvenuto con successo <br> <br> </p>';
        $corsi = listaAdmin(); 

    } else {
        $message_in = '<p>Non hai inserito tutti i campi necessari</p>';
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



    <div id="content1">  
            <br><br> <br> <br> <br> <br> <br>

            <div id="login-formins">
                <h2 class="titolovip"> Inserimento corsi </h2> <br>
                <form action="inserimento.php" method="post">

                    <label for="nome"> Nome corso:
                        <input type="text" id="nome" name="nome" required placeholder="Inserire nome corso" /> </label>
                    <br>

                    <label for="tipologia"> Tipologia:
                        <input type="text" id="tipologia" name="tipologia" required
                            placeholder="Inserire tipologia corso" /> </label> <br>

                    <label for="descrizione"> Descrizione corso:
                        <input type="text" id="descrizione" name="descrizione" required
                            placeholder="Inserire descrizione corso" /> </label> <br>

                    <label for="partecipanti"> Partecipanti:
                        <input type="number" id="partecipanti" name="partecipanti" required
                            placeholder="Numero partecipanti" /> </label> <br>

                    <label for="orario">Inizio corso:
                        <input type="datetime" id="orario" name="orario" required placeholder="Orario corso"/> </label> <br>


 <?php echo $message_in ?> <!-- stampo messaggio inserimento !-->

                    
            </div>
        </center>
    </div> <br> <div id="login-form5"> <a href="" class="lb1">
    <input type="submit" value="Inserisci" name="inserisci" />

                
               
                </form>
<a href="areaadmin.php" class="rb-margin-1"> <button class="backadmin">  Torna all'area admin </button> </a>
    



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