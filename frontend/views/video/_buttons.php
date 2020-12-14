<?php
/** @var $model \common\models\Video */

use yii\helpers\Url;

?>

<a href="<?php echo Url::to(['/video/likes', 'id' => $model->video_id]) ?>"
   class="btn btn-sm <?php echo $model->isLikedBy(Yii::$app->user->id) ?
       'btn-outline-primary' : 'btn-outline-secondary'?>"
   data-method="post" data-pjax="1">
    <i class="fas fa-thumbs-up"></i> <?php echo $model->getLikes()->count() ?>
</a>

<a href="<?php echo Url::to(['/video/dislikes', 'id' => $model->video_id]) ?>"
   class="btn btn-sm <?php echo $model->isDisLikedBy(Yii::$app->user->id) ?
       'btn-outline-primary' : 'btn-outline-secondary'?>"
   data-method="post" data-pjax="1">
    <i class="fas fa-thumbs-down"></i> <?php echo $model->getDisLikes()->count() ?>
</a>
