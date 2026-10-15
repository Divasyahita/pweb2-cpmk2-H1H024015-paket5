<?php

use App\Models\Customer;
use Livewire\Component;
use Livewire\WithPagination;

new class extends Component
{
    use WithPagination;

    public string $search = '';
    public bool $showForm = false;
    public ?int $customerId = null;

    public string $nama = '';
    public string $no_hp = '';
    public string $alamat = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    protected function rules(): array
    {
        return [
            'nama' => ['required', 'string', 'max:255'],
            'no_hp' => ['required', 'string', 'max:20'],
            'alamat' => ['required', 'string'],
        ];
    }

    public function create(): void
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function edit(int $id): void
    {
        $customer = Customer::findOrFail($id);

        $this->customerId = $customer->id;
        $this->nama = $customer->nama;
        $this->no_hp = $customer->no_hp;
        $this->alamat = $customer->alamat;

        $this->resetValidation();
        $this->showForm = true;
    }

    public function save(): void
    {
        $validated = $this->validate();

        Customer::updateOrCreate(
            ['id' => $this->customerId],
            $validated
        );

        session()->flash(
            'message',
            $this->customerId
                ? 'Data pelanggan berhasil diperbarui.'
                : 'Pelanggan berhasil ditambahkan.'
        );

        $this->resetForm();
    }

    public function delete(int $id): void
    {
        $customer = Customer::findOrFail($id);

        if ($customer->vehicles()->exists()) {
            session()->flash(
                'error',
                'Pelanggan tidak bisa dihapus karena masih memiliki kendaraan.'
            );

            return;
        }

        $customer->delete();

        session()->flash('message', 'Pelanggan berhasil dihapus.');
    }

    public function resetForm(): void
    {
        $this->reset([
            'customerId',
            'nama',
            'no_hp',
            'alamat',
        ]);

        $this->resetValidation();
        $this->showForm = false;
    }

    public function layout(): string
    {
        return 'layouts.app';
    }

    public function with(): array
    {
        return [
            'customers' => Customer::query()
                ->when($this->search, function ($query) {
                    $query->where(function ($q) {
                        $q->where(
                            'nama',
                            'like',
                            '%' . $this->search . '%'
                        )->orWhere(
                            'no_hp',
                            'like',
                            '%' . $this->search . '%'
                        );
                    });
                })
                ->latest()
                ->paginate(10),
        ];
    }
};
?>

<div class="mx-auto max-w-7xl space-y-6">

    {{-- Header halaman --}}
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <div class="mb-2 flex items-center gap-2 text-xs font-semibold text-violet-600">
                <span>Dashboard</span>
                <span class="text-gray-300">/</span>
                <span class="text-gray-400">Pelanggan</span>
            </div>

            <h1 class="text-3xl font-extrabold tracking-tight text-gray-900">
                Data Pelanggan
            </h1>

            <p class="mt-2 text-sm text-gray-500">
                Kelola informasi pelanggan bengkel dengan mudah.
            </p>
        </div>

        <button
            wire:click="create"
            class="btn-primary"
        >
            <span class="text-lg">+</span>
            Tambah Pelanggan
        </button>
    </div>

    {{-- Notifikasi berhasil --}}
    @if (session('message'))
        <div class="alert-success flex items-center gap-3">
            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-green-100 text-green-700">
                <svg xmlns="http://www.w3.org/2000/svg"
                    width="18" height="18" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path d="m5 12 4 4L19 6"/>
                </svg>
            </span>

            <p class="text-sm font-semibold">
                {{ session('message') }}
            </p>
        </div>
    @endif

    {{-- Notifikasi gagal --}}
    @if (session('error'))
        <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm font-medium text-red-700">
            {{ session('error') }}
        </div>
    @endif

    {{-- Ringkasan --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

        {{-- Total pelanggan --}}
        <div class="card flex items-center gap-4 p-5">
            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-violet-100 text-violet-700">
                <svg xmlns="http://www.w3.org/2000/svg"
                    width="24" height="24" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor"
                    stroke-width="1.8" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                    <circle cx="10" cy="7" r="4"/>
                    <path d="M20 21v-2a4 4 0 0 0-3-3.87"/>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
            </div>

            <div>
                <p class="text-sm font-medium text-gray-500">
                    Total Pelanggan
                </p>

                <p class="mt-1 text-2xl font-extrabold text-gray-900">
                    {{ \App\Models\Customer::count() }}
                </p>
            </div>
        </div>

        {{-- Pelanggan ditampilkan --}}
        <div class="card flex items-center gap-4 p-5">
            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-purple-100 text-purple-700">
                <svg xmlns="http://www.w3.org/2000/svg"
                    width="24" height="24" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor"
                    stroke-width="1.8" stroke-linecap="round"
                    stroke-linejoin="round">
                    <rect x="4" y="4" width="16" height="16" rx="2"/>
                    <path d="M8 9h8"/>
                    <path d="M8 13h8"/>
                    <path d="M8 17h4"/>
                </svg>
            </div>

            <div>
                <p class="text-sm font-medium text-gray-500">
                    Pelanggan Ditampilkan
                </p>

                <p class="mt-1 text-2xl font-extrabold text-gray-900">
                    {{ $customers->count() }}
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
                        Form Pelanggan
                    </p>

                    <h2 class="mt-1 text-xl font-extrabold text-gray-900">
                        {{ $customerId ? 'Edit Pelanggan' : 'Tambah Pelanggan Baru' }}
                    </h2>

                    <p class="mt-1 text-sm text-gray-500">
                        Lengkapi informasi pelanggan di bawah ini.
                    </p>
                </div>

                {{-- Ikon form --}}
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-purple-50 text-violet-700">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        width="22" height="22" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor"
                        stroke-width="1.8" stroke-linecap="round"
                        stroke-linejoin="round">
                        <circle cx="12" cy="8" r="4"/>
                        <path d="M5 21v-2a7 7 0 0 1 14 0v2"/>
                    </svg>
                </div>
            </div>

            <form wire:submit="save" class="space-y-5">
                <div>
                    <label for="nama" class="form-label">
                        Nama Lengkap
                    </label>

                    <input
                        id="nama"
                        type="text"
                        wire:model="nama"
                        placeholder="Masukkan nama pelanggan"
                        class="form-control"
                    />

                    @error('nama')
                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label for="no_hp" class="form-label">
                        Nomor HP
                    </label>

                    <input
                        id="no_hp"
                        type="text"
                        wire:model="no_hp"
                        placeholder="Contoh: 081234567890"
                        class="form-control"
                    />

                    @error('no_hp')
                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label for="alamat" class="form-label">
                        Alamat
                    </label>

                    <textarea
                        id="alamat"
                        wire:model="alamat"
                        rows="3"
                        placeholder="Masukkan alamat lengkap"
                        class="form-control"
                    ></textarea>

                    @error('alamat')
                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="flex flex-wrap gap-3 border-t border-gray-100 pt-4">
                    <button
                        type="submit"
                        wire:loading.attr="disabled"
                        class="btn-primary"
                    >
                        <span wire:loading.remove wire:target="save">
                            {{ $customerId ? 'Simpan Perubahan' : 'Simpan Pelanggan' }}
                        </span>

                        <span wire:loading wire:target="save">
                            Menyimpan...
                        </span>
                    </button>

                    <button
                        type="button"
                        wire:click="resetForm"
                        class="btn-secondary"
                    >
                        Batal
                    </button>
                </div>
            </form>
        </div>
    @endif

    {{-- Tabel pelanggan --}}
    <div class="card overflow-hidden">

        {{-- Judul tabel --}}
        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-purple-50 p-5">
            <div>
                <h2 class="font-extrabold text-gray-900">
                    Daftar Pelanggan
                </h2>

                <p class="mt-1 text-xs text-gray-500">
                    Informasi pelanggan yang terdaftar di sistem.
                </p>
            </div>

            <span class="rounded-full bg-purple-50 px-3 py-1.5 text-xs font-bold text-violet-700">
                {{ $customers->total() }} data
            </span>
        </div>

        {{-- Pencarian --}}
        <div class="border-b border-gray-100 p-5">
            <div class="relative">
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"
                    width="20" height="20" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor"
                    stroke-width="1.8" stroke-linecap="round"
                    stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"/>
                    <path d="m21 21-4.35-4.35"/>
                </svg>

                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Cari nama atau nomor HP pelanggan..."
                    class="form-control pl-11"
                />
            </div>
        </div>

        {{-- Isi tabel --}}
        <div class="table-wrapper">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama Pelanggan</th>
                        <th>Nomor HP</th>
                        <th>Alamat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($customers as $customer)
                        <tr wire:key="customer-{{ $customer->id }}">
                            <td class="w-16">
                                <span class="font-semibold text-gray-400">
                                    {{ $customers->firstItem() + $loop->index }}
                                </span>
                            </td>

                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-purple-100 font-extrabold text-violet-700">
                                        {{ strtoupper(substr($customer->nama, 0, 1)) }}
                                    </div>

                                    <span class="font-bold text-gray-800">
                                        {{ $customer->nama }}
                                    </span>
                                </div>
                            </td>

                            <td>
                                <span class="whitespace-nowrap font-medium text-gray-600">
                                    {{ $customer->no_hp }}
                                </span>
                            </td>

                            <td>
                                <span class="inline-block min-w-48 max-w-sm leading-relaxed text-gray-500">
                                    {{ $customer->alamat }}
                                </span>
                            </td>

                            <td>
                                <div class="flex items-center gap-2">
                                    <button
                                        wire:click="edit({{ $customer->id }})"
                                        class="rounded-lg bg-violet-50 px-3 py-2 text-xs font-bold text-violet-700 hover:bg-violet-100"
                                    >
                                        Edit
                                    </button>

                                    <button
                                        wire:click="delete({{ $customer->id }})"
                                        wire:confirm="Yakin ingin menghapus pelanggan ini?"
                                        wire:loading.attr="disabled"
                                        class="rounded-lg bg-red-50 px-3 py-2 text-xs font-bold text-red-600 hover:bg-red-100"
                                    >
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center">

                                {{-- Ikon saat data kosong --}}
                                <div class="mx-auto mb-3 flex h-14 w-14 items-center justify-center rounded-2xl bg-purple-50 text-violet-700">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        width="26" height="26" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor"
                                        stroke-width="1.8" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <circle cx="12" cy="8" r="4"/>
                                        <path d="M5 21v-2a7 7 0 0 1 14 0v2"/>
                                    </svg>
                                </div>

                                <p class="font-bold text-gray-700">
                                    Data pelanggan belum ditemukan
                                </p>

                                <p class="mt-1 text-sm text-gray-400">
                                    Coba gunakan kata kunci lain atau tambahkan pelanggan baru.
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="border-t border-gray-100 p-5">
            {{ $customers->links() }}
        </div>
    </div>
</div>