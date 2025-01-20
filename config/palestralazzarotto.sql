-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Ott 13, 2023 alle 18:09
-- Versione del server: 10.4.28-MariaDB
-- Versione PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `palestralazzarotto`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `corso`
--
INSERT INTO `corso` ( `nome`, `tipologia`, `descrizione`, `partecipanti`, `orario`) VALUES
( 'Zumba Fitness', 'Fitness', 'Il corso di Zumba Fitness è un mix di balli e fitness che ti farà divertire mentre bruci calorie. Senti la musica e segui il ritmo. Le sessioni si tengono ogni mercoledì e venerdì alle 20:00 per una sessione di fitness danzante.',
  20, '2023-10-18 20:00:00'),

( 'CrossFit Challenge', 'Fitness', 'Unisciti alla sfida del CrossFit per migliorare la tua forza, resistenza e agilità. Questo corso combina esercizi funzionali ad alta intensità per darti una sfida completa. Lezioni disponibili ogni lunedì e gioveddì alle 17:00.',
  18, '2023-10-16 17:00:00'),

( 'Hatha Yoga', 'Fitness', 'Il corso di Hatha Yoga è una pratica più lenta e riflessiva che si concentra su posture statiche e allungamento. Questo corso è ideale per rilassarsi e trovare la calma interiore. Partecipa ogni martedì e giovedì alle 16:00 per una pratica yoga tranquilla.',
  15, '2023-10-17 16:00:00'),

( 'Piloxing Fusion', 'Fitness', 'Il Piloxing Fusion combina il Pilates con il pugilato per un allenamento completo del corpo. Rafforza il tuo nucleo e brucia calorie con il ritmo della musica. Unisciti a noi ogni mercoledì e venerdì alle 18:30 per questa esperienza unica.',
  20, '2023-10-18 18:30:00'),

( 'Spinning Intenso', 'Fitness', 'Il corso di Spinning Intenso è un allenamento cardiovascolare ad alta intensità. Pedala e brucia calorie con musica motivante. Le sessioni si tengono ogni lunedì e gioveddì alle 19:30 per sfidare il tuo cardio.',
  22, '2023-10-16 19:30:00'),

( 'Ginnastica per Anziani', 'Fitness', 'Questo corso di Ginnastica è progettato per gli anziani e si concentra su movimenti leggeri per mantenere la mobilità. Partecipa ogni martedì e giovedì alle 11:00 per rimanere attivo e in salute.',
  10, '2023-10-17 11:00:00'),

( 'Danza Hip-Hop', 'Fitness', 'Il corso di Danza Hip-Hop è un''esperienza di danza urbana. Impara i movimenti e balla al ritmo della musica hip-hop. Lezioni disponibili ogni mercoledì e venerdì alle 21:00.',
  15, '2023-10-18 21:00:00'),

( 'Allenamento in Acqua', 'Fitness', 'L''Allenamento in Acqua è un modo leggero e rinfrescante per fare esercizio. Questo corso è perfetto per l''estate e si tiene ogni lunedì e gioveddì alle 14:00.',
  12, '2023-10-16 14:00:00'),

( 'Tai Chi', 'Fitness', 'Il Tai Chi è un''antica pratica cinese che combina movimenti lenti e respirazione profonda. Questo corso promuove la calma e l''equilibrio. Unisciti a noi ogni martedì e gioveddì alle 15:30 per migliorare la tua salute mentale e fisica.',
  14, '2023-10-17 15:30:00'),

( 'Kickboxing Fitness', 'Fitness', 'Il Kickboxing Fitness è un mix di kickboxing e fitness. Brucia calorie e sfoga lo stress mentre impari le tecniche di combattimento. Lezioni disponibili ogni mercoledì e venerdì alle 19:30.',
  20, '2023-10-18 19:30:00');



INSERT INTO `corso` ( `nome`, `tipologia`, `descrizione`, `partecipanti`, `orario`) VALUES
( 'Allenamento Funzionale', 'Fitness', 'Il corso di allenamento funzionale è progettato per migliorare la tua forza, resistenza e flessibilità. Imparerai esercizi che coinvolgono tutto il corpo e che ti aiuteranno a prepararti per sfide fisiche quotidiane. Questo corso è adatto a persone di tutti i livelli di fitness. Le lezioni si svolgono ogni lunedì e giovedì alle 18:00.',
20, '2023-10-16 18:00:00'),

( 'Body Pump', 'Fitness', 'Body Pump è un allenamento con i pesi che ti aiuterà a tonificare e rafforzare tutti i principali gruppi muscolari. Le sessioni includono esercizi con bilancieri e pesi liberi. Unisciti a noi ogni mercoledì e venerdì alle 17:30 per un allenamento completo del corpo.',
15, '2023-10-18 17:30:00'),

( 'Yoga Vinyasa', 'Fitness', 'Il corso di Yoga Vinyasa è una pratica dinamica che combina movimenti fluidi con la respirazione consapevole. Questo corso è progettato per migliorare la flessibilità, la forza e la calma mentale. Le lezioni si tengono ogni martedì e giovedì alle 10:30 per aiutarti a trovare l''equilibrio tra corpo e mente.',
18, '2023-10-17 10:30:00'),

( 'Aerobica Ad Alto Impatto', 'Fitness', 'L''aerobica ad alto impatto è un allenamento energico che coinvolge il movimento coordinato e coreografie. Questo corso ti farà sudare e bruciare calorie, migliorando la tua resistenza cardiovascolare. Unisciti a noi ogni martedì e giovedì alle 19:00 per un''esperienza di fitness coinvolgente.',
25, '2023-10-17 19:00:00'),

( 'Circuit Training', 'Fitness', 'Il circuit training è un allenamento completo del corpo che combina esercizi di forza e cardio. Questo corso è perfetto per chi vuole bruciare calorie e migliorare la forma fisica generale. Le sessioni si svolgono ogni mercoledì e venerdì alle 9:00.',
15, '2023-10-18 09:00:00'),

( 'Pilates Matwork', 'Fitness', 'Il Pilates Matwork è una forma di allenamento che si concentra sulla forza del nucleo e sulla flessibilità. In questo corso, imparerai esercizi che puoi eseguire senza attrezzi speciali. Le lezioni si tengono ogni lunedì e mercoledì alle 16:30 per migliorare la postura e la forza.',
12, '2023-10-16 16:30:00');



CREATE TABLE assistenza (
    id_assistenza INT AUTO_INCREMENT PRIMARY KEY,
    username_ass varchar(20) not null,
    mail_ass varchar(30) not null,
    assistenza_richiesta varchar(500) not null
);
CREATE TABLE `corso` (
  `id_corso` int(10) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `tipologia` varchar(255) NOT NULL,
  `descrizione` varchar(100) DEFAULT NULL,
  `partecipanti` int(10) NOT NULL,
  `orario` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `corso`
--

INSERT INTO `corso` (`id_corso`, `nome`, `tipologia`, `descrizione`, `partecipanti`, `orario`) VALUES
(8, 'sa', 'asd', 'sda', 12, '2004-12-16 16:30:00'),
(9, '60 Minuti di Energia', 'Zumba', 'Funzionale per tutto il corpo, per un ottimo allenamento completo lo zumba fa per voi ', 4, '2004-12-16 16:30:00');

-- --------------------------------------------------------

--
-- Struttura della tabella `utenti`
--

CREATE TABLE `utenti` (
  `id_utente` int(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(30) NOT NULL,
  `nome` varchar(30) NOT NULL,
  `cognome` varchar(30) NOT NULL,
  `mail` varchar(40) NOT NULL,
  `data_nascita` date NOT NULL,
  `amministratore` tinyint(1) NOT NULL COMMENT '1 = admin, 0 = no'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `utenti`
--

INSERT INTO `utenti` (`id_utente`, `username`, `password`, `nome`, `cognome`, `mail`, `data_nascita`, `amministratore`) VALUES
(13, 'Amministratore', 'Amministratore1', 'Francesco', 'Lazzarotto', 'admin@gmail.com', '2023-10-11', 1),
(17, 'c', 'c', 'c', 'c', 'checco@gmail.com', '2023-10-19', 0);

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `corso`
--
ALTER TABLE `corso`
  ADD PRIMARY KEY (`id_corso`);

--
-- Indici per le tabelle `utenti`
--
ALTER TABLE `utenti`
  ADD PRIMARY KEY (`id_utente`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `corso`
--
ALTER TABLE `corso`
  MODIFY `id_corso` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT per la tabella `utenti`
--
ALTER TABLE `utenti`
  MODIFY `id_utente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
COMMIT;

ALTER TABLE 'iscrizione'
MODIFY 'Id_Iscrizione' int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18
;
COMMIT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;


-- Utente 1
INSERT INTO utenti (username, password, nome, cognome, mail, data_nascita, amministratore)
VALUES ('utente1', 'password1', 'Mario', 'Rossi', 'mario@example.com', '1990-01-01', 0);

-- Utente 2
INSERT INTO utenti (username, password, nome, cognome, mail, data_nascita, amministratore)
VALUES ('utente2', 'password2', 'Laura', 'Bianchi', 'laura@example.com', '1985-03-15', 0);

-- Utente 3
INSERT INTO utenti (username, password, nome, cognome, mail, data_nascita, amministratore)
VALUES ('utente3', 'password3', 'Carlo', 'Verdi', 'carlo@example.com', '1988-07-22', 0);

-- Utente 4
INSERT INTO utenti (username, password, nome, cognome, mail, data_nascita, amministratore)
VALUES ('utente4', 'password4', 'Sofia', 'Neri', 'sofia@example.com', '1995-11-10', 0);

-- Utente 5
INSERT INTO utenti (username, password, nome, cognome, mail, data_nascita, amministratore)
VALUES ('utente5', 'password5', 'Giulio', 'Gialli', 'giulio@example.com', '1980-05-05', 0);



-- Utente 1
INSERT INTO assistenza (username_ass, mail_ass, assistenza_richiesta)
VALUES ('utente1', 'mario@example.com', 'Ho problemi a trovare  corsi sul sito. Quando tento di ricerca di  un corso, ricevo un errore che dice "Errore di connessione con il server". Ho già provato a cancellare i cookie del mio browser, ma il problema persiste.');

-- Utente 2
INSERT INTO assistenza (username_ass, mail_ass, assistenza_richiesta)
VALUES ('utente2', 'laura@example.com', 'Nel mio profilo utente, non vedo l''opzione per cambiare la mia password. Come posso aggiornarla?');

-- Utente 3
INSERT INTO assistenza (username_ass, mail_ass, assistenza_richiesta)
VALUES ('utente3', 'carlo@example.com', 'Mi piacerebbe ricevere informazioni aggiuntive sui corsi di bodybuilding offerti. Vorrei sapere gli orari disponibili e se ci sono istruttori specializzati.');

-- Utente 4
INSERT INTO assistenza (username_ass, mail_ass, assistenza_richiesta)
VALUES ('utente4', 'sofia@example.com', 'Ho dimenticato la mia password per accedere al sito. C''è un modo per reimpostarla o recuperarla? Ho dovuto fare un altro account.');

-- Utente 5
INSERT INTO assistenza (username_ass, mail_ass, assistenza_richiesta)
VALUES ('utente5', 'giulio@example.com', 'Sto riscontrando difficoltà a trovare informazioni sui corsi di yoga. Vorrei conoscere i dettagli sui corsi, come la durata, i giorni disponibili, e il costo.');
