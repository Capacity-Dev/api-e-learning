<?php
    $parent="parentPage";
    $title="Accueil";
    //begining page content
    ob_start();
?>

<!-- content of the page-->


<?php
    $content=ob_get_clean();
    ob_start();
?>
<?php
    $scripts=ob_get_clean();