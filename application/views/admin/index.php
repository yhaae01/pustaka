<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>

    <!-- Content Kotak Atas -->
    <div class="row">

        <!-- Jumlah Anggota -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Jumlah Anggota</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $this->User_model->getUserWhere(['role_id' => 2])->num_rows(); ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stok Buku Terdaftar -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Stok Buku Terdaftar</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php 
                                    $where = ['stok != 0']; 
                                    $totalstok = $this->Buku_model->total('stok', $where); 
                                    echo $totalstok; 
                                ?> 
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-book fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Buku Dipinjam -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Buku Dipinjam</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php 
                                    $where = ['dipinjam != 0']; 
                                    $totaldipinjam = $this->Buku_model->total('dipinjam', $where); 
                                    echo $totaldipinjam; 
                                ?> 
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-tag fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Buku Dibooking -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Buku Dibooking</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php 
                                    $where = ['dibooking !=0']; 
                                    $totaldibooking = $this->Buku_model->total('dibooking', $where); 
                                    echo $totaldibooking; 
                                ?> 
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Content Kotak Atas -->

</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->
