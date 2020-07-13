<?php
// +----------------------------------------------------------------------
// | ShopXO 国内领先企业级B2C免费开源电商系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011~2019 http://shopxo.net All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: Devil
// +----------------------------------------------------------------------
namespace app\admin\form;

use think\Db;
use app\service\PaymentService;
use app\service\ExpressService;

/**
 * 订单动态表格
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2020-06-08
 * @desc    description
 */
class Order
{
    // 基础条件
    public $condition_base = [
        ['is_delete_time', '=', 0],
    ];

    /**
     * 入口
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-06-08
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public function Run($params = [])
    {
        return [
            // 基础配置
            'base' => [
                'key_field'     => 'id',
                'is_search'     => 1,
                'search_url'    => MyUrl('admin/order/index'),
                'detail_title'  => '基础信息',
            ],
            // 表单配置
            'form' => [
                [
                    'label'         => '订单ID',
                    'view_type'     => 'field',
                    'view_key'      => 'id',
                    'width'         => 105,
                    'search_config' => [
                        'form_type'         => 'input',
                        'where_type'        => '=',
                    ],
                ],
                [
                    'label'         => '订单号',
                    'view_type'     => 'field',
                    'view_key'      => 'order_no',
                    'width'         => 170,
                    'search_config' => [
                        'form_type'         => 'input',
                        'where_type'        => '=',
                    ],
                ],
                [
                    'label'         => '基础信息',
                    'view_type'     => 'module',
                    'view_key'      => 'order/module/goods',
                    'grid_size'     => 'lg',
                    'is_detail'     => 0,
                    'search_config' => [
                        'form_type'             => 'input',
                        'form_name'             => 'id',
                        'where_type'            => 'like',
                        'where_type_custom'     => 'in',
                        'where_handle_custom'   => 'WhereGoodsInfo',
                        'placeholder'           => '请输入商品名称/型号',
                    ],
                ],
                [
                    'label'         => '用户信息',
                    'view_type'     => 'module',
                    'view_key'      => 'lib/module/user',
                    'grid_size'     => 'sm',
                    'search_config' => [
                        'form_type'             => 'input',
                        'form_name'             => 'user_id',
                        'where_type'            => 'like',
                        'where_type_custom'     => 'in',
                        'where_handle_custom'   => 'WhereValueUserInfo',
                        'placeholder'           => '请输入用户名/昵称/手机/邮箱',
                    ],
                ],
                [
                    'label'         => '地址信息',
                    'view_type'     => 'module',
                    'view_key'      => 'order/module/address',
                    'grid_size'     => 'sm',
                    'is_detail'     => 0,
                    'search_config' => [
                        'form_type'             => 'input',
                        'form_name'             => 'id',
                        'where_type'            => 'like',
                        'where_type_custom'     => 'in',
                        'where_handle_custom'   => 'WhereValueAddressInfo',
                    ],
                ],
                [
                    'label'         => '取货信息',
                    'view_type'     => 'module',
                    'view_key'      => 'order/module/take',
                    'width'         => 125,
                    'is_detail'     => 0,
                    'search_config' => [
                        'form_type'             => 'input',
                        'form_name'             => 'id',
                        'where_type'            => 'like',
                        'where_type_custom'     => 'in',
                        'where_handle_custom'   => 'WhereValueTakeInfo',
                    ],
                ],
                [
                    'label'         => '订单状态',
                    'view_type'     => 'module',
                    'view_key'      => 'order/module/status',
                    'width'         => 120,
                    'search_config' => [
                        'form_type'         => 'select',
                        'form_name'         => 'status',
                        'where_type'        => 'in',
                        'data'              => lang('common_order_admin_status'),
                        'data_key'          => 'id',
                        'data_name'         => 'name',
                        'is_multiple'       => 1,
                    ],
                ],
                [
                    'label'         => '支付状态',
                    'view_type'     => 'module',
                    'view_key'      => 'order/module/pay_status',
                    'width'         => 120,
                    'search_config' => [
                        'form_type'         => 'select',
                        'form_name'         => 'pay_status',
                        'where_type'        => 'in',
                        'data'              => lang('common_order_pay_status'),
                        'data_key'          => 'id',
                        'data_name'         => 'name',
                        'is_multiple'       => 1,
                    ],
                ],
                [
                    'label'         => '订单模式',
                    'view_type'     => 'field',
                    'view_key'      => 'order_model',
                    'view_data_key' => 'name',
                    'view_data'     => lang('common_site_type_list'),
                    'width'         => 120,
                    'search_config' => [
                        'form_type'         => 'select',
                        'where_type'        => 'in',
                        'data'              => lang('common_site_type_list'),
                        'data_key'          => 'value',
                        'data_name'         => 'name',
                        'is_multiple'       => 1,
                    ],
                ],
                [
                    'label'         => '来源',
                    'view_type'     => 'field',
                    'view_key'      => 'client_type',
                    'view_data_key' => 'name',
                    'view_data'     => lang('common_platform_type'),
                    'width'         => 120,
                    'search_config' => [
                        'form_type'         => 'select',
                        'where_type'        => 'in',
                        'data'              => lang('common_platform_type'),
                        'data_key'          => 'value',
                        'data_name'         => 'name',
                        'is_multiple'       => 1,
                    ],
                ],
                [
                    'label'         => '单价(元)',
                    'view_type'     => 'field',
                    'view_key'      => 'price',
                    'search_config' => [
                        'form_type'         => 'section',
                        'is_point'          => 1,
                    ],
                ],
                [
                    'label'         => '总价(元)',
                    'view_type'     => 'field',
                    'view_key'      => 'total_price',
                    'search_config' => [
                        'form_type'         => 'section',
                        'is_point'          => 1,
                    ],
                ],
                [
                    'label'         => '支付金额(元)',
                    'view_type'     => 'field',
                    'view_key'      => 'pay_price',
                    'search_config' => [
                        'form_type'         => 'section',
                        'is_point'          => 1,
                    ],
                ],
                [
                    'label'         => '退款金额(元)',
                    'view_type'     => 'field',
                    'view_key'      => 'refund_price',
                    'search_config' => [
                        'form_type'         => 'section',
                        'is_point'          => 1,
                    ],
                ],
                [
                    'label'         => '退货数量',
                    'view_type'     => 'field',
                    'view_key'      => 'returned_quantity',
                    'search_config' => [
                        'form_type'         => 'section',
                    ],
                ],
                [
                    'label'         => '购买总数',
                    'view_type'     => 'field',
                    'view_key'      => 'buy_number_count',
                    'search_config' => [
                        'form_type'         => 'section',
                    ],
                ],
                [
                    'label'         => '增加金额(元)',
                    'view_type'     => 'field',
                    'view_key'      => 'increase_price',
                    'search_config' => [
                        'form_type'         => 'section',
                        'is_point'          => 1,
                    ],
                ],
                [
                    'label'         => '优惠金额(元)',
                    'view_type'     => 'field',
                    'view_key'      => 'preferential_price',
                    'search_config' => [
                        'form_type'         => 'section',
                        'is_point'          => 1,
                    ],
                ],
                [
                    'label'         => '支付方式',
                    'view_type'     => 'field',
                    'view_key'      => 'payment_name',
                    'search_config' => [
                        'form_type'         => 'select',
                        'form_name'         => 'payment_id',
                        'where_type'        => 'in',
                        'data'              => PaymentService::PaymentList(),
                        'data_key'          => 'id',
                        'data_name'         => 'name',
                        'is_multiple'       => 1,
                    ],
                ],
                [
                    'label'         => '用户备注',
                    'view_type'     => 'field',
                    'view_key'      => 'user_note',
                    'width'         => 120,
                    'search_config' => [
                        'form_type'         => 'select',
                        'where_type'        => 'like',
                    ],
                ],
                [
                    'label'         => '扩展信息',
                    'view_type'     => 'module',
                    'view_key'      => 'order/module/extension',
                    'grid_size'     => 'sm',
                    'search_config' => [
                        'form_type'         => 'input',
                        'form_name'         => 'extension_data',
                        'where_type'        => 'like',
                    ],
                ],
                [
                    'label'         => '快递公司',
                    'view_type'     => 'field',
                    'view_key'      => 'express_name',
                    'search_config' => [
                        'form_type'         => 'select',
                        'form_name'         => 'express_id',
                        'data'              => ExpressService::ExpressList(),
                        'where_type'        => 'in',
                        'data_key'          => 'id',
                        'data_name'         => 'name',
                        'is_multiple'       => 1,
                    ],
                ],
                [
                    'label'         => '快递单号',
                    'view_type'     => 'field',
                    'view_key'      => 'express_number',
                    'search_config' => [
                        'form_type'         => 'input',
                        'where_type'        => 'like',
                    ],
                ],
                [
                    'label'         => '最新售后',
                    'view_type'     => 'module',
                    'view_key'      => 'order/module/aftersale',
                    'grid_size'     => 'sm',
                ],
                [
                    'label'         => '确认时间',
                    'view_type'     => 'field',
                    'view_key'      => 'confirm_time',
                    'search_config' => [
                        'form_type'         => 'datetime',
                    ],
                ],
                [
                    'label'         => '支付时间',
                    'view_type'     => 'field',
                    'view_key'      => 'pay_time',
                    'search_config' => [
                        'form_type'         => 'datetime',
                    ],
                ],
                [
                    'label'         => '发货时间',
                    'view_type'     => 'field',
                    'view_key'      => 'delivery_time',
                    'search_config' => [
                        'form_type'         => 'datetime',
                    ],
                ],
                [
                    'label'         => '完成时间',
                    'view_type'     => 'field',
                    'view_key'      => 'collect_time',
                    'search_config' => [
                        'form_type'         => 'datetime',
                    ],
                ],
                [
                    'label'         => '取消时间',
                    'view_type'     => 'field',
                    'view_key'      => 'cancel_time',
                    'search_config' => [
                        'form_type'         => 'datetime',
                    ],
                ],
                [
                    'label'         => '关闭时间',
                    'view_type'     => 'field',
                    'view_key'      => 'close_time',
                    'search_config' => [
                        'form_type'         => 'datetime',
                    ],
                ],
                [
                    'label'         => '创建时间',
                    'view_type'     => 'field',
                    'view_key'      => 'add_time',
                    'search_config' => [
                        'form_type'         => 'datetime',
                    ],
                ],
                [
                    'label'         => '更新时间',
                    'view_type'     => 'field',
                    'view_key'      => 'upd_time',
                    'search_config' => [
                        'form_type'         => 'datetime',
                    ],
                ],
                [
                    'label'         => '操作',
                    'view_type'     => 'operate',
                    'view_key'      => 'order/module/operate',
                    'align'         => 'center',
                    'fixed'         => 'right',
                ],
            ],
        ];
    }

    /**
     * 取货码条件处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-06-08
     * @desc    description
     * @param   [string]          $value    [条件值]
     * @param   [array]           $params   [输入参数]
     */
    public function WhereValueTakeInfo($value, $params = [])
    {
        if(!empty($value))
        {
            // 获取订单 id
            $ids = Db::name('OrderExtractionCode')->where('code', '=', $value)->column('order_id');

            // 避免空条件造成无效的错觉
            return empty($ids) ? [0] : $ids;
        }
        return $value;
    }

    /**
     * 收件地址条件处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-06-08
     * @desc    description
     * @param   [string]          $value    [条件值]
     * @param   [array]           $params   [输入参数]
     */
    public function WhereValueAddressInfo($value, $params = [])
    {
        if(!empty($value))
        {
            // 获取订单 id
            $ids = Db::name('OrderAddress')->where('name|tel|address', 'like', '%'.$value.'%')->column('order_id');

            // 避免空条件造成无效的错觉
            return empty($ids) ? [0] : $ids;
        }
        return $value;
    }

    /**
     * 用户信息条件处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-06-08
     * @desc    description
     * @param   [string]          $value    [条件值]
     * @param   [array]           $params   [输入参数]
     */
    public function WhereValueUserInfo($value, $params = [])
    {
        if(!empty($value))
        {
            // 获取用户 id
            $ids = Db::name('User')->where('username|nickname|mobile|email', 'like', '%'.$value.'%')->column('id');

            // 避免空条件造成无效的错觉
            return empty($ids) ? [0] : $ids;
        }
        return $value;
    }

    /**
     * 商品信息条件处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-06-08
     * @desc    description
     * @param   [string]          $value    [条件值]
     * @param   [array]           $params   [输入参数]
     */
    public function WhereGoodsInfo($value, $params = [])
    {
        if(!empty($value))
        {
            // 获取订单详情搜索的订单 id
            $ids = Db::name('OrderDetail')->where('title|model', 'like', '%'.$value.'%')->column('order_id');

            // 避免空条件造成无效的错觉
            return empty($ids) ? [0] : $ids;
        }
        return $value;
    }
}
?>