<?php
$connection = mysqli_connect("localhost","root","","oron"); 
if (!$connection) {
    die("Connection Failed: " . mysqli_connect_error());
}

// SQL query to select profile data
$sql = "SELECT username, avatar_url, bio, level, exp, poin, achievements_achieved FROM profiles";
$result = $connection->query($sql);

// Check if any profiles are available
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div class='profile'>";
        echo "<h2>" . htmlspecialchars($row['username']) . "</h2>";
        echo "<img src='" . htmlspecialchars($row['avatar_url']) . "' alt='Avatar' width='100' height='100'>";
        echo "<p><strong>Bio:</strong> " . htmlspecialchars($row['bio']) . "</p>";
        echo "<p><strong>Level:</strong> " . $row['level'] . "</p>";
        echo "<p><strong>Experience:</strong> " . $row['exp'] . "</p>";
        echo "<p><strong>Points:</strong> " . $row['poin'] . "</p>";
        echo "<p><strong>Achievements:</strong> " . $row['achievements_achieved'] . "</p>";
        echo "</div>";
    }
} else {
    echo "No profiles found.";
}

$connection->close();
?>