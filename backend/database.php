<?php

$dotenv = Dotenv\Dotenv::createUnsafeImmutable(__DIR__ . '/');
$dotenv->safeLoad();

function connect_database(): PDO {
  $db_options = [
    \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
  ];

  return new PDO(
    'mysql:host=' . getenv('JSE_DB_HOSTNAME') . ';dbname=' . getenv('JSE_DB_NAME'),
    getenv('JSE_DB_USERNAME'),
    getenv('JSE_DB_PASSWORD'),
    $db_options
  );
}
