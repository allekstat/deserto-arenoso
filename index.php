<?php
if (session_status() === PHP_SESSION_NONE)
{
    include 'formulario-login.html';
    exit;
}