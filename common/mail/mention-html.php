<?php
/** @var $channel \common\models\User */
/** @var $user \common\models\User */
/** @var $comment  string */
?>

<p> Hello <?php echo $channel->username ?> </p>
<p> User <?php echo  \common\helpers\Html::channelLink($user, true) ?>
    has mention you in the following comment</p>

<blockquote>
    <?php echo $comment ?>
</blockquote>

<p> YouTubeClone Team </p>
