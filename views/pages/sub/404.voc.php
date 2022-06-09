<?php
    $parent="parentPage";
    $title="Page Non Trouvé";
    //begining page content
    ob_start();
?>

<!-- content of the page-->
<div class="text-center">
    <div class="error mx-auto" data-text="404">404</div>
    <p class="text-gray-500 mb-0">La page n'a pas été trouvéé</p>
</div>
<?php
    $content=ob_get_clean();
?>