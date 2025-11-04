@extends('layouts.admin')

@section('content')
<div class="content-card">
    <div class="container py-4">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
            <div>
                <h2 class="fw-bold text-dark mb-0">
                    <i class="bi bi-people-fill me-2"></i>User Management
                </h2>
                <small class="text-muted">Manage system users, responders, and administrators.</small>
            </div>
            <button class="btn btn-success mt-2 mt-md-0 shadow-sm" data-bs-toggle="modal" data-bs-target="#addUserModal">
                <i class="bi bi-person-plus-fill me-1"></i> Add New User
            </button>
        </div>

        <!-- Users Table -->
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table align-middle table-hover mb-0">
                        <thead class="table-light border-bottom">
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Role</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                            <tr>
                                <td class="fw-semibold text-muted">{{ $loop->iteration }}</td>
                                <td class="fw-semibold">{{ $user->name }}</td>
                                <td>{{ $user->username }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->phone_number ?? '—' }}</td>
                                <td>
                                    <span class="badge 
                                        @if($user->role === 'admin') bg-danger 
                                        @elseif($user->role === 'responder') bg-primary 
                                        @else bg-secondary @endif
                                        px-3 py-2 rounded-pill">
                                        <i class="bi bi-person-badge me-1"></i>{{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-outline-primary me-1" 
                                                data-bs-toggle="tooltip" title="Edit User"
                                                onclick="editUser({{ $user->id }}, '{{ $user->name }}', '{{ $user->username }}', '{{ $user->email }}', '{{ $user->role }}', '{{ $user->phone_number ?? '' }}')">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger" 
                                                data-bs-toggle="tooltip" title="Delete User"
                                                onclick="deleteUser({{ $user->id }}, '{{ $user->name }}')">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    <i class="bi bi-info-circle me-1"></i>No users found.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content shadow-lg border-0 rounded-4">
      <form id="addUserForm">
        @csrf
        <div class="modal-header bg-success text-white rounded-top-4">
          <h5 class="modal-title"><i class="bi bi-person-plus me-1"></i>Add New User</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body text-dark">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Full Name</label>
              <input type="text" name="name" class="form-control" placeholder="Enter full name" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Username</label>
              <input type="text" name="username" class="form-control" placeholder="Enter username" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Email Address</label>
              <input type="email" name="email" class="form-control" placeholder="example@email.com" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Phone Number</label>
                <input type="text" name="phone_number" class="form-control" placeholder="e.g. +639xxxxxxx" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Password</label>
              <input type="password" name="password" class="form-control" placeholder="••••••" required>
            </div>
            <div class="col-md-12">
              <label class="form-label">Role</label>
              <select name="role" class="form-select">
                <option value="user">Regular User</option>
                <option value="responder">Responder</option>
                <option value="admin">Admin</option>
              </select>
            </div>
          </div>
        </div>
        <div class="modal-footer bg-light">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
              <i class="bi bi-x-circle me-1"></i>Cancel
          </button>
          <button type="submit" class="btn btn-success">
              <i class="bi bi-check-circle me-1"></i>Save User
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener("DOMContentLoaded", () => {
    // Bootstrap tooltip initialization
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    tooltipTriggerList.map(t => new bootstrap.Tooltip(t))
});

document.getElementById('addUserForm').addEventListener('submit', function(e) {
    e.preventDefault();
    fetch('{{ route('admin.users.store') }}', {
        method: 'POST',
        headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
        body: new FormData(this)
    })
    .then(res => res.json())
    .then(data => {
        Swal.fire({
            icon: data.status,
            title: data.title,
            text: data.message,
            confirmButtonColor: '#198754'
        });
        if (data.status === 'success') setTimeout(() => location.reload(), 1200);
    });
});

function editUser(id, name, username, email, role, phone_number = '') {
    Swal.fire({
        title: 'Edit User',
        html: `
            <input id="swal-name" class="swal2-input" placeholder="Full Name" value="${name}">
            <input id="swal-username" class="swal2-input" placeholder="Username" value="${username}">
            <input id="swal-email" class="swal2-input" placeholder="Email" value="${email}">
            <input id="swal-phone" class="swal2-input" placeholder="Phone Number" value="${phone_number}">
            <select id="swal-role" class="swal2-select">
                <option value="user" ${role === 'user' ? 'selected' : ''}>User</option>
                <option value="responder" ${role === 'responder' ? 'selected' : ''}>Responder</option>
                <option value="admin" ${role === 'admin' ? 'selected' : ''}>Admin</option>
            </select>
        `,
        confirmButtonText: 'Save Changes',
        showCancelButton: true,
        confirmButtonColor: '#198754',
        preConfirm: () => {
            const updated = {
                name: document.getElementById('swal-name').value,
                username: document.getElementById('swal-username').value,
                email: document.getElementById('swal-email').value,
                phone_number: document.getElementById('swal-phone').value,
                role: document.getElementById('swal-role').value,
            };
            return fetch(`/admin/users/${id}`, {
                method: 'PUT',
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json'},
                body: JSON.stringify(updated)
            }).then(res => res.json());
        }
    }).then(result => {
        if (result.value) {
            Swal.fire({
                icon: result.value.status,
                title: result.value.title,
                text: result.value.message,
                confirmButtonColor: '#198754'
            });
            if (result.value.status === 'success') setTimeout(() => location.reload(), 1200);
        }
    });
}

function deleteUser(id, name) {
    Swal.fire({
        title: 'Confirm Delete',
        text: `Are you sure you want to remove ${name}?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it',
        cancelButtonText: 'Cancel',
        confirmButtonColor: '#dc3545',
    }).then(result => {
        if (result.isConfirmed) {
            fetch(`/admin/users/${id}`, {
                method: 'DELETE',
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
            })
            .then(res => res.json())
            .then(data => {
                Swal.fire({
                    icon: data.status,
                    title: data.title,
                    text: data.message,
                    confirmButtonColor: '#198754'
                });
                if (data.status === 'success') setTimeout(() => location.reload(), 1200);
            });
        }
    });
}
</script>
@endsection
