<?php
/** @var  $channel User */
/** @var $user User */

use common\models\User;

?>

<p> Hello <?php echo $channel->username ?> </p>
<p> User <?php echo  \common\helpers\Html::channelLink($user, true) ?>
    has subscribed to you</p>

<p> YouTubeClone Team </p>
