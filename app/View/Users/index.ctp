<h1>Users</h1>
<table>
    <tr>
        <th>ID</th>
        <th>Username</th>
        <th>Email</th>
    </tr>
    <?php foreach ($users as $user): ?>
    <tr>
        <td><?php echo h($user['User']['id']); ?></td>
        <td><?php echo h($user['User']['username']); ?></td>
        <td><?php echo h($user['User']['email']); ?></td>
    </tr>
    <?php endforeach; ?>
</table>
