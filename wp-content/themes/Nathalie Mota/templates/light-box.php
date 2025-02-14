<?php
/**
 * Template Name: Light Box
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
    <div class="lightbox">
        <div class="lbTop">
            <span class="cross">&#9587;</span>
        </div>
        <div class="lbMiddle">
            <div class="lbPrevious">
                <span class="lbLeftArrow">&#8592;</span> <span class="precedente">Précédente</span>
            </div>
            <div class="lbPhotos">
                <img class="lbCurrentPhoto" src="" alt="photo">
                <div class="lbBottom">
                    <span class="overlay-ref"><?php the_field('reference'); ?></span>
                    <span class="overlay-category"><?php
                        $terms = get_the_terms(get_the_ID(), 'category');
                        foreach ($terms as $term) {
                        echo $term->name . ' ';
                        }
                    ?></span>
                </div>
            </div>
            <div class="lbNext">
                <span class="suivante">Suivante</span> <span class="lbRightArrow">&#8594;</span>
            </div>
        </div>
    </div>
</body>
</html>