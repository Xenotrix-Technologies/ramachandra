<?php
// === Configuration ===
$to = "your-email@example.com"; // 👉 Replace this with your real email
$subject_prefix = "New Contact Form Message - ";
$from_name = "Ramachandra Handlooms & Tours";
$success_message = "Your message has been sent successfully!";
$error_message = "Sorry, something went wrong. Please try again later.";

// === Handle Request ===
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = strip_tags(trim($_POST["name"]));
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $subject = strip_tags(trim($_POST["subject"]));
    $phone = isset($_POST["phone"]) ? strip_tags(trim($_POST["phone"])) : "Not provided";
    $message = trim($_POST["message"]);

    // === Validation ===
    if (empty($name) || empty($email) || empty($message)) {
        echo json_encode(["status" => "error", "message" => "Please fill in all required fields."]);
        exit;
    }

    // === Email Content ===
    $email_subject = $subject_prefix . $subject;
    $email_body = "
    <html>
    <body>
        <h2>New Contact Message</h2>
        <p><strong>Name:</strong> {$name}</p>
        <p><strong>Email:</strong> {$email}</p>
        <p><strong>Phone:</strong> {$phone}</p>
        <p><strong>Subject:</strong> {$subject}</p>
        <p><strong>Message:</strong><br>" . nl2br($message) . "</p>
    </body>
    </html>";

    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8\r\n";
    $headers .= "From: {$from_name} <{$email}>\r\n";
    $headers .= "Reply-To: {$email}\r\n";

    // === Send Email ===
    if (mail($to, $email_subject, $email_body, $headers)) {
        echo json_encode(["status" => "success", "message" => $success_message]);
    } else {
        echo json_encode(["status" => "error", "message" => $error_message]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request."]);
}
?>
