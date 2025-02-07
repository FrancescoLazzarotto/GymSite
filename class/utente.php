<?php
//Definizione classe utente 
class Utente
{
    //proprietà private della classe
    private $nome;
    private $cognome;
    private $email;
    private $username;
    private $password;

    // Costruttore
    public function __construct($nome, $cognome, $email, $username, $password)
    {
        $this->nome = $nome;
        $this->cognome = $cognome;
        $this->email = $email;
        $this->username = $username;
        $this->password = $password;
    }

    // Metodi 
    public function getNome()
    {
        return $this->nome;
    }

    public function getCognome()
    {
        return $this->cognome;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getUsername()
    {
        return $this->username;
    }


    // Metodo per restituire le informazioni dell'utente in un array
    public function getInfo()
    {
        return [
            'nome' => $this->nome,
            'cognome' => $this->cognome,
            'email' => $this->email,
            'username' => $this->username,
            'password' => $this->password
        ];
    }
}

