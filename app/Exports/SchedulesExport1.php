<?php

namespace App\Exports;


use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SchedulesExport1 implements FromCollection, WithMapping, WithHeadings, ShouldAutoSize, WithStyles
{
    protected $schedules;

    public function __construct($schedules)
    {
        $this->schedules = $schedules;
    }

    public function collection()
    {
        return $this->schedules;
    }
    public function map($schedule): array
    {
        return [

            'Hari' => isset($schedule->day->name_day) ? $schedule->day->name_day : '',
            'Jam' => isset($schedule->time->range) ? $schedule->time->range : '',
            'Kelas' => isset($schedule->teach->class_room) ? $schedule->teach->class_room : '',
            'Kode Guru' => isset($schedule->teach->lecturer->code_lecturers) ? $schedule->teach->lecturer->code_lecturers : '',
            'Mata Pelajaran' => isset($schedule->teach->course->name) ? $schedule->teach->course->name : '',
            'JP' => isset($schedule->teach->course->jp) ? $schedule->teach->course->jp : '',
            'Ruangan' => isset($schedule->room->name) ? $schedule->room->name : '',
        ];
    }

    public function headings(): array
    {
        return [
            'Hari',
            'Jam',
            'Kelas',
            'Kode Guru',
            'Mata Pelajaran',
            'JP',
            'Ruangan',
        ];
    }
    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:G1')->applyFromArray([
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        $sheet->getStyle('A1:G1')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();

        $sheet->getStyle('A2:' . $highestColumn . $highestRow)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        return [
            'A1:G1' => [
                'font' => [
                    'bold' => true,
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
            ],
            'A2:' . $highestColumn . $highestRow => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['argb' => '000000'],
                    ],
                ],
            ],
        ];
    }
}
