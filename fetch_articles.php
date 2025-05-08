<?php
// Database connection (MySQL)
$servername = 'localhost:3306';
$username = 'webadmin';
$password = 'L124o#n9q';
$dbname = 'ContactForm';



$conn = new mysqli($servername, $username, $password, $dbname);

// Set the charset to utf8mb4
$conn->set_charset("utf8");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to get articles
$sql = "SELECT id, title, date, excerpt, image_url, article_url FROM articles ORDER BY date DESC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output the articles in HTML format
    while($row = $result->fetch_assoc()) {
        echo '<article class="article-card">';
        echo '<a href="' . $row["article_url"] . '">';
        echo '<img src="' . $row["image_url"] . '" alt="Article Image" class="article-image">';
        echo '</a>';
        echo '<div class="article-content">';
        echo '<a href="' . $row["article_url"] . '">';
        echo '<h2 class="article-title">' . $row["title"] . '</h2>';
        echo '</a>';
        echo '<p class="article-date">' . date("d M, Y", strtotime($row["date"])) . '</p>';
        echo '<p class="article-excerpt">' . $row["excerpt"] . '</p>';
        echo '</div>';
        echo '</article>';
    }
} else {
    echo "<p>No articles found</p>";
}

$conn->close();
?>