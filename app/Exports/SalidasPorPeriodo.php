<?php
namespace App\Exports;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Events\AfterSheet;
use Carbon\Carbon;

class SalidasPorPeriodo implements FromCollection, WithStyles, WithEvents
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    public function title(): string
    {
        return 'Salidas Por Periodo'; // nombre de la hoja 1
    }
    public function collection()
    {
        $lista = collect($this->request->listasalidas);

        $days = $lista->pluck('salida')->map(function ($date) {
            return Carbon::parse($date)->toDateString();
        })->unique()->sort()->values();

        $diasNombres = $days->map(function ($date) {
            return ucfirst(Carbon::parse($date)->locale('es')->dayName); // 'Lunes', etc.
        });

        $fechaInicioCarbon = Carbon::parse($days->first());
        $fechaFinCarbon = Carbon::parse($days->last());

        $diaInicio = $fechaInicioCarbon->format('d');
        $mesInicio = ucfirst($fechaInicioCarbon->translatedFormat('F'));
        $anioInicio = $fechaInicioCarbon->format('Y');

        $diaFin = $fechaFinCarbon->format('d');
        $mesFin = ucfirst($fechaFinCarbon->translatedFormat('F'));
        $anioFin = $fechaFinCarbon->format('Y');

        if ($anioInicio === $anioFin) {
            // Si años iguales
            if ($mesInicio === $mesFin) {
                // Mismo mes y año
                $fechaInicio = "$diaInicio";
                $fechaFin = "$diaFin de $mesFin de $anioFin";
            } else {
                // Mismo año pero distinto mes
                $fechaInicio = "$diaInicio de $mesInicio";
                $fechaFin = "$diaFin de $mesFin de $anioFin";
            }
        } else {
            // Años distintos, mostrar todo completo
            $fechaInicio = "$diaInicio de $mesInicio de $anioInicio";
            $fechaFin = "$diaFin de $mesFin de $anioFin";
        }



        $agrupadoPorTecnico = $lista->groupBy('tecnico');

        $datosExportacion = collect();

        // Fila del período
        $datosExportacion->push(["PERIODO DEL $fechaInicio AL $fechaFin"]);

        // Fila de encabezados
        $header = array_merge(['Técnico'], $diasNombres->toArray(), ['Total', 'Meta', 'Rendimiento']);
        $datosExportacion->push($header);

        foreach ($agrupadoPorTecnico as $tecnico => $registros) {
            $registrosPorFecha = $registros->groupBy(function ($item) {
                return Carbon::parse($item['salida'])->toDateString();
            });

            $conteoPorDia = [];
            foreach ($days as $day) {
                $conteoPorDia[] = isset($registrosPorFecha[$day]) ? $registrosPorFecha[$day]->count() : 0;
            }

            $total = array_sum($conteoPorDia);
            $meta = count($days) * 3;
            $rendimiento = $meta > 0 ? round(($total / $meta) * 100, 2) . '%' : 'N/A';

            $filaResumen = array_merge([$tecnico], $conteoPorDia, [$total, $meta, $rendimiento]);
            $datosExportacion->push($filaResumen);

            $maxFilas = $registrosPorFecha->map->count()->max();
            for ($i = 0; $i < $maxFilas; $i++) {
                $filaDetalle = [''];
                foreach ($days as $day) {
                    $filaDetalle[] = isset($registrosPorFecha[$day][$i])
                        ? $registrosPorFecha[$day][$i]['OrdenServicio'] ?? ''
                        : '';
                }
                $datosExportacion->push($filaDetalle);
            }

            $datosExportacion->push(['']);
        }

        return $datosExportacion;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 14]], // "PERIODO DEL ... AL ..."
            2 => ['font' => ['bold' => true, 'size' => 12]], // Encabezado de columnas
        ];
    }


    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Ajustar tamaño automático de columnas
                $lastColumn = $sheet->getHighestColumn();
                $sheet->mergeCells("A1:$lastColumn" . "1");
            
                // 🔘 Centrar el texto horizontal y verticalmente
                $sheet->getStyle("A1")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("A1")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

                $lastColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($lastColumn);
                for ($col = 1; $col <= $lastColumnIndex; $col++) {
                    $columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);
                    $sheet->getColumnDimension($columnLetter)->setAutoSize(true);
                }
                // Obtener la fila de encabezado
                $header = $event->sheet->getDelegate()->rangeToArray('A2:' . $lastColumn . '2')[0];

                // Buscar índices de las columnas Total, Meta y Rendimiento
                $amarillas = ['Total', 'Meta', 'Rendimiento'];
                $columnasAmarillas = [];

                foreach ($header as $index => $colName) {
                    if (in_array($colName, $amarillas)) {
                        $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($index + 1);
                        $columnasAmarillas[] = $colLetter;
                    }
                }

                // Aplicar fondo amarillo a esas columnas
                $highestRow = $sheet->getHighestRow();
                foreach ($columnasAmarillas as $colLetter) {
                    $range = $colLetter . '2:' . $colLetter . $highestRow;
                    $sheet->getStyle($range)->applyFromArray([
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => ['rgb' => 'FFFF00'], // Amarillo
                        ],
                    ]);
                }
            }
        ];
    }

}
