<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 fw-bold text-dark">{{ __('إدارة الحسابات') }}</h2>
    </x-slot>

    <div class="container py-4">
        {{-- رسالة نجاح --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- جدول المستخدمين --}}
        <div class="card mb-5">
            <div class="card-header bg-primary text-white fw-semibold">
                قائمة المستخدمين
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-striped align-middle text-center" id="users-table">

                        <thead class="table-light">
                            <tr>
                                <th>الاسم</th>
                                <th>البريد</th>
                                <th>الدور</th>
                                <th>الصلاحيات</th>
                                <th>إجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                <tr data-id="{{ $user->id }}">
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        {{ $user->roles->pluck('name')->map(fn($name) => __('permissions.' . $name))->implode(', ') }}
                                    </td>
                                    <td>
                                        {{ $user->permissions->pluck('name')->map(fn($name) => __('permissions.' . $name))->implode(', ') }}
                                    </td>

                                    <td>
                                        <button class="btn btn-sm btn-warning edit-btn" data-id="{{ $user->id }}">
                                            تعديل
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- إنشاء مستخدم جديد --}}
        <div class="card">
            <div class="card-header bg-success text-white fw-semibold">
                إنشاء مستخدم جديد
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('users.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">الاسم</label>
                        <input type="text" name="name" class="form-control" required value="{{ old('name') }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">البريد الإلكتروني</label>
                        <input type="email" name="email" class="form-control" required value="{{ old('email') }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">كلمة المرور</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">الدور</label>
                        <select name="role" class="form-select" required>
                            <option value="">-- اختر الدور --</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}">{{ __('permissions.' . $role->name) }}</option>
                            @endforeach
                        </select>

                    </div>

                    <button type="submit" class="btn btn-success">
                        حفظ المستخدم
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- مودال تعديل المستخدم --}}
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <form id="editForm">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">تعديل المستخدم</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <input type="hidden" id="user_id">

                        <div class="mb-3">
                            <label class="form-label">الاسم</label>
                            <input type="text" name="name" id="name" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">البريد</label>
                            <input type="email" name="email" id="email" class="form-control" required autocomplete="new-email">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">كلمة المرور الجديدة (اختياري)</label>
                            <input type="text" name="password" id="password" class="form-control" autocomplete="new-password">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">الدور</label>
                            <select name="role" id="role" class="form-select" required></select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">الصلاحيات</label>
                            <div class="row" id="permissions-container"></div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">حفظ</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</x-app-layout>
