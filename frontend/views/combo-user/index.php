<?php

use frontend\models\ComboUserList;
use frontend\models\ComboUserLogged;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\ComboUserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Combo list';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="combo-user-index box box-primary">
    <div class="box-header with-border">
        <?= Html::a('Insertar lista nueva', ['create'], ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <div class="box-body table-responsive no-padding">
        <?php // echo $this->render('_search', ['model' => $searchModel]); 
        ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            // 'filterModel' => $searchModel,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                
                [
                    'label'=>"Total",
                    'format' => 'html',
                    'contentOptions' => ['style' => 'text-transform:uppercase;'],
                    'value' => function ($data) {
                        
                        $count = number_format( 
                            ComboUserList::find()->where(['combo_user_id'=>$data->id])->count() 
                        );

                        // return HTML::a( $count,'/api/combo_user?GUID='.$data->guId);

                        return $count;
                    }
                ],
                [
                    'label'=>"Logged",
                    'format' => 'html',
                    'contentOptions' => ['style' => 'text-transform:uppercase;'],
                    'value' => function ($data) {
                        
                        $count = number_format( 
                            ComboUserLogged::find()->where(['combo_user_id'=>$data->id])->count() 
                        );

                        // return HTML::a( $count,'/api/combo_user?GUID='.$data->guId);

                        return $count;
                    }
                ],
                [
                    'attribute' => "status",
                    'format' => 'html',
                    'value' => function ($data) {
                        return $this->render('UI/status', ['status' => $data->status]);
                    }
                ],
                'name',
                'guId',
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{download} {update}  {delete}  ', // Show both edit and delete action buttons
                    'buttons' => [
                        'download' => function ($url, $model, $key) {
                            return Html::a('<span class="glyphicon glyphicon-download mr-2"></span>', ['combo-user/download', 'id' => $model->id], [
                                'title' => 'Descargar',
                                'style'=>"margin-left:7px",
                            ]);
                        },
                        'update' => function ($url, $model, $key) {
                            return Html::a('<span class="glyphicon glyphicon-pencil mr-2"></span>', ['combo-user/update', 'id' => $model->id], [
                                'title' => 'Editar',
                                'style'=>"margin-left:7px",
                            ]);
                        },
                        'delete' => function ($url, $model, $key) {
                            return Html::a('<span class="glyphicon glyphicon-trash mr-2"></span>', ['combo-user/delete', 'id' => $model->id], [
                                'title' => 'Borrar',
                                'style'=>"margin-left:7px",
                                'data' => [
                                    'confirm' => '¿Estás seguro/a de que quieres eliminar este elemento?',
                                    'method' => 'post',
                                ],
                            ]);
                        },
                        
                    ],
                ],
                [
                    'header'=>"Opciones para logged",
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{download} {delete}  ',
                    'buttons' => [
                        'download' => function ($url, $model, $key) {
                            return Html::a('<span class="glyphicon glyphicon-download mr-2"></span>', ['combo-user-logged/download', 'id' => $model->id], [
                                'title' => 'Descargar',
                                'style'=>"margin-left:7px",
                            ]);
                        },
                        'delete' => function ($url, $model, $key) {
                            return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['combo-user-logged/delete', 'id' => $model->id], [
                                'title' => 'Borrar',
                                'style'=>"margin-left:7px",
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