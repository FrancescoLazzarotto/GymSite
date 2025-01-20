<?php
include("funzioni.php");
session_start();
$testo_errore = ''; //INIZIALIZZO LE VARIABILI
$bottone_login = '<a class="" href="login.php">Login</a>';
$bottone_utente = '<a class="" href="registrazione.php">Registrati </a>';
$utenti = '';


if (isset($_POST["logout"])) { //SE E STATO PREMUTO IL PULSANTE LOGOUT NEL FORM            
    session_destroy(); //ELIMINO LA SESSIONE
} else if (isset($_SESSION["amministratore"]) && $_SESSION["amministratore"] == "1") {
    header("Location: AreaAdmin.php");
} else if (isset($_SESSION["login"]) && $_SESSION["login"] == "1") { //HA GIA EFFETTUTATO IL LOGIN (infatti ho la session salvata)
    header("Location: homee.php");
} else if (isset($_POST["username"]) && !empty($_POST["username"]) && isset($_POST["password"]) && !empty($_POST["password"])) { //GUARDO SE CI SONO I DATI PER EFETTUARE IL LOGIN
    //PRENDO I DATI DAL FORM DI LOGIN
    $username = $_POST["username"];
    $password = $_POST["password"];

    $userData = controllaUtente($username, $password); //USO LA FUNZIONE PER VEDERE SE ESISTE L'UTENTE
    $id = '';
    if ($userData !== false) { //SE ESISTE UN UTENTE CON QUESTO LOGIN
        if ($userData != "Password errata") { //SE LA PASSWORD E GIUSTA
            $_SESSION["login"] = $username; //SALVO NELLA SESSIONE CHE HA EFFETTUATO IL LOGIN
            $_SESSION['password'] = $password;
            $_SESSION['id_utente'] = $id_utente;
            $_SESSION["amministratore"] = $userData["amministratore"];
            for ($i = 0; $i < sizeof($userData); $i++) {
                if ($userData['amministratore'] == 1) {
                    $testo_errore = "<p class='testo-errore'> <br> Sei un amministratore, accedi <a href='loginadmin.php'> qua </a> </p>";
                } else {
                    header("Location: corsi.php");
                }
            }

        } else {
            $testo_errore = "<p class='testo-errore'>Password errata</p>";
        }
    } else {
        $testo_errore = "<p class='testo-errore'>Utente inesistente</p>";
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
        <br> <br> <br> <br>


        <center>
            <div id="login-form0">
                <h2 class="titolovip"> Autenticati per accedere ai nostri servizi!
                
                <form name="form" method="post" action="">



                    <label for="username"> &nbsp;
                        <input type="text" name="username" value placeholder="Username" id="username" required>
                    </label>
                    <label for="password"> &nbsp;
                        <input type="password" name="password" placeholder="Password" id="password" required>
                    </label>

                    <?php echo $testo_errore ?> <!-- STAMPO GLI EVENTUALI MESSAGGI DI ERRORE -->

                    <center> <label for="invio"> <input type="submit" onclick="premuto" name="login" value="Login"
                                id="invio"> </label>
                    </center>
                </form>

                <br>
                <a href="registrazione.php" class="linkstilizzato1">Nuovo utente? Registrati!</a>
            </div>
        </center>

        <script>
            $('#show_password').hover(function functionName() {
                //Change the attribute to text
                $('#password').attr('type', 'text');
                $('.glyphicon').removeClass('glyphicon-eye-open').addClass('glyphicon-eye-close');
            }, function () {
                //Change the attribute back to password
                $('#password').attr('type', 'password');
                $('.glyphicon').removeClass('glyphicon-eye-close').addClass('glyphicon-eye-open');
            }
            );
        </script>


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

            <div class="footer_sezione amministrazione ">
                <a class="linkstilizzato1" href="loginadmin.php">&#128274; Area di Amministrazione</a>

            </div>

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