<?php

function formatErrors($errors)
{
    if (is_iterable($errors)) {
        echo "Error information: <br/>";

        foreach ($errors as $error) {
            echo "Code: " . $error['code'] . "<br/>";
            echo "Message: " . $error['message'] . "<br/>";
        }
    } else {
        echo $errors;
    }
}

/**
 * Sanitize user input to prevent XSS and SQL injection.
 * @param string $data The input data
 * @return string Sanitized data
 */
function testInput($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function alertMsg($message)
{
    echo "<script>alert('$message');</script>";
}

function var_dump_pre($mixed = null)
{
    echo '<pre>';
    var_dump($mixed);
    echo '</pre>';
    return null;
}

function var_dump_ret($mixed = null)
{
    ob_start();
    var_dump($mixed);
    $content = ob_get_contents();
    ob_end_clean();
    return $content;
}

/**
 * Global exception handler.
 * Logs errors and optionally displays an error message.
 * @param Exception $e The caught exception
 */
function globalExceptionHandler($exception)
{
    // You can log the exception to a file or database here
    error_log('Uncaught Exception: ' . $exception->getMessage());

    // Display a user-friendly error message
    echo '<div style="padding: 10px; background-color: #f44336; color: white;">';
    echo '<strong>Exception:</strong> ' . $exception->getMessage() . '<br/>';
    echo '<strong>Line:</strong> ' . $exception->getLine() . '<br/>';
    echo '<strong>File:</strong> ' . $exception->getFile() . '<br/>';
    echo '</div>';
}

// Set the global exception handler
set_exception_handler('globalExceptionHandler');

/**
 * Generate a random reset code (6-digit number).
 * @return string Random reset code
 */
function generateResetCode()
{
    return str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
}

function maskEmail($email)
{
    $parts = explode("@", $email);
    $username = $parts[0];
    $domain = $parts[1];

    // Show first and last character of the username, mask the rest
    $maskedUsername = substr($username, 0, 1) . str_repeat("*", max(0, strlen($username) - 2)) . substr($username, -1);

    // Show first letter of domain and the full domain extension
    $domainParts = explode(".", $domain);
    $maskedDomain = substr($domainParts[0], 0, 1) . str_repeat("*", max(0, strlen($domainParts[0]) - 1)) . "." . $domainParts[1];

    return $maskedUsername . "@" . $maskedDomain;
}
