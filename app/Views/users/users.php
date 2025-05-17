<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<main class="container py-5">
    <!-- app/Views/admin/usuarios_listado.php -->

    <h2>Listado de Usuarios</h2>

    <table class="table" cellpadding="10">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre completo</th>
                <th>Username</th>
                <th>Email</th>
                <th>Teléfono</th>
                <th>Rol</th>
                <th>Activo</th>
                <th>Último login</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= esc($user['user_id']) ?></td>
                    <td><?= esc($user['full_name']) ?></td>
                    <td><?= esc($user['username']) ?></td>
                    <td><?= esc($user['email']) ?></td>
                    <td><?= esc($user['phone']) ?></td>
                    <?php if ($user['role_id'] == 1): ?>
                        <td>Administrador</td>
                    <?php elseif ($user['role_id'] == 2): ?>
                        <td>Usuario</td>
                    <?php elseif ($user['role_id'] == 3): ?>
                        <td>Invitado</td>
                    <?php endif; ?>
                    <td><?= $user['is_active'] ? 'Sí' : 'No' ?></td>
                    <td><?= esc($user['last_login']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</main>

<?= $this->endSection() ?>