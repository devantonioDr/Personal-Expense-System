<?php

namespace common\services;

use Yii;

class MenuService
{
    /**
     * Items para el menú lateral (sidebar).
     * @param int|null $userId Si null, usa el usuario logueado.
     * @return array
     */
    public static function getMenuItems(?int $userId = null): array
    {
        $route = Yii::$app->controller->route ?? '';
        $currentProyectoId = Yii::$app->request->get('proyecto_id');

        $isActivePersonal = function ($r) use ($route, $currentProyectoId) {
            return $route === $r && (empty($currentProyectoId) && $currentProyectoId !== '0');
        };
        $isActiveProyecto = function ($r, $proyectoId) use ($route, $currentProyectoId) {
            return $route === $r && (string) $currentProyectoId === (string) $proyectoId;
        };

        $menuItems = [
            // [
            //     'label' => 'Finanzas Personales',
            //     'icon' => 'align-justify',
            //     'url' => '#',
            //     'items' => [
            //         ['label' => 'Categorias', 'icon' => 'circle', 'url' => ['/categoria-gastos/index'], 'active' => $isActivePersonal('categoria-gastos/index')],
            //         ['label' => 'Dashboard', 'icon' => 'circle', 'url' => ['/dashboard/index'], 'active' => $isActivePersonal('dashboard/index')],
            //         ['label' => 'Gastos', 'icon' => 'circle', 'url' => ['/gastos/index'], 'active' => $isActivePersonal('gastos/index')],
            //         ['label' => 'Ingresos', 'icon' => 'circle', 'url' => ['/ingresos/index'], 'active' => $isActivePersonal('ingresos/index')],
            //     ]
            // ],
        ];

        $proyectos = ProyectoService::getProyectosByUserId($userId);
        foreach ($proyectos as $proyecto) {
            $menuItems[] = [
                'label' => $proyecto->nombre,
                'icon' => 'folder-o',
                'url' => '#',
                'items' => [
                    ['label' => 'Dashboard', 'icon' => 'circle', 'url' => ['/dashboard/index', 'proyecto_id' => $proyecto->id], 'active' => $isActiveProyecto('dashboard/index', $proyecto->id)],
                    ['label' => 'Gastos', 'icon' => 'circle', 'url' => ['/gastos/index', 'proyecto_id' => $proyecto->id], 'active' => $isActiveProyecto('gastos/index', $proyecto->id)],
                    ['label' => 'Ingresos', 'icon' => 'circle', 'url' => ['/ingresos/index', 'proyecto_id' => $proyecto->id], 'active' => $isActiveProyecto('ingresos/index', $proyecto->id)],
                ],
            ];
        }

        $userRol = null;
        if (!Yii::$app->user->isGuest && Yii::$app->user->identity) {
            $userRol = Yii::$app->user->identity->user_rol ?? null;
        }
        $isOperador = ($userRol === \common\models\User::ROLE_OPERADOR);

        if (!$isOperador) {
            $menuItems[] = [
                'label' => 'Proyectos',
                'icon' => 'folder-o',
                'url' => ['/proyecto/index'],
                'active' => $route === 'proyecto/index',
            ];

            $menuItems[] = [
                'label' => 'Usuarios',
                'icon' => 'user',
                'url' => ['/usuario/index'],
                'active' => strpos($route, 'usuario/') === 0,
            ];
        }

        return $menuItems;
    }
}
