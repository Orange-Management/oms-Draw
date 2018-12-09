<?php

use Modules\Draw\Controller\BackendController;
use Modules\Draw\Models\PermissionState;
use phpOMS\Account\PermissionType;
use phpOMS\Router\RouteVerb;

return [
    '^.*/backend/draw/create.*$' => [
        [
            'dest' => '\Modules\Draw\Controller\BackendController:setUpDrawEditor',
            'verb' => RouteVerb::GET,
            'permission' => [
                'module' => BackendController::MODULE_NAME,
                'type'  => PermissionType::CREATE,
                'state' => PermissionState::DRAW,
            ],
        ],
        [
            'dest' => '\Modules\Draw\Controller\BackendController:viewDrawCreate',
            'verb' => RouteVerb::GET,
            'permission' => [
                'module' => BackendController::MODULE_NAME,
                'type'  => PermissionType::CREATE,
                'state' => PermissionState::DRAW,
            ],
        ],
    ],
    '^.*/backend/draw/list.*$' => [
        [
            'dest' => '\Modules\Draw\Controller\BackendController:viewDrawList',
            'verb' => RouteVerb::GET,
            'permission' => [
                'module' => BackendController::MODULE_NAME,
                'type'  => PermissionType::READ,
                'state' => PermissionState::DRAW,
            ],
        ],
    ],
    '^.*/backend/draw/single.*$' => [
        [
            'dest' => '\Modules\Draw\Controller\BackendController:setUpDrawEditor',
            'verb' => RouteVerb::GET,
            'permission' => [
                'module' => BackendController::MODULE_NAME,
                'type'  => PermissionType::READ,
                'state' => PermissionState::DRAW,
            ],
        ],
        [
            'dest' => '\Modules\Draw\Controller\BackendController:viewDrawSingle',
            'verb' => RouteVerb::GET,
            'permission' => [
                'module' => BackendController::MODULE_NAME,
                'type'  => PermissionType::READ,
                'state' => PermissionState::DRAW,
            ],
        ],
    ],
];
