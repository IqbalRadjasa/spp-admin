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

        $query = Payment::with([
            'bill.student' => function ($q) {
                $q->select(['id', 'fullname', 'nis', 'avatar']);
            },
            'paymentMethod'
        ])
            ->when($this->request->filled('payment_method_id'), function ($q) {
                $q->where('payment_method_id', $this->request->payment_method_id);
            })
            ->when($this->request->filled('year'), function ($q) {
                $q->whereYear('paid_at', $this->request->year);
            })
            ->when($this->request->filled('month'), function ($q) {
                $q->whereMonth('paid_at', $this->request->month);
            })
            ->where(function ($q) {
                $q->where('status', 'paid');
            });

        return $query
            ->get()
            ->map(function ($payment) {
                return [
                    $payment->payment_code,
                    $payment->bill->student->fullname,
                    $payment->bill->billing_period,
                    $payment->paymentMethod->name,
                    Carbon::parse(
                        $payment->paid_at
                    )->format('d M Y'),
                    rupiah(
                        $payment->amount_paid
                    )
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Kode Pembayaran',
            'Nama Siswa',
            'Periode',
            'Metode Pembayaran',
            'Tanggal Pembayaran',
            'Nominal'
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
                        'rgb' => 'B2BD7E'
                    ]

                ]
            ]
        ];
    }
}
