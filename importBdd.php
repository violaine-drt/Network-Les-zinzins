<?php


function importBdd()
{
    $mysqli = new mysqli("localhost", "root", "root", "socialnetwork");
    session_start();
    return $mysqli;
}
