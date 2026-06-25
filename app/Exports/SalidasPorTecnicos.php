<?php
namespace App\Exports;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Events\AfterSheet;

class SalidasPorTecnicos implements FromCollection, WithStyles, WithEvents, WithTitle

{
    protected $request;
    protected $rowsWithTechnicianHeaders = [];
public function title(): string
    {
        return 'Actividades'; // nombre de la hoja 1
    }
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $lista = collect($this->request->listasalidas);
        $agrupadoPorTecnico = $lista->groupBy('tecnico');

        $datosExportacion = collect();

        foreach ($agrupadoPorTecnico as $tecnico => $registros) {
            // Marcar fila para estilo
            $this->rowsWithTechnicianHeaders[] = $datosExportacion->count() + 1;

            // Nombre del técnico
            $datosExportacion->push(["TÉCNICO: $tecnico"]);

            // Encabezados
            $datosExportacion->push([
                'Orden Servicio', 'Economico', 'Descripcion', 'Entrada', 'Diagnostico',
                'Pedido de Refacciones', 'Entrega De Refacciones', 'Salida', 'Total De Horas'
            ]);

            // Agregar registros
            foreach ($registros as $registro) {
                if (is_array($registro)) {
                    $registro = (object) $registro;
                }

                $datosExportacion->push([
                    $registro->OrdenServicio,
                    $registro->economico,
                    $registro->descripcion,
                    $registro->entrada,
                    $registro->diagnostico,
                    $registro->pedidohecho,
                    $registro->pedidoentregado,
                    $registro->salida,
                    $registro->horas
                ]);
            }

            // Separador (opcional)
            $datosExportacion->push(['']); // Línea vacía entre técnicos
        }

        return $datosExportacion;
    }

    public function styles(Worksheet $sheet)
    {
        $styles = [];

        foreach ($this->rowsWithTechnicianHeaders as $row) {
            $styles[$row] = [
                'font' => ['bold' => true, 'size' => 14],
            ];
        }

        return $styles;
    }

    public function registerEvents(): array
{
    return [
        AfterSheet::class => function (AfterSheet $event) {
            // Aplicar estilos a encabezados de cada técnico
            foreach ($this->rowsWithTechnicianHeaders as $row) {
                $event->sheet->getStyle("A" . ($row + 1) . ":I" . ($row + 1))->applyFromArray([
                    'font' => ['bold' => true],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'D9D9D9'],
                    ],
                ]);
            }

            // 🔁 Ajustar tamaño de columnas automáticamente
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