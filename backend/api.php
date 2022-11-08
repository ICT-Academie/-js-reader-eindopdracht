<?php
date_default_timezone_set('UTC');

ini_set('display_startup_errors', 0);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__.'/php-error.log');
error_reporting(E_ALL);

require_once __DIR__.'/vendor/autoload.php';
require_once(__DIR__.'/database.php');

if (getenv('DEVELOPMENT')) {
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
}

// Validate parameters before proceeding
if (!isset($_GET['query'])) {
  http_response_code(400);
  die("Error: `query` parameter must be present.");
}

if (!isset($_GET['source']) || !in_array($_GET['source'], ['wiki', 'rune'])) {
  http_response_code(400);
  die("Error: `source` parameter must be present and set to either 'wiki' or 'rune'.");
}


$db_connection = connect_database();

$query = $_GET['query'];
$source = $_GET['source'];

if ($source === 'wiki') {
  $response = wikipedia($query);
}
else {
  $response = runescape($db_connection, $query);
}

header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json; charset=utf-8');
echo json_encode($response);


function runescape(PDO $db_connection, $query) {
  $statement = $db_connection->prepare("SELECT * FROM `item` WHERE `name` LIKE :query ORDER BY `cost` DESC");
  $statement->execute(["query" => "%$query%"]);
  
  return $statement->fetchAll();
}

function wikipedia($query) {
  $query = urlencode($query);
  $response = file_get_contents("https://en.wikipedia.org/w/api.php?action=query&list=search&srsearch=".$query."&utf8=&format=json");
  $decoded_json = json_decode($response);

  return $decoded_json->query->search;
}
