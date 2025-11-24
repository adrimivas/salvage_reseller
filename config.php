<?php
$user = "kdubey"; // replace with your Artemis DB username
$pass = "Sevh2ypol"; // replace with your Artemis DB password
$db   = "kdubey"; // replace with your database name

// Establish a PDO connection
function get_pdo() {
    global $user, $pass, $db;
    $host = "localhost";

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        echo "<p><strong>Connection failed:</strong> " . $e->getMessage() . "</p>";
        die();
    }
}

// Enable error output to help with debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Helper function to create an HTML table from query results
function makeTable($rows) {
    if (empty($rows)) {
        return "<p>No results found.</p>";
    }

    $html = "<table border='1'><thead><tr>";
    foreach ($rows[0] as $col => $val) {
        $html .= "<th>$col</th>";
    }
    $html .= "</tr></thead><tbody>";

    foreach ($rows as $row) {
        $html .= "<tr>";
        foreach ($row as $val) {
            $html .= "<td>$val</td>";
        }
        $html .= "</tr>";
    }

    $html .= "</tbody></table>";
    return $html;
}
?>
