<div class="container">
    <div class="row">

        <div class="col-lg-5 mt-3 mx-auto">
            <div class="card">
                <div class="card-body">
                <h5 class="text-center">Login</h5>
                <hr>
                <?= $this->session->flashdata('message'); ?>
                    <form action="<?= base_url('member'); ?>" method="post">
                        <div class="form-group">
                            <input type="text" class="form-control" autocomplete="no" id="email" name="email" placeholder="Email . . .">
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control" id="password" name="password" placeholder="Password . . .">
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Login</button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
