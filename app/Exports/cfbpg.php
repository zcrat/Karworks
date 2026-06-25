<?php

namespace App\Exports;

use App\presupuestos;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
class cfbpg  implements FromCollection, WithHeadings,WithStyles
{
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $buscar = $this->request->buscar;
        $criterio = $this->request->criterio;
        $fecha_inicio = $this->request->fecha_inicio;
        $fecha_final = $this->request->fecha_final;
        $not=$buscar ? 'LIKE' : 'NOT LIKE';

        $buscar=$buscar ? $buscar : 5;

        if($criterio=='num_comprobante')
        {
            $criterio='presupuestos.status';
        }
        $cotizacionesQuery = presupuestos::join('pVehiculos','presupuestos.pVehiculos_id','=','pVehiculos.id')
        ->join('pGenerales','presupuestos.pGenerales_id','=','pGenerales.id')
        ->join('empresas','presupuestos.empresa_id','=','empresas.id')
        ->join('estatus', 'estatus.id', '=', 'presupuestos.status')
        ->join('pcarrito', 'pcarrito.presupuesto_id', '=', 'presupuestos.id')
        ->select('pGenerales.NSolicitud','pVehiculos.identificador','pVehiculos.marca','pVehiculos.modelo', 'pVehiculos.ano','pVehiculos.placas','pVehiculos.vin','pGenerales.FechaAlta','empresas.nombre as empresas',
        DB::raw('SUM(pcarrito.cantidad * pcarrito.precio_v) as SubTotal'),
        DB::raw('(SUM(pcarrito.cantidad * pcarrito.precio_v)) * 0.16 as Iva'),
        DB::raw('(SUM(pcarrito.cantidad * pcarrito.precio_v)) * 1.16 as Total'),
        'estatus.nombre' )
        ->where($criterio, $not, '%' . $buscar . '%')
        ->groupBy(
            'pGenerales.NSolicitud',
            'pVehiculos.identificador',
            'pVehiculos.marca',
            'pVehiculos.modelo',
            'pVehiculos.ano',
            'pVehiculos.placas',
            'pVehiculos.vin',
            'pGenerales.FechaAlta',
            'empresas.nombre',
            'estatus.nombre'
        );
        if (!empty($fecha_inicio)) {
            $cotizacionesQuery->where('presupuestos.created_at', '>',$fecha_inicio);
        }
        if (!empty($fecha_final)) {
            $cotizacionesQuery->where('presupuestos.created_at', '<',$fecha_final);
        }

        return $cotizacionesQuery->orderBy('presupuestos.status', 'asc')->orderBy('presupuestos.id', 'desc')->get();
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
}
