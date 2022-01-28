<?php
/** @var $channel \common\models\User */
/** @var $user \common\models\User */
/** @var $comment  string */
?>

Hello <?php echo $channel->username ?>
User <?php echo  \common\helpers\Html::channelLink($user, true) ?>
    has mention you in the following comment


    <?php echo $comment ?>


YouTubeClone Team
