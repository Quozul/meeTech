<?php
$headers = apache_request_headers();

// Verify that the header exists
if (!isset($headers['X-Hub-Signature'])) {
    echo "You're not allowed to execute this action!<br>Your IP address is: " . $_SERVER['REMOTE_ADDR'];
} else {
    $secret = '>2[@gj7aG@U}(f)fn.G}A3t(?aC{/UxB';
    $hash = 'sha1=' . hash_hmac('sha1', file_get_contents("php://input"), $secret);

    // Verify the hash
    if (hash_equals($hash, $headers['X-Hub-Signature'])) {
        echo "Updated successfully! Thanks for your visit :)\n";
        // Pull from origin, master branch
        echo exec('git pull origin master');
    }
}
