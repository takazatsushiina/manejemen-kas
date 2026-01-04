@extends('layouts.app')

@section('content')
    <div class="max-w-xl mx-auto">
        <div class="card">
            <div class="card-header flex items-center gap-2">
                <span class="iconify w-5 h-5 text-slate-400" data-icon="heroicons:user-circle"></span>
                Edit Pengguna
            </div>
            <div class="card-body">
                <form action="{{ route('users.update', $user) }}" method="POST" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="name" class="form-label">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" id="name" name="name" class="form-input" value="{{ old('name', $user->name) }}"
                            required>
                    </div>

                    <div>
                        <label for="username" class="form-label">Username</label>
                        <input type="text" id="username" name="username" class="form-input"
                            value="{{ old('username', $user->username) }}" placeholder="Optional">
                    </div>

                    <div>
                        <label for="email" class="form-label">Email <span class="text-red-500">*</span></label>
                        <input type="email" id="email" name="email" class="form-input"
                            value="{{ old('email', $user->email) }}" required>
                    </div>

                    <div>
                        <label for="role" class="form-label">Role <span class="text-red-500">*</span></label>
                        <select id="role" name="role" class="form-select" required>
                            <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>User</option>
                            <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                    </div>

                    <div class="pt-4 border-t border-slate-100">
                        <p class="text-sm text-slate-500 mb-4">Kosongkan password jika tidak ingin mengubah</p>

                        <div class="space-y-5">
                            <div>
                                <label for="password" class="form-label">Password Baru</label>
                                <input type="password" id="password" name="password" class="form-input" minlength="8">
                            </div>

                            <div>
                                <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                    class="form-input">
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 pt-4 border-t border-slate-100">
                        <button type="submit" class="btn-primary">
                            <span class="iconify w-5 h-5" data-icon="heroicons:check-circle"></span>
                            Simpan Perubahan
                        </button>
                        <a href="{{ route('users.show', $user) }}" class="btn-secondary">
                            <span class="iconify w-5 h-5" data-icon="heroicons:x-mark"></span>
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection