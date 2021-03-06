<?= $this->extend('admin/layouts/app') ?>

<?= $this->section('content') ?>
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">

            <?= $this->include('admin/layouts/messages') ?>

            <h4 class="card-title">User List</h4>
            <a href="<?= base_url() . '/admin/users/create'; ?>" type="button" class="btn btn-primary">User Create</a>

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>E-Mail</th>
                            <th>Money Limit</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user) { ?>
                            <tr>
                                <td><?= $user['id']; ?></td>
                                <td><?= $user['name']; ?></td>
                                <td><?= $user['email']; ?></td>
                                <td><?= $user['money_limit']; ?></td>
                                <td>
                                    <a href="<?= base_url() . '/admin/users/edit/' . $user['id']; ?>" type="button" class="btn btn-warning">Edit</a>
                                    <a href="<?= base_url() . '/admin/users/delete/' . $user['id']; ?>" type="button" class="btn btn-danger">Delete</a>
                                    <a href="<?= base_url() . '/admin/users/caseDetail/' . $user['id']; ?>" type="button" class="btn btn-primary">Case</a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>