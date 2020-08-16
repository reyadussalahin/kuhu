<?php
// this template provides utility for reply
// i.e.
// (1) reply-container(which contains reply cells)
// (2) reply-form

require_once("components/user/authentication.php");

?>

<div class="reply-utility --kuhu-margin-left-32px">
    <?php
        include("templates/reply/replyContainer.php");
    ?>

    <?php
        // if(isAuthenticated()):
            include("templates/reply/replyForm.php");
        // endif;
    ?>

</div>