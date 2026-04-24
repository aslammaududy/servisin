<?php

use App\Models\Booking;
use App\Models\BookingItem;
use App\Models\Complaint;
use Livewire\Component;

new class extends Component {
    use \App\Livewire\Concerns\HasToast;
    use \Livewire\WithFileUploads;

    public Booking $booking;
    public string $search_technician = '';
    public string $booking_status = '';
    public ?int $technician_id;
    public mixed $shipping_fee;
    public array $statuses = [
        'Menunggu',
        'Teknisi Ditugaskan',
        'Sedang Dikerjakan',
        'Selesai',
        'Batal'
    ];

    // Complaint properties
    public string $complaint_message = '';
    public $complaint_photo;

    // Payment proof properties
    public $payment_proof;

    public function mount(Booking $booking): void
    {
        $this->booking = $booking->load([
            'bookingItems.damageType.service', 
            'user', 
            'technician', 
            'bookingEvents.changedBy', 
            'complaints.user', 
            'complaints.complainPhotos',
            'paymentProofs'
        ]);
        $this->booking_status = $booking->status;
        $this->technician_id = $booking->technician_id;
        $this->shipping_fee = $booking->shipping_fee;
    }

    #[\Livewire\Attributes\Computed]
    public function estimatedTotal(): int
    {
        $estimated_total = $this->booking->bookingItems->reduce(function (?int $carry, \App\Models\BookingItem $item) {
            return $carry + $item->damageType->price;
        }, 0);

        return $estimated_total + $this->booking->shipping_fee;
    }

    #[\Livewire\Attributes\Computed]
    public function services(): string
    {
        $services = [];
        $this->booking->bookingItems->each(function (BookingItem $bookingItem) use (&$services) {
            $services[] = $bookingItem->damageType->service->name;
        });
        return implode(', ', array_unique($services));
    }

    #[\Livewire\Attributes\Computed]
    public function damages(): string
    {
        $damages = [];
        $this->booking->bookingItems->each(function (BookingItem $bookingItem) use (&$damages) {
            $damages[] = $bookingItem->damageType->name;
        });

        return implode(', ', array_unique($damages));;
    }

    #[\Livewire\Attributes\Computed]
    public function technicians(): Illuminate\Database\Eloquent\Collection
    {
        return \App\Models\User::where('role', 'technician')->whereLike('name', "%$this->search_technician%")->get();
    }

    #[\Livewire\Attributes\Computed]
    public function complaints()
    {
        return $this->booking->complaints()->with('user')->latest()->get();
    }

    public function updatedBookingStatus(string $status): void
    {
        $this->booking->status = $status;
        $this->booking->save();

        $this->toastSuccess('Berhasil update status');
    }

    public function updatedTechnicianId(?int $id): void
    {
        $this->booking->technician_id = $id;
        $this->booking->save();

        $this->toastSuccess('Berhasil menugaskan teknisi');
    }

    public function setShippingFee(): void
    {
        $this->booking->shipping_fee = (int)str_replace(",", "", $this->shipping_fee);
        $this->booking->save();

        $this->toastSuccess('Berhasil update ongkos kirim');
    }

    public function submitComplaint(): void
    {
        $this->validate([
            'complaint_message' => 'required|min:10',
            'complaint_photo' => 'nullable|image|max:1024',
        ], [
            'complaint_message.required' => 'Pesan komplain wajib diisi',
            'complaint_message.min' => 'Pesan komplain minimal 10 karakter',
            'complaint_photo.image' => 'File harus berformat gambar',
            'complaint_photo.max' => 'File maksimal 1MB',
        ]);

        $complaint = Complaint::create([
            'booking_id' => $this->booking->id,
            'user_id' => auth()->id(),
            'message' => $this->complaint_message,
            'status' => 'pending',
        ]);

        if ($this->complaint_photo) {
            $file_name = $this->complaint_photo->getClientOriginalName();
            $path = $this->complaint_photo->storeAs(path: 'complaints', name: $file_name);

            \App\Models\ComplainPhoto::create([
                'complaint_id' => $complaint->id,
                'path' => $path,
                'original_name' => $file_name,
            ]);
        }

        $this->complaint_message = '';
        $this->complaint_photo = null;

        $this->toastSuccess('Komplain berhasil dikirim');
    }

    public function uploadPaymentProof(): void
    {
        $this->validate([
            'payment_proof' => 'required|image|max:2048',
        ], [
            'payment_proof.required' => 'Bukti pembayaran wajib diunggah',
            'payment_proof.image' => 'File harus berformat gambar',
            'payment_proof.max' => 'File maksimal 2MB',
        ]);

        $file_name = $this->payment_proof->getClientOriginalName();
        $path = $this->payment_proof->storeAs(path: 'payment_proofs', name: $file_name);

        \App\Models\BookingPaymentProof::create([
            'booking_id' => $this->booking->id,
            'path' => $path,
            'original_name' => $file_name,
            'status' => 'pending',
        ]);

        $this->payment_proof = null;

        $this->toastSuccess('Bukti pembayaran berhasil diunggah');
    }
};
