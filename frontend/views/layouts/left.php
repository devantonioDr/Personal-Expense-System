<aside class="main-sidebar">
  <section class="sidebar">
    <?php
    use common\services\MenuService;

    $menuItems = MenuService::getMenuItems();
    ?>
    <?= dmstr\widgets\Menu::widget(
      [
        'options' => ['class' => 'sidebar-menu tree', 'data-widget' => 'tree'],
        'items' => $menuItems,
      ]
    ) ?>
  </section>
</aside>


<style>
  .skin-blue .sidebar-form {
    border: none !important;
  }
</style>
