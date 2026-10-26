<?php

namespace App\Exports;

use App\Models\Bill;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class OverdueExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $query = Bill::with(['student'])
            ->where('status', 'unpaid')
            ->when($this->request->filled('month'), function ($q) {
                $month = $this->request->month;
                $q->where('billing_period', 'like', "%-{$month}");
            })
            ->when($this->request->filled('year'), function ($q) {
                $year = $this->request->year;
                $q->where('billing_period', 'like', "{$year}-%");
            });

        return $query
            ->latest()
            ->get()
            ->map(function ($bill) {
                $billStatus = $bill->status == 'unpaid' ? 'Belum Dibayar' : 'Telah Dibayar';

                return [
                    $bill->student->fullname ?? $bill->student->name,
                    $bill->billing_period,
                    rupiah($bill->amount),
                    titleCase($billStatus),
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Nama Siswa',
            'Periode',
            'Nominal',
            'Status',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle(
            'A1:D' . $sheet->getHighestRow()
        )
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle(
                Border::BORDER_THIN
            );

        return [

            1 => [
                'font' => [
                    'bold' => true
                ],

                'alignment' => [
                    'horizontal' => 'center'
                ],

                'fill' => [

                    'fillType' => 'solid',

                    'startColor' => [
                        'rgb' => 'B2BD7E'
                    ]

                ]
            ]
        ];
    }
}
