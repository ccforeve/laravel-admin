<?php

namespace App\Admin\Extensions\Excel;

use App\Models\Specification;
use Encore\Admin\Grid\Exporters\AbstractExporter;
use Maatwebsite\Excel\Facades\Excel;

class ExcelExpoter extends AbstractExporter
{
    private $filename;  //导出文件名称

    public function __construct( $filename )
    {
        $this->filename = $filename;
    }

    public function export()
    {
        Excel::create($this->filename, function($excel) {
            $excel->sheet($this->filename, function($sheet) {
                $newarr[0] = ['订单id', '商品类型', '商品名称', '商品规格', '包装', '收件人姓名', '收件人手机号', '收件人详细地址', '订单备注', '支付时间', '是否是活动订单'];
                foreach ($this->getData() as $key => $value) {
                    $newarr[$key+1]['id'] = $value['id'];
                    if($value['product_type'] == 1) {
                        $newarr[ $key + 1 ][ 'product_type' ] = '免费领取';
                    } elseif($value['product_type'] == 2) {
                        $newarr[ $key + 1 ][ 'product_type' ] = '套装';
                    } elseif($value['product_type'] == 3) {
                        $newarr[ $key + 1 ][ 'product_type' ] = '体验商品';
                    }
                    $newarr[$key+1]['product_name'] = $value['product']['name'];
                    if($value['order_attr']) {
                        if ( $value[ 'order_attr' ][ 'spec' ] ) {
                            $spec = Specification::find($value[ 'order_attr' ][ 'spec' ]);
                            $newarr[$key+1][ 'spec' ] = $spec->name;
//                            dump($spec->name);
                        } else {
                            $newarr[ $key + 1 ][ 'spec' ] = '';
                        }
                    } else {
                        $newarr[$key+1][ 'spec' ] = '';
                    }

                    if($value['order_attr']) {
                        if($value['order_attr']['packing'] == 1){
                            $newarr[$key+1][ 'packing' ] = '不包装';
                        } else {
                            $newarr[$key+1][ 'packing' ] = '包装';
                        }
                    } else {
                        $newarr[$key+1][ 'packing' ] = '';
                    }
                    $newarr[$key+1]['name'] = $value['address']['name'];
                    $newarr[$key+1]['phone'] = $value['address']['phone'];
                    $newarr[$key+1]['address'] = $value['address']['province'] . $value['address']['detail'];
                    $newarr[$key+1]['remark'] = $value['remark'];
                    $newarr[$key+1]['pay_at'] = $value['pay_at'];
                    if($value['activity']) {
                        $newarr[ $key + 1 ][ 'activity' ] = '是';
                    } else {
                        $newarr[ $key + 1 ][ 'activity' ] = '否';
                    }
                }
                $sheet->rows($newarr);

            });

        })->export('xls');
    }
}