@extends('layouts.app')

@section('content')
    <div class="max-w-xl mx-auto">
        <div class="card">
            <div class="card-header flex items-center gap-2">
                <span class="iconify w-5 h-5 text-slate-400" data-icon="heroicons:user-plus"></span>
                Tambah Pengguna Baru
            </div>
            <div class="card-body">
                <form action="{{ route('users.store') }}" method="POST" class="space-y-5">
                    @csrf

                    <div>
                        <label for="name" class="form-label">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" id="name" name="name" class="form-input" value="{{ old('name') }}" required>
                    </div>

                    <div>
                        <label for="username" class="form-label">Username</label>
                        <input type="text" id="username" name="username" class="form-input" value="{{ old('username') }}"
                            placeholder="Optional">
                    </div>

                    <div>
                        <label for="email" class="form-label">Email <span class="text-red-500">*</span></label>
                        <input type="email" id="email" name="email" class="form-input" value="{{ old('email') }}" required>
                    </div>

                    <div>
                        <label for="role" class="form-label">Role <span class="text-red-500">*</span></label>
                        <select id="role" name="role" class="form-select" required>
                            <option value="">Pilih Role</option>
                            <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                        <p class="text-xs text-slate-500 mt-2">
                            <strong>User:</strong> Input transaksi, kelola kas kecil<br>
                            <strong>Admin:</strong> Semua akses termasuk validasi, rekonsiliasi, dan kelola user
                        </p>
                    </div>

                    <div>
                        <label for="password" class="form-label">Password <span class="text-red-500">*</span></label>
                        <input type="password" id="password" name="password" class="form-input" required minlength="8">
                    </div>

                    <div>
                        <label for="password_confirmation" class="form-label">Konfirmasi Password <span
                                class="text-red-500">*</span></label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-input"
                            required>
                    </div>

                    <div class="flex items-center gap-3 pt-4 border-t border-slate-100">
                        <button type="submit" class="btn-primary">
                            <span class="iconify w-5 h-5" data-icon="heroicons:check-circle"></span>
                            Simpan
                        </button>
                        <a href="{{ route('users.index') }}" class="btn-secondary">
                            <span class="iconify w-5 h-5" data-icon="heroicons:x-mark"></span>
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection