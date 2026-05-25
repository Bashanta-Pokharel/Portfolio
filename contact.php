<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

function respond(int $status, string $message): void
{
    http_response_code($status);
    echo json_encode(['message' => $message]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    respond(405, 'Only POST requests are allowed.');
}

if (!empty($_POST['website'])) {
    respond(200, 'Thank you. Your message has been received.');
}

$lastMessageTime = $_SESSION['last_message_time'] ?? 0;
if (time() - $lastMessageTime < 30) {
    respond(429, 'Please wait a moment before sending another message.');
}

$name = trim((string) ($_POST['name'] ?? ''));
$email = trim((string) ($_POST['email'] ?? ''));
$subject = trim((string) ($_POST['subject'] ?? 'Portfolio Message'));
$message = trim((string) ($_POST['message'] ?? ''));

if ($name === '' || $email === '' || $subject === '' || $message === '') {
    respond(422, 'Please fill in all required fields.');
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    respond(422, 'Please enter a valid email address.');
}

$email = str_replace(["\r", "\n"], '', $email);
$subject = str_replace(["\r", "\n"], ' ', $subject);

if (strlen($name) > 100 || strlen($subject) > 140 || strlen($message) > 2500) {
    respond(422, 'Please keep your message concise and try again.');
}

$safeName = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
$safeSubject = htmlspecialchars($subject, ENT_QUOTES, 'UTF-8');
$safeMessage = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');

$recipient = 'ggrgdgdge26@gmail.com';
$mailSubject = "Portfolio Contact: {$subject}";
$body = "Name: {$safeName}\nEmail: {$email}\nSubject: {$safeSubject}\n\nMessage:\n{$safeMessage}\n";
$headers = [
    'From: Bashanta Portfolio <ggrgdgdge26@gmail.com>',
    "Reply-To: {$email}",
    'Content-Type: text/plain; charset=UTF-8',
    'X-Mailer: PHP/' . phpversion(),
];

$sent = @mail($recipient, $mailSubject, $body, implode("\r\n", $headers));

if (!$sent) {
    // Local fallback helps during XAMPP/WAMP development when mail() is not configured.
    $logDir = __DIR__ . DIRECTORY_SEPARATOR . 'submissions';
    if (!is_dir($logDir)) {
        @mkdir($logDir, 0755, true);
    }

    $entry = json_encode([
        'time' => date(DATE_ATOM),
        'name' => $safeName,
        'email' => $email,
        'subject' => $safeSubject,
        'message' => $safeMessage,
    ], JSON_UNESCAPED_SLASHES);

    if ($entry === false || @file_put_contents($logDir . DIRECTORY_SEPARATOR . 'contact-messages.jsonl', $entry . PHP_EOL, FILE_APPEND | LOCK_EX) === false) {
        respond(500, 'Message could not be sent. Please email me directly.');
    }

    $_SESSION['last_message_time'] = time();
    respond(202, 'Email delivery failed in XAMPP, so your message was saved locally in submissions/contact-messages.jsonl.');
}

$_SESSION['last_message_time'] = time();
respond(200, 'Thank you. Your email was sent successfully.');
?>
