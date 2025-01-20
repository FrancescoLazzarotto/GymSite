<?php
// Configurazione dell'applicazione

// Impostazioni di base
define('APP_NAME', 'Gym Management System');  // Nome dell'applicazione
define('APP_VERSION', '1.0');                  // Versione dell'applicazione

// Percorsi delle directory
define('BASE_URL', 'http://localhost/gym_management/'); // URL base dell'applicazione
define('CSS_PATH', BASE_URL . 'assets/css/'); // Percorso della cartella CSS
define('JS_PATH', BASE_URL . 'assets/js/');   // Percorso della cartella JS
define('IMG_PATH', BASE_URL . 'assets/images/'); // Percorso della cartella immagini
define('UPLOAD_DIR', __DIR__ . '/uploads/');  // Percorso della cartella per i file caricati

// Impostazioni per la sicurezza
define('PASSWORD_HASH_ALGO', PASSWORD_BCRYPT); // Algoritmo di hash per le password
define('SESSION_TIMEOUT', 3600);                // Timeout della sessione in secondi

// Altre configurazioni
define('ADMIN_EMAIL', 'admin@gym_management.com'); // Email dell'amministratore
?>
