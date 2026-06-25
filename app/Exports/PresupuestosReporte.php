<?php

namespace App\Exports;

use App\Models\Presupuesto;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Facades\DB;

class PresupuestosReporte implements FromCollection, WithHeadings, WithStyles,WithEvents
{
    protected $ids;

    public function __construct( $ids)
    {
        $this->ids = $ids;
    }

    public function collection()
    {
        $elements = Presupuesto::withTrashed()->with(['detallesGenerales:id,Empresa_id,Vehiculo_id','detallesGenerales.vehiculo','detallesGenerales.empresa','estatus:id,nombre'])
        ->select('presupuestosnuevos.DetallesGenerales_id','presupuestosnuevos.created_at','presupuestosnuevos.id','presupuestosnuevos.Folio','presupuestosnuevos.Status_id','presupuestosnuevos.Tipo_id',
        DB::raw('ROUND(SUM(carrito.Cantidad * carrito.Venta), 2) as SubTotal'),
        DB::raw('ROUND(SUM(carrito.Cantidad * carrito.Venta)*0.16, 2) as Iva'),
        DB::raw('ROUND(SUM(carrito.Cantidad * carrito.Venta)*1.16, 2) as Total'),)
        ->leftjoin('Presupuesto_Carrito as carrito','presupuestosnuevos.id','=','carrito.presupuesto_id')
        ->whereNull('carrito.deleted_at')
        ->whereIn('presupuestosnuevos.id', $this->ids)
        ->groupBy('presupuestosnuevos.DetallesGenerales_id',
        'presupuestosnuevos.id',
        'presupuestosnuevos.Folio',
        'presupuestosnuevos.Status_id',
        'presupuestosnuevos.created_at',
        'presupuestosnuevos.Tipo_id')
        ->orderBy('id','desc')->get();

        return $elements->map(function ($element) {
            $detalles = $element->detallesGenerales;
            $vehiculo = $detalles->vehiculo;
            $empresa = $detalles->empresa;
            
            return [
                'Folio' => $element->Folio,
                'Economico' => $vehiculo ? $vehiculo->no_economico : '',
                'Marca' => $vehiculo ? $vehiculo->marca->nombre : '',
                'Modelo' => $vehiculo ? $vehiculo->modelo->nombre : '',
                'Año' => $vehiculo ? $vehiculo->anio : '',
                'Placas' => $vehiculo ? $vehiculo->placas : '',
                'VIN' => $vehiculo ? $vehiculo->vim : '',
                'Fecha' => date('Y-m-d', strtotime($element->created_at)),
                'Empresa' => $empresa ? $empresa->nombre : '',
                'SubTotal' => number_format($element->SubTotal, 2),
                'Iva' => number_format($element->Iva, 2),
                'Total' => number_format($element->Total, 2),
                'Estatus' => $element->estatus ? $element->estatus->nombre : ''
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Folio','Economico','Marca','Modelo','Año','Placas','VIN','Fecha','Empresa','SubTotal','Iva','Total','Estatus'
        ];
    }
    public function styles(Worksheet $sheet)
    {
        return [
            // Negrita en los encabezados
            1 => ['font' => ['bold' => true]],


        ];
    }
     public function registerEvents(): array
{
    return [
        AfterSheet::class => function (AfterSheet $event) {

            foreach (range('A', 'N') as $col) {
                $event->sheet->getDelegate()->getColumnDimension($col)->setAutoSize(true);
            }
        }
    ];
}
}
