<?php

namespace App\Exports;


use App\anexosForaneos;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithStyles;
class cfbforaneos implements FromCollection,WithHeadings,WithStyles
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
        $eco = $this->request->eco_id;
        $not=$buscar ? 'LIKE' : 'NOT LIKE';
        $buscar=$buscar ? $buscar : 5;

        if($criterio=='num_comprobante')
        {
            $criterio='anexosforaneos.status';
        }


        $cotizacionesQuery = anexosForaneos::join('anexosFVehiculos','anexosforaneos.pVehiculos_id','=','anexosFVehiculos.id')
            ->join('anexosFGenerales','anexosforaneos.pGenerales_id','=','anexosFGenerales.id')
            ->join('empresas','anexosforaneos.empresa_id','=','empresas.id')
            ->join('estatus', 'estatus.id', '=', 'anexosforaneos.status')
            ->join('anexosfcarrito', 'anexosfcarrito.presupuesto_id', '=', 'anexosforaneos.id')
            ->select('anexosFGenerales.NSolicitud','anexosFVehiculos.identificador','anexosFVehiculos.marca','anexosFVehiculos.modelo', 'anexosFVehiculos.ano','anexosFVehiculos.placas','anexosFVehiculos.vin','anexosFGenerales.FechaAlta', 'empresas.nombre as empresas',
                DB::raw('SUM(anexosfcarrito.cantidad * anexosfcarrito.precio_v) as SubTotal'),
                DB::raw('(SUM(anexosfcarrito.cantidad * anexosfcarrito.precio_v)) * 0.16 as Iva'),
                DB::raw('(SUM(anexosfcarrito.cantidad * anexosfcarrito.precio_v)) * 1.16 as Total'),
            'estatus.nombre')
            ->where('anexosforaneos.eco_id', '=', $eco)
            ->where($criterio, $not, '%' . $buscar . '%')
            ->groupBy(
                'anexosFGenerales.NSolicitud',
                'anexosFVehiculos.identificador',
                'anexosFVehiculos.marca',
                'anexosFVehiculos.modelo',
                'anexosFVehiculos.ano',
                'anexosFVehiculos.placas',
                'anexosFVehiculos.vin',
                'anexosFGenerales.FechaAlta',
                'empresas.nombre',
                'estatus.nombre'
            );
        if (!empty($fecha_inicio)) {
            $cotizacionesQuery->where('anexosforaneos.created_at', '>',$fecha_inicio);
        }
        if (!empty($fecha_final)) {
            $cotizacionesQuery->where('anexosforaneos.created_at', '<',$fecha_final);
        }

        return $cotizacionesQuery->orderBy('anexosforaneos.status', 'asc')->orderBy('anexosforaneos.id', 'desc')->get();
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
