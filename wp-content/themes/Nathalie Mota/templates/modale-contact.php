<?php
/**
 * Template Name: Modal Contact
 */
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact</title>
</head>
<body>
    <div class="overlay">
        <div class="modal-contact">
            <img class="modale-header" src="<?php echo get_template_directory_uri(); ?>/assets/img/contact-header.png" alt="Contact Nathalie Mota">
            <?php echo do_shortcode('[contact-form-7 id="30f62b0" title="Contact form 1"]'); ?>
        </div>
    </div>
</body>
</html>
