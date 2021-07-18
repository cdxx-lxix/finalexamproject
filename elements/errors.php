<?php

function errorChecker($target) {

    switch ($target) {
        case 'magic':
            // Bad symbols
            errorBodyPrinter('warning', 'Oops!', 'Username must contain only a-z, A-Z, 0-9 symbols.');
            break;

        case 'drum':
            // Passwords don't match
            errorBodyPrinter('warning', 'Oops!', "Your passwords don't match. Try again.");
            break;

        case 'old-drum':
            // Password check failed
            errorBodyPrinter('warning', 'Oops!', "Wrong current password.");
            break;

        case 'same':
            // Old and new passwords can't be the same
            errorBodyPrinter('warning', 'Oops!', "Old and new passwords can't be the same. Come up with something new!");
            break;

        case 'violin':
            // Username is not unique
            errorBodyPrinter('warning', 'Oops!', "This username is already occupied! Try different one.");
            break;

        case 'beetle':
            // Failed to prepare a statement for db
            errorBodyPrinter('danger', 'Warning!', "Sorry, it's our bad! Try again.");
            break;

        case 'fire':
            // Wrong password or username. Any page, during login
            errorBodyPrinter('danger', 'Warning!', "Wrong username or password! Try again.");
            break;

        case 'none':
            // Registration successful
            errorBodyPrinter('success', 'YEAH!', "You are now ascended to a user! Now sign in!");
            break;

        case 'person':
            // Profile info updated
            errorBodyPrinter('success', 'YEAH!', "Profile info updated successfully!");
            break;

        case 'guest':
            // User tried to perform action that is made for registered only
            errorBodyPrinter('info', 'Nope!', "You are a guest. <b>Sign in</b> or <b>Sign up</b> to do that!");
            break;

        case 'fox':
            // User tried to access processor page
            errorBodyPrinter('info', 'Nope!', "Staff only!");
            break;

        case 'sheep':
            // User tried to restore password when already logged in
            errorBodyPrinter('info', 'Nope!', "What do you want to restore if you are already logged in?");
            break;

        case 'ghost':
            // No user with such email
            errorBodyPrinter('warning', 'Warning!', "There is no user with such email.");
            break;

        case 'message':
            // Restoration email sent successfully
            errorBodyPrinter('success', 'YEAH!', "Check your email for restoration link.");
            break;

        case 'retry':
            // User needs to resubmit their request
            errorBodyPrinter('warning', 'Oops!', "Something went wrong. You need to resubmit your request.");
            break;

        case 'change':
            // Password changed successfully
            errorBodyPrinter('success', 'YEAH!', "Enjoy your brand new password and don't forget it!");
            break;

        case 'void':
            // No image or user tried to access avatarProcessor
            errorBodyPrinter('warning', 'Oops!', "You can't upload emptiness! Choose and image for yourself.");
            break;

        case 'child':
            // Post created
            errorBodyPrinter('success', 'YEAH!', "Your post successfully created!");
            break;

        case 'contact':
            // Contact me form message successfully sent
            errorBodyPrinter('success', 'YEAH!', "Your message is on it's way!");
            break;

        case 'dead':
            // Post deleted successful
            errorBodyPrinter('success', 'Bye bye!', "Your post successfully casted into oblivion!");
            break;

        case 'reborn';
            // Post edit successful
            errorBodyPrinter('success', 'YEAH!', "Your post updated!");
            break;

        default:
            // Unknown shit happened
            errorBodyPrinter('dark', 'WTF?!', "Unknown problem happened");
            break;
    }
}

/**
 * Echoes an alert div with message of a given type. Dismissible.
 * @param $type - primary/secondary/success/danger/warning/info/light/dark (Converts to lowercase, so don't bother)
 * @param $preMessage - first bold words b4 actual message
 * @param $message - actual message
 */
function errorBodyPrinter(string $type, string $preMessage, string $message) {
    $type = strtolower($type);
    echo "<div class='alert alert-$type alert-dismissible fade show' role='alert'>
              <strong>$preMessage</strong> $message
              <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                <span aria-hidden='true'>&times;</span>
              </button>
          </div>";
}
