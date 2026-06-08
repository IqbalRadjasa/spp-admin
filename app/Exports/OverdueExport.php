<?php

namespace App\Exports;

use App\Models\Bill;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class OverdueExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
{
    /**
     * @return \Illuminate\Support\Collection
     */

    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $query = Bill::with([
            'student'
        ])->where('status', 'unpaid');


        if ($this->request->month) {
            $query->where('billing_period', $this->request->month);
        }


        return $query
            ->get()
            ->map(function ($bill) {

                return [

                    $bill->student->name,

                    $bill->billing_period,

                    rupiah($bill->amount),

                    $bill->status

                ];
            });
    }


    public function headings(): array
    {
        return [
            'Student Name',
            'Billing Period',
            'Amount',
            'Status'
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
                        'rgb' => 'D9D9D9'
                    ]

                ]
            ]
        ];
    }
}
