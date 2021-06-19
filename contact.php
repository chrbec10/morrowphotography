<?php 
$title = "Contact";
include_once("includes/header.php");
include_once("includes/navbar.php");


/*
$to = 'ahdelacy@gmail.com';
$subject = trim($_POST['subject']);
$message = trim($_POST['message']);
$return = trim($_POST['email']);
$headers = "From: cmphotography@cmp.com";

$message = wordwrap($message, 70, "\r\n");
$subject .= ' - from ' . $return;

mail($to, $subject, $message, $headers);
*/

?>
<div class="page-container">
<main class="main">
    <div class="container">
        <h2>Contact Me</h2>
        <p>You're welcome to contact me via email using the form below, or you can send me a private message using any of my social media profiles.</p>
            <p><a href="#"><strong>Facebook:</strong> CMPhotography</a></p>
            <p><a href="#"><strong>Twitter:</strong> @CMPhotography</a></p>
        <br>
        <br>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" id="contact">
            <div class="form-group">
                <label for="name">Your Name</label>
                <input type="text" id="name" name="name" class="form-control">
            </div>
            <br>
            <div class="form-group">
                <label for="email">Your Email Address</label>
                <input type="text" id="email" name="email" class="form-control" aria-describedby="emailHelp">
                <div id="emailHelp" class="form-text">Your email will only be used to reply to your query, and never shared.</div>
            </div>
            <br>
            <div class="form-group">
                <label for="subject">Subject</label>
                <input type="text" id="subject" name="subject" class="form-control">
            </div>
            <br>
            <div class="form-group">
                <label for="message">Message</label>
                <textarea id="message" name="message" class="form-control"></textarea>
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