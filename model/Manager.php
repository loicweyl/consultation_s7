<?php

namespace LoicW\Blog\Model;
// Create the class Manager

class Manager{
    protected function dbConnect(){	// Connexion à la BDD
        $db = new \PDO('mysql:host=localhost;dbname=fps;charset=utf8','loic','9WlXtohLdGA7o40gdgnn');
        return $db;
    }
}
