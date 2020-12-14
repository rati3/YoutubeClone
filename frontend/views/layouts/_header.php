<?php

use yii\bootstrap4\Nav;
use yii\bootstrap4\NavBar;

NavBar::begin([
    'brandLabel' => Yii::$app->name,
    'brandUrl' => Yii::$app->homeUrl,
    'options' => ['class' => 'navbar navbar-expand-lg navbar-light bg-light shadow-sm'],
    'innerContainerOptions' => [
        'class' => 'container-fluid'
    ]
]);

if (Yii::$app->user->isGuest) {
    $menuItems[] = ['label' => 'Signup', 'url' => ['/site/signup']];
    $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
} else {
    $menuItems[] = [
        'label' => 'Logout (' . Yii::$app->user->identity->username . ')',
        'url' => ['/site/logout'],
        'linkOptions' => [
            'data-method' => 'post'
        ]
    ];
}
?>

<form action="<?php echo \yii\helpers\Url::to(['/video/search']) ?>"
      class="d-flex">
    <input class="form-control me-2" type="search"
           placeholder="Search" name="keyword">
    <button class="btn btn-outline-success" >Search</button>
</form>

<?php echo Nav::widget([
    'options' => ['class' => 'navbar-nav ml-auto'],
    'items' => $menuItems,
]);
NavBar::end();

