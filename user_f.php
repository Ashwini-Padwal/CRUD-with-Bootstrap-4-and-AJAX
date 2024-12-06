<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD with Bootstrap 4 and AJAX</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>
    <div class="container">
        <h2 class="mt-4">CRUD with Bootstrap 4 and AJAX</h2>
        <div class="row">
            <div class="col-md-12">
                <button class="btn btn-success mb-3" data-toggle="modal" data-target="#userModal">Add User</button>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="userTable">
                        <!-- Data will be loaded here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- User Modal -->
    <div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userModalLabel">Add User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="userForm">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <input type="hidden" id="userId" name="id">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Load users
            function loadUsers() {
                $.ajax({
                    url: 'crud.php',
                    type: 'POST',
                    data: { action: 'read' },
                    success: function(response) {
                        $('#userTable').html('');
                        const users = JSON.parse(response);
                        users.forEach(user => {
                            $('#userTable').append(`
                                <tr>
                                    <td>${user.id}</td>
                                    <td>${user.name}</td>
                                    <td>${user.email}</td>
                                    <td>
                                        <button class="btn btn-primary btn-sm editBtn" data-id="${user.id}" data-name="${user.name}" data-email="${user.email}"><i class="fas fa-edit"></i></button>
                                        <button class="btn btn-danger btn-sm deleteBtn" data-id="${user.id}"><i class="fas fa-trash-alt"></i></button>
                                    </td>
                                </tr>
                            `);
                        });
                    }
                });
            }

            loadUsers();

            // Save user (create/update)
            $('#userForm').on('submit', function(e) {
                e.preventDefault();
                const id = $('#userId').val();
                const name = $('#name').val();
                const email = $('#email').val();
                const action = id ? 'update' : 'create';

                $.ajax({
                    url: 'crud.php',
                    type: 'POST',
                    data: { id, name, email, action },
                    success: function(response) {
                        $('#userModal').modal('hide');
                        $('#userForm')[0].reset();
                        $('#userId').val('');
                        loadUsers();
                    }
                });
            });

            // Edit user
            $(document).on('click', '.editBtn', function() {
                const id = $(this).data('id');
                const name = $(this).data('name');
                const email = $(this).data('email');

                $('#userId').val(id);
                $('#name').val(name);
                $('#email').val(email);
                $('#userModalLabel').text('Edit User');
                $('#userModal').modal('show');
            });

            // Delete user
            $(document).on('click', '.deleteBtn', function() {
                const id = $(this).data('id');

                if (confirm('Are you sure you want to delete this user?')) {
                    $.ajax({
                        url: 'crud.php',
                        type: 'POST',
                        data: { id, action: 'delete' },
                        success: function(response) {
                            loadUsers();
                        }
                    });
                }
            });

            // Reset modal on close
            $('#userModal').on('hidden.bs.modal', function () {
                $('#userForm')[0].reset();
                $('#userId').val('');
                $('#userModalLabel').text('Add User');
            });
        });
    </script>
</body>
</html>
