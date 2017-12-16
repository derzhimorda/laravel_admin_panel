<?php

namespace Vmorozov\LaravelAdminGenerator\App\Utils\Export\Strategies;


use Maatwebsite\Excel\Facades\Excel;

class XlsCsvStrategy implements ExportStrategy
{
    private $acceptableFormats = [
        'xlsx', 'xls', 'csv'
    ];

    private $format;
    private $modelClass;

    public function __construct(string $modelClass, string $format = 'xlsx')
    {
        $this->modelClass = $modelClass;

        if (in_array($format, $this->acceptableFormats))
            $this->format = $format;
        else
            throw new \Exception('XlsCsvStrategy error: illegal export format: '.$format);
    }

    public function export()
    {
        $model = new $this->modelClass();
        $models = $model->all();

        Excel::create('Export', function($excel) use ($models) {

            $excel->sheet('Export', function($sheet) use ($models) {
                $sheet->fromModel($models);
            });

        })->download($this->format);
    }
}