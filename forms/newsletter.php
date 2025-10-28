<?php
// === Configuration ===
$to = "your-email@example.com"; // 👉 Replace with your real email address
$subject = "New Subscription";
$from_name = "Ramachandra Handlooms & Tours";
$success_message = "Thank you for subscribing!";
$error_message = "Sorry, something went wrong. Please try again later.";

// === Handle POST Request ===
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(["status" => "error", "message" => "Please enter a valid email address."]);
        exit;
    }

    // === Email Content ===
    $email_body = "
    <html>
    <body>
        <h2>New Subscription Request</h2>
        <p><strong>Email:</strong> {$email}</p>
        <p>Someone just subscribed to updates from Ramachandra Handlooms & Tours.</p>
    </body>
    </html>";

    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8\r\n";
    $headers .= "From: {$from_name} <{$to}>\r\n";
    $headers .= "Reply-To: {$email}\r\n";

    // === Send Email ===
    if (mail($to, $subject, $email_body, $headers)) {
        echo json_encode(["status" => "success", "message" => $success_message]);
    } else {
        echo json_encode(["status" => "error", "message" => $error_message]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request."]);
}
?>
