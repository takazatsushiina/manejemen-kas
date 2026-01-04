@extends('layouts.app')

@section('content')
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-xl font-bold text-slate-800">Manajemen Pengguna</h1>
            <p class="text-sm text-slate-500">Kelola akun pengguna sistem</p>
        </div>
        <a href="{{ route('users.create') }}" class="btn-primary">
            <span class="iconify w-5 h-5" data-icon="heroicons:plus-circle"></span>
            Tambah Pengguna
        </a>
    </div>

    <!-- Users Table -->
    <div class="table-wrapper">
        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Terdaftar</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>
                                <span class="font-medium text-slate-800">#{{ $user->id }}</span>
                            </td>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="avatar avatar-sm">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-medium text-slate-800">{{ $user->name }}</p>
                                        @if($user->username)
                                            <p class="text-xs text-slate-500">{{ '@' . $user->username }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if($user->role == 'admin')
                                    <span class="badge-success">Admin</span>
                                @else
                                    <span class="badge-primary">User</span>
                                @endif
                            </td>
                            <td class="whitespace-nowrap">{{ $user->created_at->format('d/m/Y') }}</td>
                            <td>
                                <div class="flex items-center justify-center gap-1">
                                    <a href="{{ route('users.show', $user) }}"
                                        class="btn-icon text-slate-500 hover:text-primary-600 hover:bg-primary-50"
                                        title="Lihat">
                                        <span class="iconify w-4 h-4" data-icon="heroicons:eye"></span>
                                    </a>
                                    <a href="{{ route('users.edit', $user) }}"
                                        class="btn-icon text-slate-500 hover:text-amber-600 hover:bg-amber-50" title="Edit">
                                        <span class="iconify w-4 h-4" data-icon="heroicons:pencil-square"></span>
                                    </a>
                                    @if($user->id !== auth()->id())
                                        <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline"
                                            onsubmit="return confirm('Yakin ingin menghapus pengguna ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-icon text-slate-500 hover:text-red-600 hover:bg-red-50"
                                                title="Hapus">
                                                <span class="iconify w-4 h-4" data-icon="heroicons:trash"></span>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <span class="iconify w-12 h-12 mx-auto mb-3 text-slate-300"
                                        data-icon="heroicons:users"></span>
                                    <p>Belum ada pengguna</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="px-5 py-4 border-t border-slate-100">
                {{ $users->links() }}
            </div>
        @endif
    </div>
@endsection