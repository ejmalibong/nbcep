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

function testInput($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function alert($message)
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

// Exception handler function
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
