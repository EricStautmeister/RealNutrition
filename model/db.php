<?php

function dbVersion($db)
{
    try {
        $stm = $db->query("SELECT VERSION()");
        $version = $stm->fetch();

        return $version[0] . PHP_EOL;
    } catch (PDOException $error) {
        return $error;
    }
}

function addUser($db)
{
    try {
        $stm = $db->query("INSERT INTO users (username, password) VALUES ('test', 'test')");
        
        return $stm;                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                
    } catch (PDOException $error) {
        return $error;
    }
}
