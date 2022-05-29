<?php
return [
    'SystemProperties' => [
        'RoleList' => [
            0 => ROLE_READ,
            1 => ROLE_WRITE,
            2 => ROLE_DELETE,
            3 => ROLE_CSV_EXPORT,
            4 => ROLE_CSV_IMPORT,
            5 => ROLE_EXCEL_EXPORT,
            6 => ROLE_EXCEL_IMPORT,
        ],
        'RoleBadgeClass' => [
            ROLE_READ => 'badge bg-primary',
            ROLE_WRITE => 'badge bg-danger',
            ROLE_DELETE => 'badge bg-warning text-white',
            ROLE_CSV_EXPORT => 'badge bg-info',
            ROLE_CSV_IMPORT => 'badge bg-success',
            ROLE_EXCEL_EXPORT => 'badge bg-info',
            ROLE_EXCEL_IMPORT => 'badge bg-success',
        ],
    ],
    'BakedFunctions' => [
        'Accounts' => '口座',
        'Configs' => '設定',
        'DailyRecords' => '資産記録',
        'Deposits' => '入出金',
    ],
    'Codes' => [
        'Calendars' => [
            'is_holiday' => [
                1 => 'true',
                0 => 'false',
            ],
        ],
        'Configs' => [
            'display_only_month' => [
                DISPLAY_ONLY_MONTH_ON => 'ON',
                '02' => 'OFF',
            ],
            'display_init_record' => [
                DISPLAY_INIT_RECORD_ON => '表示する',
                '02' => '表示しない',
            ],
            'display_setting' => [
                'date' => '日付',
                'record_total_real' => '実質資産',
                'prev_day_diff_value' => '前営業日比',
                'prev_day_diff_rate' => '前営業日比(%)',
                'prev_month_diff_value' => '単月成績',
                'prev_month_diff_rate' => '単月成績(%)',
                'beginning_year_diff_value' => '年初来成績',
                'beginning_year_diff_rate' => '年初来成績(%)',
                'deposit_day_ammount' => '入出金',
                'record_total' => '証券口座合計',
                'account_records' => '口座ごとの資産',
                'account_new_link' => '口座の新規追加リンク',
            ],
            'display_setting_for_handsontable' => [
                'date' => [
                    'header_text' => '日付',
                    'columns_format' => [
                        'type' => 'date',
                        'dateFormat' => 'YYYY-MM-DD',
                    ],
                ],
                'record_total_real' => [
                    'header_text' => '実質資産',
                    'columns_format' => [
                        'renderer' => 'html',
                        'className' => 'htRight',
                        'columnSorting' => [
                            'compareFunctionFactory' => null,
                        ]
                    ],
                ],
                'prev_day_diff_value' => [
                    'header_text' => '前営業日比',
                    'columns_format' => [
                        'renderer' => 'html',
                        'className' => 'htRight',
                        'columnSorting' => [
                            'compareFunctionFactory' => null,
                        ]
                    ],
                ],
                'prev_day_diff_rate' => [
                    'header_text' => '前営業日比(%)',
                    'columns_format' => [
                        'renderer' => 'html',
                        'className' => 'htRight',
                        'columnSorting' => [
                            'compareFunctionFactory' => null,
                        ]
                    ],
                ],
                'prev_month_diff_value' => [
                    'header_text' => '単月成績',
                    'columns_format' => [
                        'renderer' => 'html',
                        'className' => 'htRight',
                        'columnSorting' => [
                            'compareFunctionFactory' => null,
                        ]
                    ],
                ],
                'prev_month_diff_rate' => [
                    'header_text' => '単月成績(%)',
                    'columns_format' => [
                        'renderer' => 'html',
                        'className' => 'htRight',
                        'columnSorting' => [
                            'compareFunctionFactory' => null,
                        ]
                    ],
                ],
                'beginning_year_diff_value' => [
                    'header_text' => '年初来成績',
                    'columns_format' => [
                        'renderer' => 'html',
                        'className' => 'htRight',
                        'columnSorting' => [
                            'compareFunctionFactory' => null,
                        ]
                    ],
                ],
                'beginning_year_diff_rate' => [
                    'header_text' => '年初来成績(%)',
                    'columns_format' => [
                        'renderer' => 'html',
                        'className' => 'htRight',
                        'columnSorting' => [
                            'compareFunctionFactory' => null,
                        ]
                    ],
                ],
                'deposit_day_ammount' => [
                    'header_text' => '入出金',
                    'columns_format' => [
                        'renderer' => 'html',
                        'className' => 'htRight',
                        'columnSorting' => [
                            'compareFunctionFactory' => null,
                        ]
                    ],
                ],
                'record_total' => [
                    'header_text' => '証券口座合計',
                    'columns_format' => [
                        'renderer' => 'html',
                        'className' => 'htRight',
                        'columnSorting' => [
                            'compareFunctionFactory' => null,
                        ]
                    ],
                ],
                'account_records' => [
                    'header_text' => '',
                    'columns_format' => [
                        'renderer' => 'html',
                        'className' => 'htRight',
                        'columnSorting' => [
                            'compareFunctionFactory' => null,
                        ]
                    ],
                ],
                'account_new_link' => [
                    'header_text' => '',
                    'columns_format' => [],
                ],
            ],
        ],
        'Display' => [
            // 単位表示ON
            'display_unit_on' => '1',
            // 入出金を含めるON
            'include_deposit_on' => '1',
        ],
    ],
    'HeaderConfig' => [
    ],
    'FooterConfig' => [
        'buttons' => [
        ],
        'copylight' => [
            'from' => '2021',
            'text' => 'StockPerformance',
            'link' => 'https://github.com/imo-tikuwa/stock-performance-app-publish',
        ],
    ],
    'LeftSideMenu' => [
        'Accounts' => [
            'controller' => 'Accounts',
            'label' => '口座',
            'icon_class' => 'fas fa-piggy-bank',
        ],
        'Deposits' => [
            'controller' => 'Deposits',
            'label' => '入出金',
            'icon_class' => 'fas fa-money-bill',
        ],
        'DailyRecords' => [
            'controller' => 'DailyRecords',
            'label' => '資産記録',
            'icon_class' => 'fas fa-cloud-sun',
        ],
        'Configs' => [
            'controller' => 'Configs',
            'label' => '設定',
            'icon_class' => 'fas fa-user-cog',
        ],
    ],
    'ExcelOptions' => [
        'Accounts' => [
            'version' => '725f0aa297973b19152e5bf3afa317bea66c1d412c636ad38d7581bec98b6e02c443b44fb13c5ef0a133a28b814a479c9072227508e96f9005ab1d8560d61ec0',
        ],
        'Deposits' => [
            'version' => '7fbaa01a5cd07e1afd8e36e96ee34629de57d3a3099e1f18a860e9879526a3112269ead0a471a0ea0462c64ee255d45559e49645be1be8e151207ab64deeabf6',
        ],
    ],
    'InitialOrders' => [
        'Accounts' => [
            'sort' => 'id',
            'direction' => 'asc',
        ],
        'Configs' => [
            'sort' => 'id',
            'direction' => 'asc',
        ],
        'DailyRecords' => [
            'sort' => 'id',
            'direction' => 'asc',
        ],
        'Deposits' => [
            'sort' => 'id',
            'direction' => 'asc',
        ],
    ],
    'AdminRoles' => [
        'Accounts' => [
            ROLE_READ => '口座読込',
            ROLE_WRITE => '口座書込',
            ROLE_DELETE => '口座削除',
            ROLE_CSV_EXPORT => '口座CSVエクスポート',
        ],
        'Deposits' => [
            ROLE_READ => '入出金読込',
            ROLE_WRITE => '入出金書込',
            ROLE_DELETE => '入出金削除',
            ROLE_CSV_EXPORT => '入出金CSVエクスポート',
        ],
        'DailyRecords' => [
            ROLE_READ => '資産記録読込',
            ROLE_WRITE => '資産記録書込',
            ROLE_DELETE => '資産記録削除',
            ROLE_CSV_EXPORT => '資産記録CSVエクスポート',
        ],
        'Configs' => [
            ROLE_WRITE => '設定書込',
        ],
    ],
    'SwaggerConfigs' => [
    ],
    'Others' => [
        'search_snippet_format' => [
            'AND' => ' AND',
            'OR' => ' OR',
        ],
    ],
];
