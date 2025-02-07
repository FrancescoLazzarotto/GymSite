
<?php 
//Connessione al database
function connessione_database()
{
global $connessione;
$host = "localhost";
$dbname = "palestralazzarotto";
$username = "root";
$password = "";

try {
$connessione = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
$connessione->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
return $connessione;
} catch (PDOException $e) {
error_log("Errore di connessione: " . $e->getMessage());
echo json_encode(['success' => false, 'message' => 'Errore di connessione al database']);
exit;
}
}