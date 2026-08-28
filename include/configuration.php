<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();  //start the session. Don't write anithing above it, it has to be the first line in php-code
    }

    $baseUrl = '/kinosalonkiSofia';

    /* connection to the database (from now on we use PostgreSQL instead of mySQL, so the syntax is a little different) */
    //$host = "db.wergmxmgcnhnkxejgsdy.supabase.co";
    $host = "aws-0-eu-west-1.pooler.supabase.com";
    $port = "6543";
    $db   = "postgres";
    $user = "postgres.wergmxmgcnhnkxejgsdy";
    $pass = "aECTQXShF21M4uHO";

    try {
        $pdo = new PDO(
            "pgsql:host=$host;port=$port;dbname=$db;sslmode=require",
            $user,
            $pass
        );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);  //enable exception mode for error handling
    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
?> 