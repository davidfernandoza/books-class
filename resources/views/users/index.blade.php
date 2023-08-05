<x-app title="Usuarios">
    <section class="container">
        <div class="d-flex justify-content-center my-4">
            <h1>Listado de usuarios</h1>
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-end">
                <a href="{{ route('users.create') }}" class="btn btn-primary">Crear usuario</a>
            </div>
            <div class="card-body my-2">
                <div class="table-responsive my-4 mx-2">
                    <table class="table table-bordered table-wrap" id="users_table">
                        <thead>
                            <tr>
                                <th>Cedula</th>
                                <th>Nombre</th>
                                <th>Apellido</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <th>{{ $user->number_id }}</th>
                                    <th>{{ $user->name }}</th>
                                    <th>{{ $user->last_name }}</th>
                                    <th>{{ $user->email }}</th>
                                    <th> {{ $user->roles[0]->name }}</th>
                                    <th>
                                        <div class="d-flex justify-content-center">
                                            <a href="{{ route('users.edit', ['user' => $user->id]) }}"
                                                class="btn btn-warning btn-sm"><i class="fas fa-pencil-alt"></i></a>

                                            <button class="ms-2 btn btn-danger btn-sm"
                                                onclick="deleteForm({{ $user->id }})">
                                                <i class="fas fa-trash"></i>
                                            </button>

                                            <form id="delete_form_{{ $user->id }}"
                                                action="{{ route('users.destroy', ['user' => $user->id]) }}"
                                                method="post">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </div>
                                    </th>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    {{-- Script --}}
    <x-slot:script>
        <script>
            document.addEventListener("DOMContentLoaded", loadDatatable);

            function loadDatatable() {
                $('#users_table').DataTable()
            };

            async function deleteForm(user_id) {
                const response = await Swal.fire({
                    icon: 'warning',
                    title: 'Esta seguro de eliminar?',
                    showCancelButton: true
                })
                if (!response.isConfirmed) return
                document.getElementById(`delete_form_${user_id}`).submit();
            };
        </script>
    </x-slot:script>
</x-app>
