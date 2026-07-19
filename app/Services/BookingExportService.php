<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\User;
use League\Csv\Writer;
use Symfony\Component\HttpFoundation\StreamedResponse;

class BookingExportService
{
    public function toCsv(?string $dateFrom, ?string $dateTo, ?string $status, ?User $user = null): StreamedResponse
    {
        $bookings = $this->getFilteredBookings($dateFrom, $dateTo, $status, $user);

        $callback = function () use ($bookings) {
            $csv = Writer::createFromFileObject(new \SplTempFileObject);

            $csv->insertOne([
                'ID',
                'Tanggal',
                'Pelanggan',
                'Teknisi',
                'Layanan',
                'Status',
                'Total Estimasi',
                'Ongkir',
            ]);

            foreach ($bookings as $booking) {
                $csv->insertOne([
                    $booking->id,
                    $booking->booking_date?->format('d M Y') ?? '-',
                    $booking->user?->name ?? '-',
                    $booking->technician?->name ?? 'Belum ada',
                    $booking->bookingItems->pluck('damageType.service.name')->implode(', ') ?: '-',
                    $booking->status,
                    number_format($booking->bookingItems->sum('price') + $booking->shipping_fee, 0, ',', '.'),
                    number_format($booking->shipping_fee, 0, ',', '.'),
                ]);
            }

            $csv->output();
        };

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="laporan-booking-'.$this->getDateSuffix().'.csv"',
        ];

        return response()->stream($callback, 200, $headers);
    }

    private function getFilteredBookings(?string $dateFrom, ?string $dateTo, ?string $status, ?User $user = null)
    {
        $query = Booking::forExport($dateFrom, $dateTo, $status);

        if ($user && $user->role !== 'admin') {
            $query->where('user_id', $user->id);
        }

        return $query->latest()->get();
    }

    private function getDateSuffix(): string
    {
        return now()->format('Y-m-d');
    }
}
