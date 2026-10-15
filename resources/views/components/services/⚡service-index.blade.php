<?php

use App\Models\Customer;
use App\Models\Service;
use App\Models\Vehicle;
use Livewire\Component;
use Livewire\WithPagination;

new class extends Component
{
    use WithPagination;

    public string $search = '';
    public bool $showForm = false;
    public ?int $serviceId = null;

    public $customer_id = '';
    public $vehicle_id = '';

    public string $tanggal_masuk = '';
    public string $keluhan = '';
    public string $biaya_jasa = '0';
    public string $biaya_sparepart = '0';
    public string $status = 'menunggu';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatedCustomerId(): void
    {
        $this->vehicle_id = '';
        $this->resetValidation('vehicle_id');
    }

    protected function rules(): array
    {
        return [
            'customer_id' => ['required', 'exists:customers,id'],
            'vehicle_id' => [
                'required',
                'exists:vehicles,id',
                function ($attribute, $value, $fail) {
                    if (
                        $this->customer_id &&
                        !Vehicle::where('id', $value)
                            ->where('customer_id', $this->customer_id)
                            ->exists()
                    ) {
                        $fail(
                            'Kendaraan tidak sesuai dengan pelanggan yang dipilih.'
                        );
                    }
                },
            ],
            'tanggal_masuk' => [
                'required',
                'date',
                'before_or_equal:today',
            ],
            'keluhan' => ['required', 'string'],
            'biaya_jasa' => [
                'required',
                'numeric',
                'min:0',
                'max:9999999999.99',
            ],
            'biaya_sparepart' => [
                'required',
                'numeric',
                'min:0',
                'max:9999999999.99',
            ],
            'status' => [
                'required',
                'in:menunggu,dikerjakan,selesai,diambil',
            ],
        ];
    }

    public function create(): void
    {
        $this->resetForm();

        $this->tanggal_masuk = now()->toDateString();
        $this->showForm = true;
    }

    public function edit(int $id): void
    {
        $service = Service::with('vehicle')->findOrFail($id);

        $this->resetValidation();

        $this->serviceId = $service->id;
        $this->customer_id = (string) $service->vehicle->customer_id;
        $this->vehicle_id = (string) $service->vehicle_id;
        $this->tanggal_masuk = $service->tanggal_masuk->format('Y-m-d');
        $this->keluhan = $service->keluhan;
        $this->biaya_jasa = (string) $service->biaya_jasa;
        $this->biaya_sparepart = (string) $service->biaya_sparepart;
        $this->status = $service->status;

        $this->showForm = true;
    }

    public function save(): void
    {
        $validated = $this->validate();

        if ($this->serviceId) {
            $service = Service::findOrFail($this->serviceId);

            $urutanStatus = [
                'menunggu' => 0,
                'dikerjakan' => 1,
                'selesai' => 2,
                'diambil' => 3,
            ];

            if (
                $urutanStatus[$validated['status']]
                < $urutanStatus[$service->status]
            ) {
                $this->addError(
                    'status',
                    'Status servis tidak boleh kembali ke tahap sebelumnya.'
                );

                return;
            }

            $service->update([
                'vehicle_id' => $validated['vehicle_id'],
                'tanggal_masuk' => $validated['tanggal_masuk'],
                'keluhan' => $validated['keluhan'],
                'biaya_jasa' => $validated['biaya_jasa'],
                'biaya_sparepart' => $validated['biaya_sparepart'],
                'status' => $validated['status'],
            ]);

            $message = 'Data servis berhasil diperbarui.';
        } else {
            Service::create([
                'vehicle_id' => $validated['vehicle_id'],
                'tanggal_masuk' => $validated['tanggal_masuk'],
                'keluhan' => $validated['keluhan'],
                'biaya_jasa' => $validated['biaya_jasa'],
                'biaya_sparepart' => $validated['biaya_sparepart'],
                'status' => 'menunggu',
            ]);

            $message = 'Servis berhasil ditambahkan.';
        }

        session()->flash('message', $message);

        $this->resetForm();
    }

    public function delete(int $id): void
    {
        Service::findOrFail($id)->delete();

        session()->flash('message', 'Data servis berhasil dihapus.');
    }

    public function resetForm(): void
    {
        $this->reset([
            'serviceId',
            'customer_id',
            'vehicle_id',
            'tanggal_masuk',
            'keluhan',
            'biaya_jasa',
            'biaya_sparepart',
            'status',
        ]);

        $this->biaya_jasa = '0';
        $this->biaya_sparepart = '0';
        $this->status = 'menunggu';

        $this->resetValidation();
        $this->showForm = false;
    }

    public function with(): array
    {
        return [
            'services' => Service::with('vehicle.customer')
                ->when(trim($this->search) !== '', function ($query) {
                    $search = trim($this->search);

                    $query->where(function ($q) use ($search) {
                        $q->where('keluhan', 'like', '%' . $search . '%')
                            ->orWhere('status', 'like', '%' . $search . '%')
                            ->orWhereHas('vehicle', function ($vehicleQuery) use ($search) {
                                $vehicleQuery->where(
                                    'plat_nomor',
                                    'like',
                                    '%' . $search . '%'
                                );
                            })
                            ->orWhereHas('vehicle.customer', function ($customerQuery) use ($search) {
                                $customerQuery->where(
                                    'nama',
                                    'like',
                                    '%' . $search . '%'
                                );
                            });
                    });
                })
                ->latest('tanggal_masuk')
                ->paginate(10),

            'customers' => Customer::orderBy('nama')->get(),

            'vehicles' => Vehicle::with('customer')
                ->when($this->customer_id, function ($query) {
                    $query->where('customer_id', $this->customer_id);
                })
                ->orderBy('plat_nomor')
                ->get(),
        ];
    }

    public function layout(): string
    {
        return 'layouts.app';
    }
};
?>

<div class="mx-auto w-full max-w-7xl space-y-6">

    {{-- Header --}}
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <div class="mb-2 flex items-center gap-2 text-xs font-semibold text-violet-600">
                <span>Dashboard</span>
                <span class="text-gray-300">/</span>
                <span class="text-gray-400">Servis</span>
            </div>

            <h1 class="text-3xl font-extrabold tracking-tight text-gray-900">
                Data Servis
            </h1>

            <p class="mt-2 text-sm text-gray-500">
                Pantau riwayat, biaya, dan status pengerjaan kendaraan.
            </p>
        </div>

        <button wire:click="create" class="btn-primary inline-flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                aria-hidden="true">
                <path d="M12 5v14M5 12h14"/>
            </svg>
            Tambah Servis
        </button>
    </div>

    {{-- Notifikasi --}}
    @if (session('message'))
        <div class="alert-success flex items-center gap-3">
            <span class="flex h-8 w-8 items-center justify-center rounded-full bg-green-100 text-green-700">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                    aria-hidden="true">
                    <path d="m5 12 4 4L19 6"/>
                </svg>
            </span>
            <p class="text-sm font-semibold">{{ session('message') }}</p>
        </div>
    @endif

    @if (session('error'))
        <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm font-medium text-red-700">
            {{ session('error') }}
        </div>
    @endif

    {{-- Ringkasan --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

        <div class="card flex items-center gap-4 p-5">
            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-violet-100 text-violet-700">
                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25"
                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"
                    aria-hidden="true">
                    <path d="M8 6h13M8 12h13M8 18h13"/>
                    <path d="M3 6h.01M3 12h.01M3 18h.01"/>
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Total Servis</p>
                <p class="mt-1 text-2xl font-extrabold text-gray-900">
                    {{ \App\Models\Service::count() }}
                </p>
            </div>
        </div>

        <div class="card flex items-center gap-4 p-5">
            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-orange-100 text-orange-700">
                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25"
                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"
                    aria-hidden="true">
                    <circle cx="12" cy="12" r="9"/>
                    <path d="M12 7v5l3 2"/>
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Menunggu</p>
                <p class="mt-1 text-2xl font-extrabold text-gray-900">
                    {{ \App\Models\Service::where('status', 'menunggu')->count() }}
                </p>
            </div>
        </div>

        <div class="card flex items-center gap-4 p-5">
            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-100 text-blue-700">
                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25"
                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"
                    aria-hidden="true">
                    <path d="M14.7 6.3a5 5 0 0 0-6.4 6.4L3 18l3 3 5.3-5.3a5 5 0 0 0 6.4-6.4L14 13l-3-3 3.7-3.7Z"/>
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Dikerjakan</p>
                <p class="mt-1 text-2xl font-extrabold text-gray-900">
                    {{ \App\Models\Service::where('status', 'dikerjakan')->count() }}
                </p>
            </div>
        </div>

        <div class="card flex items-center gap-4 p-5">
            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-green-100 text-green-700">
                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25"
                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"
                    aria-hidden="true">
                    <circle cx="12" cy="12" r="9"/>
                    <path d="m8 12 2.5 2.5L16 9"/>
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Selesai</p>
                <p class="mt-1 text-2xl font-extrabold text-gray-900">
                    {{ \App\Models\Service::whereIn('status', ['selesai', 'diambil'])->count() }}
                </p>
            </div>
        </div>

    </div>

    {{-- Form tambah/edit --}}
    @if ($showForm)
        <div class="form-panel space-y-5">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-violet-600">
                        Form Servis
                    </p>
                    <h2 class="mt-1 text-xl font-extrabold text-gray-900">
                        {{ $serviceId ? 'Edit Data Servis' : 'Catat Servis Baru' }}
                    </h2>
                    <p class="mt-1 text-sm text-gray-500">
                        Masukkan informasi kendaraan dan detail pengerjaan.
                    </p>
                </div>

                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-purple-50 text-purple-700">
                    <svg xmlns="http://www.w3.org/2000/svg" width="23" height="23"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"
                        aria-hidden="true">
                        <path d="M14.7 6.3a5 5 0 0 0-6.4 6.4L3 18l3 3 5.3-5.3a5 5 0 0 0 6.4-6.4L14 13l-3-3 3.7-3.7Z"/>
                    </svg>
                </div>
            </div>

            <form wire:submit="save" class="space-y-5">
                <div>
                    <label for="customer_id" class="form-label">Pelanggan</label>
                    <select id="customer_id" wire:model.live="customer_id" class="form-control">
                        <option value="">-- Pilih Pelanggan --</option>
                        @foreach ($customers as $customer)
                            <option value="{{ $customer->id }}">
                                {{ $customer->nama }} — {{ $customer->no_hp }}
                            </option>
                        @endforeach
                    </select>
                    @error('customer_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="vehicle_id" class="form-label">Kendaraan</label>
                    <select
                        id="vehicle_id"
                        wire:model="vehicle_id"
                        wire:key="vehicle-select-{{ $customer_id ?: 'none' }}"
                        @disabled(!$customer_id)
                        class="form-control disabled:bg-gray-100"
                    >
                        <option value="">
                            @if (!$customer_id)
                                -- Pilih Pelanggan Terlebih Dahulu --
                            @elseif ($vehicles->isEmpty())
                                -- Pelanggan Belum Memiliki Kendaraan --
                            @else
                                -- Pilih Kendaraan --
                            @endif
                        </option>

                        @foreach ($vehicles as $vehicle)
                            <option value="{{ $vehicle->id }}">
                                {{ $vehicle->plat_nomor }} — {{ $vehicle->merek }} {{ $vehicle->tipe }}
                            </option>
                        @endforeach
                    </select>
                    @error('vehicle_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="tanggal_masuk" class="form-label">Tanggal Masuk</label>
                    <input
                        id="tanggal_masuk"
                        type="date"
                        wire:model="tanggal_masuk"
                        max="{{ now()->toDateString() }}"
                        class="form-control"
                    />
                    @error('tanggal_masuk')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="keluhan" class="form-label">Keluhan Kendaraan</label>
                    <textarea
                        id="keluhan"
                        wire:model="keluhan"
                        rows="3"
                        placeholder="Ceritakan keluhan kendaraan..."
                        class="form-control"
                    ></textarea>
                    @error('keluhan')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    <div>
                        <label for="biaya_jasa" class="form-label">Biaya Jasa (Rp)</label>
                        <input
                            id="biaya_jasa"
                            type="number"
                            wire:model="biaya_jasa"
                            min="0"
                            step="0.01"
                            class="form-control"
                        />
                        @error('biaya_jasa')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="biaya_sparepart" class="form-label">Biaya Sparepart (Rp)</label>
                        <input
                            id="biaya_sparepart"
                            type="number"
                            wire:model="biaya_sparepart"
                            min="0"
                            step="0.01"
                            class="form-control"
                        />
                        @error('biaya_sparepart')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                @if ($serviceId)
                    <div>
                        <label for="status" class="form-label">Status Servis</label>
                        <select id="status" wire:model="status" class="form-control">
                            <option value="menunggu">Menunggu</option>
                            <option value="dikerjakan">Dikerjakan</option>
                            <option value="selesai">Selesai</option>
                            <option value="diambil">Diambil</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-400">
                            Status hanya dapat bergerak maju ke tahap berikutnya.
                        </p>
                    </div>
                @else
                    <div class="rounded-xl border border-blue-100 bg-blue-50 p-4 text-sm text-blue-800">
                        Status awal servis otomatis ditetapkan menjadi <strong>Menunggu</strong>.
                    </div>
                @endif

                <div class="flex flex-wrap gap-3 border-t border-gray-100 pt-4">
                    <button
                        type="submit"
                        wire:loading.attr="disabled"
                        class="btn-primary inline-flex items-center gap-2"
                    >
                        <svg wire:loading.remove wire:target="save"
                            xmlns="http://www.w3.org/2000/svg" width="17" height="17"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            aria-hidden="true">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2Z"/>
                            <path d="M17 21v-8H7v8M7 3v5h8"/>
                        </svg>
                        <span wire:loading.remove wire:target="save">
                            {{ $serviceId ? 'Simpan Perubahan' : 'Simpan Servis' }}
                        </span>
                        <span wire:loading wire:target="save">Menyimpan...</span>
                    </button>

                    <button type="button" wire:click="resetForm" class="btn-secondary">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    @endif

    {{-- Daftar servis --}}
    <div class="card overflow-hidden">
        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-purple-50 p-5">
            <div>
                <h2 class="font-extrabold text-gray-900">Riwayat Servis</h2>
                <p class="mt-1 text-xs text-gray-500">
                    Pantau kendaraan masuk dan perkembangan pengerjaannya.
                </p>
            </div>

            <span class="rounded-full bg-purple-50 px-3 py-1.5 text-xs font-bold text-violet-700">
                {{ $services->total() }} data
            </span>
        </div>

        {{-- Search yang sudah diperbaiki --}}
        <div class="border-b border-gray-100 p-5">
            <div class="relative w-full max-w-md">
                <span
                    class="pointer-events-none absolute inset-y-0 left-0 z-10 flex items-center pl-3"
                    aria-hidden="true"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5 shrink-0 text-gray-400"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="1.8"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    >
                        <circle cx="11" cy="11" r="7"/>
                        <path d="m20 20-4-4"/>
                    </svg>
                </span>

                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Cari keluhan, status, plat nomor, atau pelanggan..."
                    aria-label="Cari data servis"
                    class="form-control w-full !pl-11"
                />
            </div>
        </div>

        <div class="table-wrapper">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Tanggal Masuk</th>
                        <th>Kendaraan / Pemilik</th>
                        <th>Keluhan</th>
                        <th>Total Biaya</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($services as $service)
                        <tr wire:key="service-{{ $service->id }}">
                            <td>
                                <span class="font-semibold text-gray-400">
                                    {{ $services->firstItem() + $loop->index }}
                                </span>
                            </td>

                            <td class="whitespace-nowrap">
                                <span class="font-semibold text-gray-700">
                                    {{ $service->tanggal_masuk->format('d M Y') }}
                                </span>
                            </td>

                            <td>
                                <div>
                                    <span class="inline-flex rounded-lg border border-gray-200 bg-gray-50 px-2.5 py-1.5 font-extrabold tracking-wide text-gray-800">
                                        {{ $service->vehicle->plat_nomor }}
                                    </span>
                                    <p class="mt-2 font-semibold text-gray-700">
                                        {{ $service->vehicle->merek }} {{ $service->vehicle->tipe }}
                                    </p>
                                    <p class="mt-1 text-xs text-gray-400">
                                        {{ $service->vehicle->customer->nama }}
                                    </p>
                                </div>
                            </td>

                            <td>
                                <span class="inline-block min-w-36 max-w-56 leading-relaxed text-gray-500">
                                    {{ $service->keluhan }}
                                </span>
                            </td>

                            <td class="whitespace-nowrap">
                                <p class="font-extrabold text-gray-800">
                                    Rp {{ number_format($service->total_biaya, 0, ',', '.') }}
                                </p>
                                <p class="mt-1 text-xs text-gray-400">
                                    Jasa + sparepart
                                </p>
                            </td>

                            <td>
                                @php
                                    $statusClass = [
                                        'menunggu' => 'badge-menunggu',
                                        'dikerjakan' => 'badge-dikerjakan',
                                        'selesai' => 'badge-selesai',
                                        'diambil' => 'badge-diambil',
                                    ][$service->status] ?? '';
                                @endphp

                                <span class="badge-status {{ $statusClass }}">
                                    @if ($service->status === 'menunggu')
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            aria-hidden="true">
                                            <circle cx="12" cy="12" r="9"/>
                                            <path d="M12 7v5l3 2"/>
                                        </svg>
                                    @elseif ($service->status === 'dikerjakan')
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            aria-hidden="true">
                                            <path d="M14.7 6.3a5 5 0 0 0-6.4 6.4L3 18l3 3 5.3-5.3a5 5 0 0 0 6.4-6.4L14 13l-3-3 3.7-3.7Z"/>
                                        </svg>
                                    @elseif ($service->status === 'selesai')
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            aria-hidden="true">
                                            <circle cx="12" cy="12" r="9"/>
                                            <path d="m8 12 2.5 2.5L16 9"/>
                                        </svg>
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            aria-hidden="true">
                                            <path d="M3 7h18l-2 13H5L3 7Z"/>
                                            <path d="M8 7V5a4 4 0 0 1 8 0v2"/>
                                        </svg>
                                    @endif

                                    <span>{{ ucfirst($service->status) }}</span>
                                </span>
                            </td>

                            <td>
                                <div class="flex items-center gap-2">
                                    <button
                                        wire:click="edit({{ $service->id }})"
                                        class="inline-flex items-center gap-1.5 rounded-lg bg-violet-50 px-3 py-2 text-xs font-bold text-violet-700 hover:bg-violet-100"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            aria-hidden="true">
                                            <path d="M12 20h9"/>
                                            <path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L8 18l-4 1 1-4Z"/>
                                        </svg>
                                        Edit
                                    </button>

                                    <button
                                        wire:click="delete({{ $service->id }})"
                                        wire:confirm="Yakin ingin menghapus data servis ini?"
                                        wire:loading.attr="disabled"
                                        class="inline-flex items-center gap-1.5 rounded-lg bg-red-50 px-3 py-2 text-xs font-bold text-red-600 hover:bg-red-100"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            aria-hidden="true">
                                            <path d="M3 6h18"/>
                                            <path d="M8 6V4h8v2m-9 0 1 14h8l1-14"/>
                                            <path d="M10 11v5m4-5v5"/>
                                        </svg>
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-16 text-center">
                                <div class="mx-auto mb-3 flex h-14 w-14 items-center justify-center rounded-2xl bg-purple-50 text-purple-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"
                                        aria-hidden="true">
                                        <path d="M8 6h13M8 12h13M8 18h13"/>
                                        <path d="M3 6h.01M3 12h.01M3 18h.01"/>
                                    </svg>
                                </div>
                                <p class="font-bold text-gray-700">Riwayat servis belum ditemukan</p>
                                <p class="mt-1 text-sm text-gray-400">
                                    Coba kata kunci lain atau tambahkan data servis baru.
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-gray-100 p-5">
            {{ $services->links() }}
        </div>
    </div>
</div>