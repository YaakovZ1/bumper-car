<?php
// Set the response header to JSON
header('Content-Type: application/json; charset=UTF-8');

// Define the base directory for the albums
$base_dir = 'assets/images/';
$albums = array();

// Allowed image extensions
$allowed_ext = array('jpg', 'jpeg', 'png', 'gif');

// Check if the base directory exists and is readable
if (!is_dir($base_dir) || !is_readable($base_dir)) {
    echo json_encode(array("error" => "Base directory not found or not readable."), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit;
}

// Open the base directory
if ($dh = opendir($base_dir)) {
    // Iterate through each item in the base directory
    while (($album = readdir($dh)) !== false) {
        // Skip '.' and '..'
        if ($album === '.' || $album === '..') continue;

        $album_dir = $base_dir . $album;
        
        // Check if this is a directory (album)
        if (is_dir($album_dir) && is_readable($album_dir)) {
            if ($adh = opendir($album_dir)) {
                $album_images = array();

                // Iterate through each file in the album directory
                while (($file = readdir($adh)) !== false) {
                    // Skip '.' and '..'
                    if ($file === '.' || $file === '..') continue;

                    // Get the file extension
                    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));

                    // Check if the file is an allowed image
                    if (in_array($ext, $allowed_ext)) {
                        $album_images[] = $album_dir . '/' . $file;
                    }
                }
                closedir($adh);

                // Remove duplicates if any and sort the images naturally
                $album_images = array_unique($album_images);
                natsort($album_images);
                
                // Only add the album if it contains images
                if (!empty($album_images)) {
                    $albums[$album] = array_values($album_images); // Reindex array
                }
            }
        }
    }
    closedir($dh);
}

// If no albums were found, return an empty object
if (empty($albums)) {
    echo json_encode(new stdClass(), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit;
}

// Return the albums as a JSON object
echo json_encode($albums, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
