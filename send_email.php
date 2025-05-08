<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set internal character encoding to UTF-8
mb_internal_encoding("UTF-8");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data and sanitize
    $name = !empty($_POST['name']) ? htmlspecialchars($_POST['name']) : '';
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) ? $_POST['email'] : '';
    $phone = !empty($_POST['phone']) ? htmlspecialchars($_POST['phone']) : '';
    $message = !empty($_POST['message']) ? htmlspecialchars($_POST['message']) : '';

    // Validate form fields
    if (empty($name) || empty($email) || empty($message)) {
        echo json_encode(['status' => 'error', 'message' => 'אנא מלא את כל השדות הנדרשים.']);
        exit();
    }

    // Email details
    $to = 'office@bumpercar.co.il';  
    // Properly encode the subject line
    $subject = mb_encode_mimeheader('התקבל טופס יצירת קשר חדש', 'UTF-8', 'B');

    // Email body in Hebrew
    $body = "
        <h2>טופס יצירת קשר</h2>
        <p><strong>שם:</strong> {$name}</p>
        <p><strong>אימייל:</strong> {$email}</p>
        <p><strong>פלאפון:</strong> {$phone}</p>
        <p><strong>הודעה:</strong> {$message}</p>
    ";

    // Email headers
    $headers = "From: {$email}\r\n";
    $headers .= "Reply-To: {$email}\r\n";
    $headers .= "Content-type: text/html; charset=UTF-8\r\n";

    // Send the email
    if (mail($to, $subject, $body, $headers)) {
        echo json_encode(['status' => 'success', 'message' => 'הודעה נשלחה בהצלחה.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'לא ניתן לשלוח את ההודעה.']);
    }
}
?>
