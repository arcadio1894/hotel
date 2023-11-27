<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ReportCustomerExport implements WithMultipleSheets
{

    protected $data;
    protected $deletedData;

    public function __construct(array $data, array $deletedData)
    {
        $this->data = $data;
        $this->deletedData = $deletedData;
    }

    public function sheets(): array
    {
        $sheets = [];

        
        $sheets[] = new ReportCustomerSheet($this->data);
        

        // Add sheet for deleted records
        $sheets[] = new DeletedReportCustomerSheet($this->deletedData);

        return $sheets;
    }

}
