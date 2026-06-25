<?php

namespace App\Exports;

use App\Models\Presupuesto;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\LOG;
use App\Models\PresupuestoCarrito;
use Carbon\Carbon;
use App\Models\DetallesGenerales;
use App\Models\Vehiculo;
use Maatwebsite\Excel\Events\AfterSheet;
class HistorialConceptosReportes implements FromCollection, WithStyles,WithEvents
{
    protected $request;

    public function __construct( $request)
    {
        $this->request = $request;
    }

    public function collection()
    {   
        $request=$this->request;
        $vehiculo=vehiculo::find($request->vehiculo);

        $idsDetalles = DetallesGenerales::where('Vehiculo_id', $vehiculo->id)
            ->when($request->filled('orden'), function($q) use ($request) {return $q->where('id', $request->orden);})
            ->pluck('id');
        $idsPresupuestos = Presupuesto::whereIn('DetallesGenerales_id', $idsDetalles)
            ->pluck('id');
        $conceptos = PresupuestoCarrito::with(['DatosConcepto','presupuesto.detallesGenerales'])
            ->whereIn('Presupuesto_id', $idsPresupuestos);

        if ($request->filled('fechamin') && $request->filled('fechamax')) {
            $conceptos = $conceptos->whereBetween('created_at', [
                Carbon::parse($request->fechamin)->startOfDay(),
                Carbon::parse($request->fechamax)->endOfDay()
            ]);
        } elseif ($request->filled('fechamin')) {
            $conceptos = $conceptos->where('created_at', '>=', Carbon::parse($request->fechamin)->startOfDay());
        } elseif ($request->filled('fechamax')) {
            $conceptos = $conceptos->where('created_at', '<=', Carbon::parse($request->fechamax)->endOfDay());
        }

        if($request->filled('search')){
            $search='%'.$request->search.'%';
            $conceptos=$conceptos->whereHas('DatosConcepto',function($query) use ($search){
                $query->where('descripcion','LIKE',$search);
            });
        } 
        
        $elemets=$conceptos->orderbydesc('created_at')->get()->map(function($E){
            return [
                'orden'=>$E->presupuesto->detallesGenerales->OrdenServicio,
                'codigo'=>$E->DatosConcepto->num,
                'cantidad'=>$E->Cantidad,
                'fecha'=>Carbon::parse($E->created_at)->format('Y-m-d H:m'),
                'concepto'=>$E->DatosConcepto->descripcion,
                'costo'=>$E->Costo,
                'precio'=>$E->Venta,
                'total'=>$E->Venta * $E->Cantidad
            ];
        });
        $datosExportacion = collect();
        $datosExportacion->push(['DATOS DEL VEHICULO']);
        $datosExportacion->push(['']);
        $datosExportacion->push([
            'ECONOMICO:',$vehiculo->no_economico,'',
            'PLACAS',$vehiculo->placas,'',
            'VIN',$vehiculo->vim
        ]);
        $datosExportacion->push(['']);
        $datosExportacion->push(['HISTORIAL DE CONCEPTOS DEL VEHICULO']);
        $datosExportacion->push(['']);
        $datosExportacion->push([
            'ORDEN DE SERVICIO','CODIGO','CANTIDAD','FECHA','CONCEPTO','COSTO','PRECIO','TOTAL'
        ]);
        foreach ($elemets as $registro) {
            $datosExportacion->push($registro);
        }

        return $datosExportacion;
    }


    public function styles(Worksheet $sheet)
    {
        $styles = [
            7 => ['font' => ['bold' => true]],
        ];

        $sheet->mergeCells('A1:H1');
        $sheet->mergeCells('A5:H5');

        $sheet->getStyle('A1:H1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 14],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ]);

        $sheet->getStyle('A5:H5')->applyFromArray([
            'font' => ['bold' => true, 'size' => 14],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Estilos directos en celdas específicas
        $sheet->getStyle('A3')->getFont()->setBold(true);
        $sheet->getStyle('D3')->getFont()->setBold(true);
        $sheet->getStyle('G3')->getFont()->setBold(true);
    
        return $styles;

    }
     public function registerEvents(): array
{
    return [
        AfterSheet::class => function (AfterSheet $event) {

            $lastColumn = $event->sheet->getHighestColumn();
            $lastColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($lastColumn);

            for ($col = 1; $col <= $lastColumnIndex; $col++) {
                $columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);
                $event->sheet->getDelegate()->getColumnDimension($columnLetter)->setAutoSize(true);
            }
        }
    ];
}
}
