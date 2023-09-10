<?php

namespace App\Exports;

use App\Models\Schedule;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;

class SchedulesExport implements FromCollection, WithMapping
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
            'No.' => $schedule->id,
            'Hari' => isset($schedule->day->name_day) ? $schedule->day->name_day : '',
            'Jam' => isset($schedule->time->range) ? $schedule->time->range : '',
            'Nama Ruangan' => isset($schedule->room->name) ? $schedule->room->name : '',
            'Kapasitas Ruangan' => isset($schedule->room->capacity) ? $schedule->room->capacity : '',
            'Mata Kuliah' => isset($schedule->teach->course->name) ? $schedule->teach->course->name : '',
            'Dosen Pengampu' => isset($schedule->teach->lecturer->name) ? $schedule->teach->lecturer->name : '',
            'Semester' => isset($schedule->teach->course->semester) ? $schedule->teach->course->semester : '',
            'JP' => isset($schedule->teach->course->jp) ? $schedule->teach->course->jp : '',
            'Kelas' => isset($schedule->teach->class_room) ? $schedule->teach->class_room : '',
            'Nilai' => isset($schedule->value) ? $schedule->value : '',
        ];
    }

    public function headings(): array
    {
        return [
            'No.',
            'Hari',
            'Jam',
            'Nama Ruangan',
            'Kapasitas Ruangan',
            'Mata Kuliah',
            'Dosen Pengampu',
            'Semester',
            'JP',
            'Kelas',
            'Nilai',
        ];
    }
}
