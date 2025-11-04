@extends('layouts.user')

@section('content')
<div class="container py-4">
    <div class="card shadow-lg border-0 p-4">
        <h2 class="text-center fw-bold mb-4">
            <i class="bi bi-person-circle text-success me-2"></i>Edit User
        </h2>

        <form id="editUserForm">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label fw-semibold">Full Name:</label>
                <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Username:</label>
                <input type="text" name="username" class="form-control" value="{{ $user->username }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Email:</label>
                <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Phone Number:</label>
                <input type="text" name="phone_number" class="form-control" value="{{ $user->phone_number }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">New Password (leave blank to keep current):</label>
                <input type="password" name="password" class="form-control" placeholder="••••••">
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Confirm Password:</label>
                <input type="password" name="password_confirmation" class="form-control" placeholder="••••••">
            </div>

            <div class="d-flex justify-content-center gap-2 mt-4">
                <button type="submit" class="btn btn-success px-4">
                    <i class="bi bi-floppy me-1"></i> Save Changes
                </button>
                <a href="{{ route('user.dashboard') }}" class="btn btn-secondary px-4">
                    <i class="bi bi-arrow-left-circle me-1"></i> Back
                </a>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const form = document.getElementById('editUserForm');

    form.addEventListener('submit', async e => {
        e.preventDefault();

        const formData = {
            _token: form.querySelector('input[name="_token"]').value,
            _method: 'PUT',
            name: form.name.value,
            username: form.username.value,
            email: form.email.value,
            phone_number: form.phone_number.value,
            password: form.password.value,
            password_confirmation: form.password_confirmation.value,
        };

        Swal.fire({
            title: 'Updating...',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        try {
            const res = await fetch(`{{ route('user.update', $user->id) }}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value
                },
                body: JSON.stringify({
                    name: form.name.value,
                    username: form.username.value,
                    email: form.email.value,
                    phone_number: form.phone_number.value,
                    password: form.password.value,
                    password_confirmation: form.password_confirmation.value,
                })
            });
            const data = await res.json();
            Swal.close();

            if (data.status === 'success') {
                Swal.fire('Success', data.message, 'success');
            } else {
                Swal.fire('Error', data.message || 'Failed to update user.', 'error');
            }
        } catch (err) {
            Swal.close();
            Swal.fire('Error', 'Server error or network issue.', 'error');
            console.error(err);
        }
    });
</script>
@endsection
