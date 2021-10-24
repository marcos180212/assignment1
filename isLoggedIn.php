<?php
function checkIfLoggedIn()
{
    session_start();
    if(empty($_SESSION['LoggedInUser']))
    {
        header("location:index.html");
    }
}