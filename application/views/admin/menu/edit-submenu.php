<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>
        <div class="row">
            <div class="col-lg-6">
                <form action="<?= base_url('menu/SubMenu'); ?>" method="post">
                <input type="hidden" name="id" value="<?= $user_sub_menu['id']; ?>">
                    <div class="form-group">
                    <label for="title">Title :</label>
                        <input type="text" class="form-control" name="title" id="title" value="<?= $user_sub_menu['title']; ?>">
                    </div>
                    <div class="form-group">
                    <label for="menu">Menu :</label>
                        <select name="menu_id" id="menu_id" class="form-control">
                            <option value="">-- Select Menu --</option>
                            <?php foreach($menu as $m) : ?>
                            <option value="<?= $m['id']; ?>"  value="<?= $user_sub_menu['menu']; ?>"><?= $m['menu']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                    <label for="url">Url :</label>
                        <input type="text" class="form-control" name="url" id="url">
                    </div>
                    <div class="form-group">
                    <label for="title">Icon :</label>
                        <input type="text" class="form-control" name="icon" id="icon">
                    </div>
                    <div class="form-group">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" name="is_active" id="is_active" checked>
                            <label class="form-check-label" for="is_active">
                                Is Active?
                            </label>
                        </div>
                    </div>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add</button>
                </form>
            </div>
        </div>

</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->
