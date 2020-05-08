<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>
        <div class="row">
            <div class="col-lg-6">
                <form action="" method="post">
                    <input type="hidden" name="id" value="<?= $user_role['id']; ?>">
                    <div class="form-group">
                        <input type="text" class="form-control" name="role" id="role" value="<?= $user_role['role']; ?>">
                        <?= form_error('role', '<small class="text-danger pl-3">', '</small>'); ?>
                    </div>
                    <button type="submit" class="btn btn-primary">Ubah</button>
                </form>
            </div>
        </div>

</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->
