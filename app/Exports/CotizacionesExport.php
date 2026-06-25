<?php

namespace App\Exports;

use App\presupuestosCFE;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Facades\DB;
class CotizacionesExport implements FromCollection, WithHeadings, WithStyles
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $buscar = $this->request->buscar;
        $criterio = $this->request->criterio;
        $contrato = $this->request->contrato;
        $fecha_inicio = $this->request->fecha_inicio;
        $fecha_final = $this->request->fecha_final;
        $cfe = $this->request->cfeid;
        $not=$buscar ? 'LIKE' : 'NOT LIKE';
        $buscar=$buscar ? $buscar : 5;
        if($contrato==0){
            $contrato='';
        }
        if($criterio=='num_comprobante')
        {
            $criterio='presupuestosCFE.status';
        }
        $cotizacionesQuery = presupuestosCFE::join('pCFEVehiculos', 'presupuestosCFE.pCFEVehiculos_id', '=', 'pCFEVehiculos.id')
        ->join('pCFEGenerales', 'presupuestosCFE.pCFEGenerales_id', '=', 'pCFEGenerales.id')
        ->join('users', 'presupuestosCFE.user_id', '=', 'users.id')
        ->join('sucursales', 'users.sucursal_id', '=', 'sucursales.id')
        ->join('contratos', 'sucursales.contratos_id', '=', 'contratos.id')
        ->join('pcfecarrito', 'pcfecarrito.presupuestoCFE_id', '=', 'presupuestosCFE.id')
        ->join('estatus', 'estatus.id', '=', 'presupuestosCFE.status')
        ->select(
            'pCFEGenerales.NSolicitud',
            'pCFEVehiculos.identificador',
            'pCFEVehiculos.marca',
            'pCFEVehiculos.modelo',
            'pCFEVehiculos.ano',
            'pCFEVehiculos.placas',
            'pCFEVehiculos.vin',
            'pCFEGenerales.FechaAlta',
            'contratos.numero as contrato',
            DB::raw('SUM(pcfecarrito.cantidad * pcfecarrito.precio_v) as SubTotal'),
            DB::raw('(SUM(pcfecarrito.cantidad * pcfecarrito.precio_v)) * 0.16 as Iva'),
            DB::raw('(SUM(pcfecarrito.cantidad * pcfecarrito.precio_v)) * 1.16 as Total'),
            'estatus.nombre'
        )
        ->where('presupuestosCFE.CFE_id', '=', $cfe)
        ->where($criterio, $not, '%' . $buscar . '%')
        ->where('contratos.id', 'like', '%' . $contrato . '%')
        ->where('presupuestosCFE.id_anio_correspondiente', '=', '2')
        ->groupBy(
            'pCFEGenerales.NSolicitud',
            'pCFEVehiculos.identificador',
            'pCFEVehiculos.marca',
            'pCFEVehiculos.modelo',
            'pCFEVehiculos.ano',
            'pCFEVehiculos.placas',
            'pCFEVehiculos.vin',
            'pCFEGenerales.FechaAlta',
            'contratos.numero',
            'estatus.nombre'
        );
    
        


        if (!empty($fecha_inicio)) {
            $cotizacionesQuery->where('presupuestosCFE.created_at', '>',$fecha_inicio);
        }
        if (!empty($fecha_final)) {
            $cotizacionesQuery->where('presupuestosCFE.created_at', '<',$fecha_final);
        }

        return $cotizacionesQuery->orderBy('presupuestosCFE.status', 'asc')->orderBy('presupuestosCFE.id', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'Folio','Economico','Marca','Modelo','Año','Placas','VIN','Fecha','Contrato','SubTotal','Iva','Total','Estatus'
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
