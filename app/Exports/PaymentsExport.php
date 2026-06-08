<?php

namespace App\Exports;

use App\Models\Payment;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PaymentsExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
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
        $query =  Payment::with([
            'bill.student',
            'paymentMethod'
        ]);

        if ($this->request->month) {

            $query->whereMonth(
                'paid_at',
                Carbon::parse(
                    $this->request->month
                )->month
            );
        }

        if ($this->request->payment_method) {

            $query->where(
                'payment_method_id',
                $this->request->payment_method
            );
        }

        return $query
            ->get()
            ->map(function ($payment) {

                return [

                    $payment->payment_code,

                    $payment->bill->student->name,

                    $payment->bill->billing_period,

                    $payment->paymentMethod->name,

                    Carbon::parse(
                        $payment->paid_at
                    )->format('d M Y'),

                    'Rp ' . number_format(
                        $payment->amount_paid
                    )

                ];
            });
    }

    public function headings(): array
    {
        return [
            'Payment Code',
            'Student Name',
            'Billing Period',
            'Payment Method',
            'Paid At',
            'Amount'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle(
            'A1:F' . $sheet->getHighestRow()
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
