<?php

use yii\widgets\ActiveForm;
use frontend\models\JceForm;


?>

<aside class="main-sidebar">

  <section class="sidebar">


    <?= dmstr\widgets\Menu::widget(
      [
        'options' => ['class' => 'sidebar-menu tree', 'data-widget' => 'tree'],
        'items' => [

          [
            'label' => 'Vonage',
            'icon' => 'align-justify',
            'url' => '#',
            'items' => [

                  ['label' => 'Crear', 'icon' => 'circle', 'url' => ['/vonage-config/create'],],
                  ['label' => 'Listado', 'icon' => 'circle', 'url' => ['/vonage-config/index'],],
      
            ]
          ],
          [
            'label' => 'Twilio',
            'icon' => 'align-justify',
            'url' => '#',
            'items' => [

                  ['label' => 'Crear', 'icon' => 'circle', 'url' => ['/twilio-config/create'],],
                  ['label' => 'Listado', 'icon' => 'circle', 'url' => ['/twilio-config/index'],],
      
            ]
          ],
          [
            'label' => 'Combo list',
            'icon' => 'align-justify',
            'url' => '#',
            'items' => [

                  ['label' => 'Insertar combolist', 'icon' => 'circle', 'url' => ['/combo-user/create'],],
                  ['label' => 'Listado', 'icon' => 'circle', 'url' => ['/combo-user/index'],],
      
            ]
          ],
        ],
      ]
    ) ?>

  </section>

</aside>


<style>
  .skin-blue .sidebar-form {
    border: none !important;
  }
</style>