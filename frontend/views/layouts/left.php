<aside class="main-sidebar">
  <section class="sidebar">
    <?= dmstr\widgets\Menu::widget(
      [
        'options' => ['class' => 'sidebar-menu tree', 'data-widget' => 'tree'],
        'items' => [
          [
            'label' => 'Gastos',
            'icon' => 'align-justify',
            'url' => '#',
            'items' => [
              ['label' => 'Crear', 'icon' => 'circle', 'url' => ['/gastos/create'],],
              ['label' => 'Lista', 'icon' => 'circle', 'url' => ['/gastos/index'],],
            ]
          ]
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