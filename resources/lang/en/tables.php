<?php
return [
    'module' => [
        'title' => 'Module managment',
        'field' => [
            'name' => 'Name',
            'description' => 'Description',
            'status' => 'Status',
            'title' => 'Title',
            'key' => 'Key'
        ]
    ],
    'plugin' => [
        'title' => 'Plugin managment',
        'field' => [
            'name' => 'Name',
            'description' => 'Description',
            'title' => 'Title',
            'status' => 'Status'
        ]
    ],
    'theme' => [
        'title' => 'Theme managment',
        'field' => [
            'key' => 'Key',
            'name' => 'Name',
            'description' => 'Description',
            'status' => 'Status',
            'title' => 'Title',
            'admin' => 'admin'
        ]
    ],
    'user' => [
        'title' => 'User managment',
        'field' => [
            'name' => 'Name',
            'description' => 'Description',
            'status' => 'Status',
            'info' => 'Info',
            'email' => 'Email',
            'avatar' => 'Avatar',
            'password' => 'Password'
        ],
        'message' => [
            'unactivated' => 'UnActivated'
        ],
        'button' => [
            'permission' => 'Permission',
            'change-password' => 'Change Password'
        ]
    ],
    'role' => [
        'title' => 'Role managment',
        'field' => [
            'name' => 'Name',
            'slug' => 'Slug',
            'status' => 'Status',

        ],
        'message' => [
            'unactivated' => 'UnActivated'
        ],
        'button' => [
            'permission' => 'Permission'
        ]
    ],
    'permission' => [
        'title' => 'Permission managment',
        'field' => [
            'name' => 'Name',
            'slug' => 'slug',
            'group' => 'group',

        ],
        'message' => [
            'unactivated' => 'UnActivated'
        ],
        'button' => [
            'load' => 'Load'
        ]
    ],
    'schedule' => [
        'title' => 'Schedule managment'
    ],
    'schedule_history' => [
        'title' => 'Schedule history managment'
    ],
    'data_list' => [
        'title' => 'Data List',
        'field' => [
            'key' => 'Key',
            'title' => 'Title',
            'content' => 'Content',
            'status' => 'Status',
            'item_default' => 'Item Default',
        ],
        'button' => [
            'list' => 'Item List'
        ]
    ],
    'data_item' => [
        'title' => 'Item List',
        'field' => [
            'link' => 'Link',
            'title' => 'Title',
            'image' => 'Image',
            'content_short' => 'Content Short',
            'content' => 'Content',
            'status' => 'Status',
            'value' => 'Value',
            'item_default' => 'Item Default',
            'sort' => 'Sort',
        ],
        'button' => []
    ],
    'custom_field_group' => [
        'title' => 'Custom Field Group',
        'field' => [
            'key' => 'key',
            'title' => 'Title',
            'module_key' => 'Module',
            'model_key' => 'Content Short',
            'content' => 'Content',
            'status' => 'Status',
            'value' => 'Value',
            'item_default' => 'Item Default',
            'sort' => 'Sort',
        ],
        'button' => [
            'list' => 'Item List'
        ]
    ],
    'custom_field_item' => [
        'title' => 'Custom Field',
        'field' => [
            'key' => 'key',
            'title' => 'Title',
            'list_key' => 'List Key',
            'list_data' => 'List Data',
            'type' => 'Type',
            'placeholder' => 'Placeholder',
            'prepend' => 'prepend',
            'append' => 'append',
            'character_limit' => 'character_limit',
            'required' => 'required',
            'default' => 'default',
            'format' => 'Format',
            'module_key' => 'Module',
            'model_key' => 'Content Short',
            'content' => 'Content',
            'status' => 'Status',
            'value' => 'Value',
            'sort' => 'Sort',
        ],
        'button' => [
            'list' => 'Item List'
        ]
    ]

];
