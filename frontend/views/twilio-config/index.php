<?php

use common\components\MyHelpers;
use frontend\access\TwilioConfigAccess;
use frontend\models\CallHistory;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Configuraciónes de Twilio';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php echo $this->render('_summary', ['total_spent' => $total_spent]); ?>

<div class="twilio-config-index box box-primary">
    <div class="box-header with-border">
        <?= Html::a('Crear una configuración de Twilio', ['create'], ['class' => 'btn btn-success btn-flat']) ?>
        <?= Html::a('Refresh Balance', ['refresh-balance'], ['class' => 'btn btn-danger btn-flat']) ?>
    </div>
    <div class="box-body table-responsive no-padding">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                // 'id',
                // 'userId',
                'etiqueta',
                [
                    'attribute' => 'spent_today',
                    'format' => 'html',
                    'value' => function ($data) {
                        return "$ " . number_format($data->spent_today, 2);
                    }
                ],
                [
                    'attribute' => 'active',
                    'format' => 'raw',
                    'value' => function ($data) {
                        return $this->render("UI/_activate_check", ['data' => $data]);
                    }
                ],
                [
                    'attribute' => 'last_purchase_time',
                    'format' => 'raw',
                    'value' => function ($data) {
                        if($data->last_purchase_time == 0){
                            return "N/A";
                        }
                        return MyHelpers::timeAgo($data->last_purchase_time);
                    }
                ],
                [
                    'label' => "Aff Hits",
                    'format' => 'raw',
                    'visible'=> TwilioConfigAccess::getInstance()->canViewAffirmHits(),
                    'value' => function ($data) {
                        return CallHistory::find()->where(['twilio_config_id' => $data->id])
                            ->andWhere(['type' => 'affirm.hit'])
                            ->andFilterWhere(['>=', 'created_at', strtotime('today midnight')])
                            ->andFilterWhere(['<', 'created_at', strtotime('tomorrow midnight')])
                            ->count();
                    }
                ],
                [
                    'label' => "Venmo Hits",
                    'format' => 'raw',
                    'visible'=> TwilioConfigAccess::getInstance()->canViewVenmoHits(),
                    'value' => function ($data) {
                        return CallHistory::find()->where(['twilio_config_id' => $data->id])
                            ->andWhere(['type' => 'venmo.hit'])
                            ->andFilterWhere(['>=', 'created_at', strtotime('today midnight')])
                            ->andFilterWhere(['<', 'created_at', strtotime('tomorrow midnight')])
                            ->count();
                    }
                ],
                'guId',
                'account_sid',
                // 'auth_token',
                // 'numbers:ntext',

                // ['class' => 'yii\grid\ActionColumn'],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{update}  {delete}  ', // Show both edit and delete action buttons
                    'buttons' => [

                        'update' => function ($url, $model, $key) {
                            return Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['twilio-config/update', 'id' => $model->id], [
                                'title' => 'Editar',
                            ]);
                        },
                        'delete' => function ($url, $model, $key) {
                            return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['twilio-config/delete', 'id' => $model->id], [
                                'title' => 'Borrar',
                                'data' => [
                                    'confirm' => '¿Estás seguro/a de que quieres eliminar este elemento?',
                                    'method' => 'post',
                                ],
                            ]);
                        },

                    ],
                ]
            ],
        ]); ?>
    </div>
</div>

<script>
    function updateConfig(id, checked) {
        $.ajax({
            url: '/twilio-config/activate?id=' + id,
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