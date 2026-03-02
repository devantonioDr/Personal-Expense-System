<?php

use yii\helpers\Html;
use yii\web\View;

/* @var $this \yii\web\View */
/* @var $content string */

frontend\assets\AppAsset::register($this);
$this->registerCssFile(\Yii::getAlias('@web/css/adjunto-foto.css'), ['depends' => [\yii\web\YiiAsset::class]]);
//dmstr\web\AdminLteAsset::register($this);

$directoryAsset = Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/dist');

if (Yii::$app->user->isGuest) {
    Yii::$app->response->redirect(Yii::$app->homeUrl)->send();
    Yii::$app->end();
}

?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">

<head>
    <meta charset="<?= Yii::$app->charset ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>

<body class="hold-transition skin-blue sidebar-mini">
    <?php $this->beginBody() ?>
    <div class="wrapper">

        <?= $this->render(
            'header.php',
            ['directoryAsset' => $directoryAsset]
        ) ?>

        <?= $this->render(
            'left.php',
            ['directoryAsset' => $directoryAsset]
        )
        ?>

        <?= $this->render(
            'content.php',
            ['content' => $content, 'directoryAsset' => $directoryAsset]
        ) ?>

    </div>

    <div id="adjunto-lightbox" class="adjunto-lightbox" role="dialog" aria-label="Vista previa del comprobante" aria-modal="true">
        <div class="adjunto-lightbox-backdrop" aria-label="Cerrar"></div>
        <div class="adjunto-lightbox-content">
            <img id="adjunto-lightbox-img" src="" alt="Comprobante" />
            <button type="button" class="adjunto-lightbox-close" aria-label="Cerrar">&times;</button>
        </div>
    </div>
<?php
$lightboxJs = <<<'LB'
(function(){
    var lb = document.getElementById('adjunto-lightbox');
    var img = document.getElementById('adjunto-lightbox-img');
    var backdrop = lb && lb.querySelector('.adjunto-lightbox-backdrop');
    var closeBtn = lb && lb.querySelector('.adjunto-lightbox-close');
    function openLightbox(url) {
        if (!lb || !img) return;
        img.src = url;
        lb.classList.add('is-open');
        document.body.style.overflow = 'hidden';
    }
    function closeLightbox() {
        if (!lb) return;
        lb.classList.remove('is-open');
        img.removeAttribute('src');
        document.body.style.overflow = '';
    }
    if (backdrop) backdrop.addEventListener('click', closeLightbox);
    if (closeBtn) closeBtn.addEventListener('click', closeLightbox);
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && lb && lb.classList.contains('is-open')) closeLightbox();
    });
    document.addEventListener('click', function(e) {
        var a = e.target.closest && e.target.closest('a.adjunto-lightbox-trigger');
        if (a && a.href) {
            e.preventDefault();
            openLightbox(a.href);
        }
    });
})();
LB;
$this->registerJs($lightboxJs, View::POS_READY);
?>
    <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>