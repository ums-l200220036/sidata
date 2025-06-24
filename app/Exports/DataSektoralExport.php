<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;

class DataSektoralExport implements FromView, WithTitle, WithStyles, ShouldAutoSize
{
    protected $viewName;
    protected $data;

    /**
     * @param string $viewName Nama file blade view untuk ekspor (cth: 'exports.prioritas').
     * @param array  $data     Data yang akan dikirim ke view tersebut.
     */
    public function __construct(string $viewName, array $data)
    {
        $this->viewName = $viewName;
        $this->data     = $data;
    }

    /**
     * Mengembalikan view yang akan dirender menjadi sheet Excel.
     */
    public function view(): View
    {
        return view($this->viewName, $this->data);
    }

    /**
     * Memberikan judul untuk worksheet.
     */
    public function title(): string
    {
        return $this->data['indikatorTitle'] ?? 'Laporan';
    }

    /**
     * Menerapkan style pada worksheet.
     */
    public function styles(Worksheet $sheet)
    {
        // Style untuk header utama
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFFE482B']],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, 'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER],
        ];
        
        // Style untuk sub-header
        $subHeaderStyle = [
            'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFE03D25']],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, 'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER],
        ];

        $sheet->getStyle('1:1')->applyFromArray($headerStyle);
        $sheet->getStyle('2:2')->applyFromArray($subHeaderStyle);

        // Menambahkan border ke seluruh tabel
        $lastRow = $sheet->getHighestRow();
        $lastColumn = $sheet->getHighestColumn();
        $sheet->getStyle('A1:' . $lastColumn . $lastRow)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
    }
}