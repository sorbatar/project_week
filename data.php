<?php
header("Content-Type: application/json; charset=UTF-8");

// Verbindingsgegevens voor je database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "energy_db";

// Maak verbinding met de database
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Voer een query uit om de benodigde gegevens op te halen
$sql = "SELECT * FROM energy_users";
$result = $conn->query($sql);

// Maak een array om de opgehaalde gegevens op te slaan
$data = array();

// Voeg de opgehaalde gegevens toe aan de array
if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
    $data[] = $row;
  }
} else {
  echo "0 results";
}

// Sluit de databaseverbinding
$conn->close();

// Stuur de gegevens als JSON-formaat naar de client
echo json_encode($data);
?>