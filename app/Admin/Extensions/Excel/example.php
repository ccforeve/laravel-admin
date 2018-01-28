<?php

namespace App\Admin\Extensions\Excel;

use Encore\Admin\Grid\Exporters\AbstractExporter;
use Maatwebsite\Excel\Facades\Excel;

class example extends AbstractExporter
{
    private $filename;  //导出文件名称

    private $expoter;   //需要导出的数据（array）格式：['0' => ['id'=>'id']]

    public function __construct( $filename, $expoter )
    {
        $this->filename = $filename;
        $this->expoter = $expoter;
    }

    public function export()
    {
        Excel::create($this->filename, function($excel) {
            $excel->sheet($this->filename, function($sheet) {
                $arr = array_merge($this->expoter, $this->getData());
//                // 这段逻辑是从表格数据中取出需要导出的字段
                $rows = collect($arr)->map(function ($item) {
                    return array_only($item, array_values($this->expoter[0]));
                });
                $sheet->rows($rows);

            });

        })->export('xls');
    }
}