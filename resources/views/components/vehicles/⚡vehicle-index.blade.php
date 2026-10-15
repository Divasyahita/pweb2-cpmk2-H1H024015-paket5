<?php

use App\Models\Customer;
use App\Models\Vehicle;
use Livewire\Component;
use Livewire\WithPagination;

new class extends Component
{
    use WithPagination;

    public string $search = '';
    public bool $showForm = false;
    public ?int $vehicleId = null;

    public $customer_id = '';
    public string $plat_nomor = '';
    public string $merek = '';
    public string $tipe = '';
    public string $tahun = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    protected function rules(): array
    {
        return [
            'customer_id' => ['required', 'exists:customers,id'],
            'plat_nomor' => [
                'required',
                'string',
                'max:15',
                'unique:vehicles,plat_nomor,' . ($this->vehicleId ?? 'NULL') . ',id',
            ],
            'merek' => ['required', 'string', 'max:100'],
            'tipe' => ['required', 'string', 'max:100'],
            'tahun' => ['required', 'integer', 'min:1900', 'max:' . date('Y')],
        ];
    }

    public function create(): void
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function edit(int $id): void
    {
        $vehicle = Vehicle::findOrFail($id);

        $this->vehicleId = $vehicle->id;
        $this->customer_id = (string) $vehicle->customer_id;
        $this->plat_nomor = $vehicle->plat_nomor;
        $this->merek = $vehicle->merek;
        $this->tipe = $vehicle->tipe;
        $this->tahun = (string) $vehicle->tahun;

        $this->showForm = true;
    }

    public function updatedPlatNomor(): void
    {
        $this->plat_nomor = strtoupper(
            preg_replace('/\s+/', '', $this->plat_nomor) ?? ''
        );
    }

    public function save(): void
    {
        $this->plat_nomor = strtoupper(
            preg_replace('/\s+/', '', trim($this->plat_nomor)) ?? ''
        );

        $validated = $this->validate();

        Vehicle::updateOrCreate(
            ['id' => $this->vehicleId],
            $validated
        );

        session()->flash(
            'message',
            $this->vehicleId
                ? 'Data kendaraan berhasil diperbarui.'
                : 'Kendaraan berhasil ditambahkan.'
        );

        $this->resetForm();
    }

    public function delete(int $id): void
    {
        $vehicle = Vehicle::findOrFail($id);

        if ($vehicle->services()->exists()) {
            session()->flash(
                'error',
                'Kendaraan tidak bisa dihapus karena masih memiliki riwayat servis.'
            );

            return;
        }

        $vehicle->delete();

        session()->flash('message', 'Kendaraan berhasil dihapus.');
    }

    public function resetForm(): void
    {
        $this->reset([
            'vehicleId',
            'customer_id',
            'plat_nomor',
            'merek',
            'tipe',
            'tahun',
        ]);

        $this->resetValidation();
        $this->showForm = false;
    }

    public function with(): array
    {
        return [
            'vehicles' => Vehicle::with('customer')
                ->when($this->search, function ($query) {
                    $query->where(function ($q) {
                        $q->where('plat_nomor', 'like', '%' . $this->search . '%')
                            ->orWhere('merek', 'like', '%' . $this->search . '%')
                            ->orWhereHas('customer', function ($customerQuery) {
                                $customerQuery->where(
                                    'nama',
                                    'like',
                                    '%' . $this->search . '%'
                                );
                            });
                    });
                })
                ->latest()
                ->paginate(10),

            'customers' => Customer::orderBy('nama')->get(),
        ];
    }

    public function layout(): string
    {
        return 'layouts.app';
    }
};
?>

<div class="mx-auto max-w-7xl space-y-6">

    {{-- Header --}}
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <div class="mb-2 flex items-center gap-2 text-xs font-semibold text-violet-600">
                <span>Dashboard</span>
                <span class="text-gray-300">/</span>
                <span class="text-gray-400">Kendaraan</span>
            </div>

            <h1 class="text-3xl font-extrabold tracking-tight text-gray-900">
                Data Kendaraan
            </h1>

            <p class="mt-2 text-sm text-gray-500">
                Kelola kendaraan dan informasi pemiliknya.
            </p>
        </div>

        <button wire:click="create" class="btn-primary inline-flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                aria-hidden="true">
                <path d="M12 5v14M5 12h14"/>
            </svg>
            Tambah Kendaraan
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
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
        <div class="card flex items-center gap-4 p-5">
            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-violet-100 text-violet-700">
                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25"
                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"
                    aria-hidden="true">
                    <path d="M5 17h14l1-5-2-5H6l-2 5 1 5Z"/>
                    <path d="M4 12h16M7 17v2m10-2v2"/>
                    <circle cx="7.5" cy="14.5" r="1"/>
                    <circle cx="16.5" cy="14.5" r="1"/>
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Total Kendaraan</p>
                <p class="mt-1 text-2xl font-extrabold text-gray-900">
                    {{ \App\Models\Vehicle::count() }}
                </p>
            </div>
        </div>

        <div class="card flex items-center gap-4 p-5">
            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-purple-100 text-purple-700">
                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25"
                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"
                    aria-hidden="true">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                    <circle cx="10" cy="7" r="4"/>
                    <path d="M20 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Total Pelanggan</p>
                <p class="mt-1 text-2xl font-extrabold text-gray-900">
                    {{ \App\Models\Customer::count() }}
                </p>
            </div>
        </div>

        <div class="card flex items-center gap-4 p-5">
            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-100 text-indigo-700">
                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25"
                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"
                    aria-hidden="true">
                    <circle cx="11" cy="11" r="7"/>
                    <path d="m20 20-4-4"/>
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Hasil Pencarian</p>
                <p class="mt-1 text-2xl font-extrabold text-gray-900">
                    {{ $vehicles->total() }}
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
                        Form Kendaraan
                    </p>
                    <h2 class="mt-1 text-xl font-extrabold text-gray-900">
                        {{ $vehicleId ? 'Edit Kendaraan' : 'Tambah Kendaraan Baru' }}
                    </h2>
                    <p class="mt-1 text-sm text-gray-500">
                        Isi informasi kendaraan dan pilih pemiliknya.
                    </p>
                </div>

                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-purple-50 text-purple-700">
                    <svg xmlns="http://www.w3.org/2000/svg" width="23" height="23"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"
                        aria-hidden="true">
                        <path d="M5 17h14l1-5-2-5H6l-2 5 1 5Z"/>
                        <path d="M4 12h16M7 17v2m10-2v2"/>
                        <circle cx="7.5" cy="14.5" r="1"/>
                        <circle cx="16.5" cy="14.5" r="1"/>
                    </svg>
                </div>
            </div>

            <form wire:submit="save" class="space-y-5">
                <div>
                    <label for="customer_id" class="form-label">Pemilik Kendaraan</label>
                    <select id="customer_id" wire:model="customer_id" class="form-control">
                        <option value="">-- Pilih Pelanggan --</option>
                        @foreach ($customers as $customer)
                            <option value="{{ $customer->id }}">
                                {{ $customer->nama }} - {{ $customer->no_hp }}
                            </option>
                        @endforeach
                    </select>
                    @error('customer_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="plat_nomor" class="form-label">Plat Nomor</label>
                    <input
                        id="plat_nomor"
                        type="text"
                        wire:model.live.debounce.300ms="plat_nomor"
                        placeholder="Contoh: R 1234 AB"
                        class="form-control uppercase"
                    />
                    <p class="mt-1 text-xs text-gray-400">
                        Spasi otomatis dihapus dan huruf diubah menjadi kapital.
                    </p>
                    @error('plat_nomor')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    <div>
                        <label for="merek" class="form-label">Merek Kendaraan</label>
                        <input
                            id="merek"
                            type="text"
                            wire:model="merek"
                            placeholder="Contoh: Honda"
                            class="form-control"
                        />
                        @error('merek')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="tipe" class="form-label">Tipe Kendaraan</label>
                        <input
                            id="tipe"
                            type="text"
                            wire:model="tipe"
                            placeholder="Contoh: Beat"
                            class="form-control"
                        />
                        @error('tipe')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="tahun" class="form-label">Tahun Kendaraan</label>
                    <input
                        id="tahun"
                        type="number"
                        wire:model="tahun"
                        min="1900"
                        max="{{ date('Y') }}"
                        placeholder="Contoh: 2022"
                        class="form-control"
                    />
                    @error('tahun')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

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
                            {{ $vehicleId ? 'Simpan Perubahan' : 'Simpan Kendaraan' }}
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

    {{-- Tabel kendaraan --}}
    <div class="card overflow-hidden">
        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-purple-50 p-5">
            <div>
                <h2 class="font-extrabold text-gray-900">Daftar Kendaraan</h2>
                <p class="mt-1 text-xs text-gray-500">
                    Data kendaraan yang terdaftar di bengkel.
                </p>
            </div>

            <span class="rounded-full bg-purple-50 px-3 py-1.5 text-xs font-bold text-violet-700">
                {{ $vehicles->total() }} data
            </span>
        </div>

        <div class="border-b border-gray-100 p-5">
            <div class="relative">
                <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19"
                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"
                    class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"
                    aria-hidden="true">
                    <circle cx="11" cy="11" r="7"/>
                    <path d="m20 20-4-4"/>
                </svg>

                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Cari plat nomor, merek, atau nama pelanggan..."
                    class="form-control pl-11"
                />
            </div>
        </div>

        <div class="table-wrapper">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Plat Nomor</th>
                        <th>Kendaraan</th>
                        <th>Tahun</th>
                        <th>Pemilik</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($vehicles as $vehicle)
                        <tr wire:key="vehicle-{{ $vehicle->id }}">
                            <td>
                                <span class="font-semibold text-gray-400">
                                    {{ $vehicles->firstItem() + $loop->index }}
                                </span>
                            </td>

                            <td>
                                <span class="inline-flex whitespace-nowrap rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 font-extrabold tracking-wider text-gray-800">
                                    {{ $vehicle->plat_nomor }}
                                </span>
                            </td>

                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-purple-100 text-purple-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"
                                            aria-hidden="true">
                                            <path d="M5 17h14l1-5-2-5H6l-2 5 1 5Z"/>
                                            <path d="M4 12h16M7 17v2m10-2v2"/>
                                            <circle cx="7.5" cy="14.5" r="1"/>
                                            <circle cx="16.5" cy="14.5" r="1"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-800">{{ $vehicle->merek }}</p>
                                        <p class="mt-1 text-xs text-gray-400">{{ $vehicle->tipe }}</p>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <span class="rounded-lg bg-indigo-50 px-3 py-1.5 text-xs font-bold text-indigo-700">
                                    {{ $vehicle->tahun }}
                                </span>
                            </td>

                            <td>
                                <div>
                                    <p class="font-semibold text-gray-700">
                                        {{ $vehicle->customer->nama }}
                                    </p>
                                    <p class="mt-1 text-xs text-gray-400">
                                        {{ $vehicle->customer->no_hp }}
                                    </p>
                                </div>
                            </td>

                            <td>
                                <div class="flex items-center gap-2">
                                    <button
                                        wire:click="edit({{ $vehicle->id }})"
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
                                        wire:click="delete({{ $vehicle->id }})"
                                        wire:confirm="Yakin ingin menghapus kendaraan ini?"
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
                            <td colspan="6" class="px-6 py-16 text-center">
                                <div class="mx-auto mb-3 flex h-14 w-14 items-center justify-center rounded-2xl bg-purple-50 text-purple-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"
                                        aria-hidden="true">
                                        <path d="M5 17h14l1-5-2-5H6l-2 5 1 5Z"/>
                                        <path d="M4 12h16M7 17v2m10-2v2"/>
                                        <circle cx="7.5" cy="14.5" r="1"/>
                                        <circle cx="16.5" cy="14.5" r="1"/>
                                    </svg>
                                </div>
                                <p class="font-bold text-gray-700">Data kendaraan belum ditemukan</p>
                                <p class="mt-1 text-sm text-gray-400">
                                    Coba kata kunci lain atau tambahkan kendaraan baru.
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-gray-100 p-5">
            {{ $vehicles->links() }}
        </div>
    </div>
</div>