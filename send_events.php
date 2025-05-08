<?php
// Enable error reporting for debugging (disable in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Set internal character encoding to UTF-8
mb_internal_encoding("UTF-8");

// Set the content type to JSON and specify UTF-8 encoding
header('Content-Type: application/json; charset=UTF-8');

try {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Retrieve and sanitize form data
        $name = htmlspecialchars(trim(isset($_POST['name']) ? $_POST['name'] : ''), ENT_QUOTES, 'UTF-8');
        $email_raw = trim(isset($_POST['email']) ? $_POST['email'] : '');
        $email = filter_var($email_raw, FILTER_VALIDATE_EMAIL) ? $email_raw : '';
        $phone = htmlspecialchars(trim(isset($_POST['phone']) ? $_POST['phone'] : ''), ENT_QUOTES, 'UTF-8');
        $date = htmlspecialchars(trim(isset($_POST['date']) ? $_POST['date'] : ''), ENT_QUOTES, 'UTF-8');
        $details = htmlspecialchars(trim(isset($_POST['details']) ? $_POST['details'] : ''), ENT_QUOTES, 'UTF-8');

        // Validate required fields
        if (empty($name) || empty($email) || empty($phone) || empty($date) || empty($details)) {
            echo json_encode(['status' => 'error', 'message' => 'אנא מלא את כל השדות הנדרשים.']);
            exit();
        }

        // Construct the email
        $subject = 'התקבל טופס הזמנת אירוע חדש';
        $subject_encoded = '=?UTF-8?B?' . base64_encode($subject) . '?=';

        $body = "
            <html>
            <head>
                <meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
            </head>
            <body dir='rtl' style='text-align: right; font-family: Arial, sans-serif;'>
                <h2>בקשת הזמנת אירוע חדשה</h2>
                <p><strong>שם:</strong> {$name}</p>
                <p><strong>אימייל:</strong> {$email}</p>
                <p><strong>טלפון:</strong> {$phone}</p>
                <p><strong>תאריך האירוע:</strong> {$date}</p>
                <p><strong>פרטי האירוע:</strong> {$details}</p>
            </body>
            </html>
        ";

        // Email headers
        $to = 'office@bumpercar.co.il';  // Replace with your recipient email

        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        $headers .= "Content-Transfer-Encoding: 8bit\r\n";
        $headers .= "From: {$email}\r\n";
        $headers .= "Reply-To: {$email}\r\n";
        $headers .= "Subject: {$subject_encoded}\r\n";

        // Send the email
        if (mail($to, '', $body, $headers)) {
            echo json_encode(['status' => 'success', 'message' => 'ההודעה נשלחה בהצלחה.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'שליחת המייל נכשלה. אנא נסה שוב מאוחר יותר.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'שיטת בקשה לא תקינה.']);
    }
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'שגיאת שרת: ' . $e->getMessage()]);
}
?>
