<?php

use common\components\MyHelpers;
use frontend\access\VonageConfigAccess;
use frontend\models\CallHistory;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Vonage Configs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vonage-config-index box box-primary">
    <div class="box-header with-border">
        <?= Html::a('Crear una configuración de vonage', ['create'], ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <div class="box-body table-responsive no-padding">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'id',
                // 'user_id',
                'etiqueta',
                [
                    'attribute' => 'active',
                    'format' => 'raw',
                    'value' => function ($data) {
                        return $this->render("UI/_activate_check", ['data' => $data]);
                    }
                ],
                [
                    'label' => "Aff Hits",
                    'format' => 'raw',
                    'visible'=> VonageConfigAccess::getInstance()->canViewAffirmHits(),
                    'value' => function ($data) {
                        return CallHistory::find()->where(['vonage_config_id' => $data->id])
                            ->andWhere(['type' => 'affirm.hit'])
                            ->andFilterWhere(['>=', 'created_at', strtotime('today midnight')])
                            ->andFilterWhere(['<', 'created_at', strtotime('tomorrow midnight')])
                            ->count();
                    }
                ],
                [
                    'label' => "Venmo Hits",
                    'format' => 'raw',
                    'visible'=> VonageConfigAccess::getInstance()->canViewVenmoHits(),
                    'value' => function ($data) {
                        return CallHistory::find()->where(['vonage_config_id' => $data->id])
                            ->andWhere(['type' => 'venmo.hit'])
                            ->andFilterWhere(['>=', 'created_at', strtotime('today midnight')])
                            ->andFilterWhere(['<', 'created_at', strtotime('tomorrow midnight')])
                            ->count();
                    }
                ],
                [
                    'label' => "Last Purchase Date",
                    'attribute' => 'last_purchase_date',
                    'format' => 'raw',
                    'value' => function ($data) {
                        if ($data->last_purchase_date == 0) {
                            return "N/A";
                        }
                        return MyHelpers::timeAgo($data->last_purchase_date);
                    }
                ],
                'guId',
                'vonage_app_id',
                // 'test_phone',
                // 'private_key',
                // 'numbers',

                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>
    </div>
</div>
<script>
    function updateConfig(id, checked) {
        $.ajax({
            url: '/vonage-config/activate?id=' + id,
            type: 'POST',
            data: {
                checked
            },
            success: function(response) {
                console.log(response);
                // Handle the response
            },
            error: function(error) {
                console.error('Error:', error);
            }
        });
    }
    setTimeout(function() {

        $(".activate_config").attr('disabled', false);

        $(".activate_config").click(function(event) {
            let id = $(this).data("id");
            if ($(this).prop("checked") == true) {
                updateConfig(id, true);
                return;
            }
            updateConfig(id, false);
        });

    }, 500);
</script>