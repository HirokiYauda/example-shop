<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Gross price as base price
    |--------------------------------------------------------------------------
    |
    | This default value is used to select the method to calculate prices and taxes
    | If true the item price is managed as a gross price, so taxes will be calculated by separation/exclusion
    |
    */

    'calculator' => \Gloudemans\Shoppingcart\Calculation\DefaultCalculator::class,

    /*
    |--------------------------------------------------------------------------
    | Default tax rate
    |--------------------------------------------------------------------------
    |
    | This default tax rate will be used when you make a class implement the
    | Taxable interface and use the HasTax trait.
    |
    */

    'tax' => 10,

    /*
    |--------------------------------------------------------------------------
    | Shoppingcart database settings
    |--------------------------------------------------------------------------
    |
    | Here you can set the connection that the shoppingcart should use when
    | storing and restoring a cart.
    |
    */

    'database' => [

        'connection' => null,

        'table' => 'shoppingcart',

    ],

    /*
    |--------------------------------------------------------------------------
    | Destroy the cart on user logout
    |--------------------------------------------------------------------------
    |
    | When this option is set to 'true' the cart will automatically
    | destroy all cart instances when the user logs out.
    |
    */

    'destroy_on_logout' => false,

    /*
    |--------------------------------------------------------------------------
    | Default number format
    |--------------------------------------------------------------------------
    |
    | This defaults will be used for the formatted numbers if you don't
    | set them in the method call.
    |
    */

    'format' => [

        'decimals' => 0,

        'decimal_point' => '',

        'thousand_separator' => ',',

    ],
    'default_cart_count'                => 10, // カートに入れられる商品数
    'default_max_qty'                   => 99, // 最大で購入可能な数量
    'not_exist_product_in_cart_message' => 'カートに商品が存在しません。',
    'no_stock_caution_message'          => '在庫がありません。', // 在庫なしメッセージ
    'max_qty_caution_message'           => '購入可能な数量を超えています。', // 商品購入可能数超過メッセージ
    'cart_count_caution_message'        => 'カートに入れられる商品数を超えています。', // カートに入れられる数量超過
    'update_error_message'              => "カートを正常に更新できませんでした。お手数ですが、トップページへ戻って、再度購入をお願いいたします。", // カート更新失敗メッセージ
    'display_remaining_inventory_count' => 10 // 残り在庫数を告知する数量設定
];
