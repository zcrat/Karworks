<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class SalidasTecnicos implements WithMultipleSheets
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function sheets(): array
    {
        return [
            new SalidasPorPeriodo($this->request),
            new SalidasPorTecnicos($this->request),
        ];
    }
}
