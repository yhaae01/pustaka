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

    <!-- Content Book -->
    <div class="row">
        <div class="table-responsive table-bordered col-lg mt-5"> 
            <div class="page-header"> 
                <span class="fas fa-book text-warning mt-2"> Data Buku</span> 
            </div> 

            <div class="table-responsive"> 
                <table class="table mt-3" id="table-datatable"> 
                    <thead> 
                        <tr> 
                            <th>#</th> 
                            <th>Judul Buku</th> 
                            <th>Pengarang</th> 
                            <th>Penerbit</th> 
                            <th>Tahun Terbit</th> 
                            <th>ISBN</th> 
                            <th>Stok</th> 
                            <th>Dipinjam</th> 
                            <th>Dibooking</th> 
                        </tr> 
                    </thead> 
                
                    <tbody> 
                        <?php 
                        $i = 1; 
                        foreach ($buku as $b) { ?> 
                        <tr> 
                            <td><?= $i++; ?></td> 
                            <td><?= $b['judul_buku']; ?></td> 
                            <td><?= $b['pengarang']; ?></td> 
                            <td><?= $b['penerbit']; ?></td>
                            <td><?= $b['tahun_terbit']; ?></td> 
                            <td><?= $b['isbn']; ?></td> 
                            <td><?= $b['stok']; ?></td> 
                            <td><?= $b['dipinjam']; ?></td> 
                            <td><?= $b['dibooking']; ?></td> 
                            </tr> 
                        <?php } ?> 
                    </tbody> 
                </table> 
                <?= $this->pagination->create_links(); ?>
            </div> 
        </div>
    </div>
    <!-- End Content Book -->

    <!-- Content User -->
    <div class="row">
        <div class="table-responsive table-bordered col-lg mt-3">
            
            <div class="page-header"> 
                <span class="fas fa-users text-primary mt-2 "> Data User</span> 
            </div> 

            <table class="table mt-3"> 
                <thead> 
                    <tr> 
                        <th>#</th> 
                        <th>Nama Anggota</th> 
                        <th>Email</th> 
                        <th>Aktif</th> 
                        <th>Member Sejak</th> 
                    </tr> 
                </thead> 

                <tbody> 
                    <?php 
                    $i = 1; 
                    foreach ($anggota as $a) { ?> 
                    <tr> 
                        <td><?= $i++; ?></td>
                        <td><?= ucwords($a['name']); ?></td> 
                        <td><?= $a['email']; ?></td> 
                        <td><?= $a['is_active']; ?></td> 
                        <td><?= date('d F Y', $a['date_created']); ?></td> 
                    </tr> 
                    <?php } ?> 
                </tbody> 
            </table>

        </div>
    </div>
    <!-- End Content User -->

</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->
