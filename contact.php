<?php 
$title = "Contact";
include_once("includes/header.php");
include_once("includes/navbar.php");

$name = $email = $subject = $message = '';
$name_err = $email_err = $subject_err = $message_err = '';
$to = 'ahdelacy@gmail.com';
$headers = 'From: contact@morrophotography.rf.gd';

//Server-side email validation
function emailCheck($address){
    //Sanitize email address using FILTER_SANITIZE_EMAIL
    $address = filter_var($address, FILTER_SANITIZE_EMAIL);

    //If it passes email validation
    if(filter_var($address, FILTER_VALIDATE_EMAIL)){
        return true;

    } else {
        return false;
    }
}

//If we got POSTed the contact form
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    //Validate and sanitize inputs
    $input_email = $_POST['email'];
    if (!empty($input_email) && emailCheck($input_email)) {
        $email = filter_var($input_email, FILTER_SANITIZE_EMAIL);
    } else {
        $email_err = "Please enter a valid email address.";
    } 

    $input_name = $_POST['name'];
    if (!empty($input_name) && preg_match("/^([a-zA-Z' \-]+)$/",$input_name)) {
        $name = $input_name;
    } else {
        $name_err = "Please enter your name.";
    }

    $input_message = $_POST['message'];
    if (!empty(filter_var($input_message, FILTER_SANITIZE_STRING))) {
        $message = wordwrap((filter_var($input_message, FILTER_SANITIZE_STRING)), 70, "\r\n");
    } else if (empty($input_message)) {
        $message_err = "Please enter a message to send.";
    } else {
        $message_err = "There was a problem sending your message. Please try again.";
    }

    $input_subject = $_POST['subject'];
    if (!empty(filter_var($input_subject, FILTER_SANITIZE_STRING))) {
        $subject = filter_var($input_subject, FILTER_SANITIZE_STRING);
    } else if (empty($input_subject)) {
        $subject_err = "Please enter a subject for your message.";
    } else {
        $subject_err = "There was a problem processing your subject line. Please try again.";
    }

    //If there aren't any errors, process and send the email
    if (empty($name_err) && empty($email_err) && empty($message_err) && empty($subject_err)) {
        $subject_line = "Message from '" . $name . "' at " . $email . " on Morrow Photography: " . $subject;
        mail($to, $subject_line, $message, $headers);
    }
}

?>
<div class="page-container">
<main class="main">
    <div class="container">
        <h2 class="text-center">Contact Me</h2>
        <br>
        <p>You're welcome to contact me via email using the form below, or you can send me a private message using any of my social media profiles.</p>
            <p><a href="#"><strong>Facebook:</strong> CMPhotography</a></p>
            <p><a href="#"><strong>Twitter:</strong> @CMPhotography</a></p>
        <br>
        <br>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" id="contact">
            <div class="form-group">
                <label for="name">Your Name</label>
                <input type="text" id="name" name="name" class="form-control<?php echo (!empty($name_err)) ? ' is-invalid' : ''; ?>" value="<?php echo $name?>">
                <span class="invalid-feedback"><?php echo $name_err;?></span>
            </div>
            <br>
            <div class="form-group">
                <label for="email">Your Email Address</label>
                <input type="text" id="email" name="email" class="form-control<?php echo (!empty($email_err)) ? ' is-invalid' : ''; ?>" aria-describedby="emailHelp" value="<?php echo $email?>">
                <div id="emailHelp" class="form-text">Your email will only be used to reply to your query, and never shared.</div>
                <span class="invalid-feedback"><?php echo $email_err;?></span>
            </div>
            <br>
            <div class="form-group">
                <label for="subject">Subject</label>
                <input type="text" id="subject" name="subject" class="form-control<?php echo (!empty($subject_err)) ? ' is-invalid' : ''; ?>" value="<?php echo $subject?>">
                <span class="invalid-feedback"><?php echo $subject_err;?></span>
            </div>
            <br>
            <div class="form-group">
                <label for="message">Message</label>
                <textarea id="message" name="message" class="form-control<?php echo (!empty($message_err)) ? ' is-invalid' : ''; ?>"><?php echo $message?></textarea>
                <span class="invalid-feedback"><?php echo $message_err;?></span>
            </div>
            <br>
            <button class="btn btn-dark btn-submit" type="submit">Submit</button>
        </form>
    </div>

<!--Validation library-->
<script src="//cdnjs.cloudflare.com/ajax/libs/validate.js/0.13.1/validate.min.js"></script>

<!--Client-side form validation-->
<script>
    //Set up constraints
    const constraints = {
        name: {
            presence: { allowEmpty: false }
        },
        email: {
            presence: { allowEmpty: false },
            email: true
        },
        subject: {
            presence: { allowEmpty: false}
        },
        message: {
            presence: { allowEmpty: false }
        }
    };

    //Select form by ID
    const form = document.getElementById('contact');

    //On form submission
    form.addEventListener('submit', function(event){ 

        //Grab values from form
        const formValues = {
            name: form.elements.name.value,
            email: form.elements.email.value,
            subject: form.elements.subject.value,
            message: form.elements.message.value
        };

        //Validate form values against constraints
        const errors = validate(formValues, constraints);

        //If there are errors
        if (errors) {
            //Prevent form submission
            event.preventDefault();

            const errorMessage = Object
                .values(errors)
                .map(function (fieldValues) {
                    return fieldValues.join(', ')
                })
                .join("\n");

            alert(errorMessage);
        }
    }, false);
    </script>
<?php
include_once("includes/footer.php");
?>
</main>
</div>
</body>