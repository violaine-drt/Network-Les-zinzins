<?php


function importBdd()
{
    $mysqli = new mysqli("localhost", "root", "root", "socialnetwork");
    return $mysqli;
}
