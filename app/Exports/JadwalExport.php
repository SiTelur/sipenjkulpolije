<?php

namespace App\Exports;

use App\Models\Jadwal;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class JadwalExport implements FromView, ShouldAutoSize, WithStyles
{
    protected $jadwal;

    public function __construct(Jadwal $jadwal)
    {
        $this->jadwal = $jadwal;
    }

    public function view(): View
    {
        return view('jadwal.excel', [
            'jadwal' => $this->jadwal
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        // Force wrap text on all cells
        $sheet->getStyle($sheet->calculateWorksheetDimension())->getAlignment()->setWrapText(true);

        // Fix Excel's merged cell auto-height bug by setting explicit row heights
        // Our schedule has 5 days * 10 time slots = 50 rows.
        // Starting from row 3 (after the 2 header rows).
        for ($i = 3; $i <= 60; $i++) {
            $sheet->getRowDimension($i)->setRowHeight(30); // 30 points ~ 40 pixels
        }

        return [
            // Bold header
            1 => ['font' => ['bold' => true, 'size' => 14]],
            2 => ['font' => ['bold' => true]],
        ];
    }
}
