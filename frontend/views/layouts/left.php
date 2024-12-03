<aside class="main-sidebar">
  <section class="sidebar">
    <?= dmstr\widgets\Menu::widget(
      [
        'options' => ['class' => 'sidebar-menu tree', 'data-widget' => 'tree'],
        'items' => [
          [
            'label' => 'Finanzas Personales',
            'icon' => 'align-justify',
            'url' => '#',
            'items' => [
              ['label' => 'Categorias', 'icon' => 'circle', 'url' => ['/categoria-gastos/index'],],
              ['label' => 'Dashboard', 'icon' => 'circle', 'url' => ['/categoria-gastos/dashboard']],
              ['label' => 'Gastos', 'icon' => 'circle', 'url' => ['/gastos/index'],],
              ['label' => 'Ingresos', 'icon' => 'circle', 'url' => ['/ingresos/index'],],
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