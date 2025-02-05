<div class="content-body">
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Barang Masuk</h4>
                    <span class="ml-1">Datatable</span>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Table</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Barang Masuk</a></li>
                </ol>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-3">
                <a href="<?= base_url('home/TambahBarangMasuk') ?>">
                    <button class="btn btn-primary">Tambah Barang</button>
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example" class="display" style="min-width: 845px; color: black;">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Barang</th>
                                        <th>Quantity</th>
                                        <th>Tanggal</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $no = 1; 
                                    foreach ($barangmasuk as $record): ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= $record->nama_barang ?></td>
                                        <td><?= $record->quantity ?></td>
                                        <td><?= $record->tanggal ?></td>
                                        <td>
                                            <a href="<?= base_url('home/EditBarangMasuk/' . $record->id_bmasuk) ?>">
                                                <button class="btn btn-dark">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                            </a>
                                            <a href="<?= base_url('home/SDBarangMasuk/' . $record->id_bmasuk) ?>" onclick="return confirm('Yakin ingin menghapus barang ini?')">
                                                <button class="btn btn-danger">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Barang</th>
                                        <th>Quantity</th>
                                        <th>Tanggal</th>
                                        <th>Aksi</th>
                                    </tr>
                                </tfoot>
                            </table>    
                        </div>
                    </div>
                </div>
            </div>
        </div>   
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="<?= base_url('vendor/global/global.min.js') ?>"></script>
<script src="<?= base_url('js/quixnav-init.js') ?>"></script>
<script src="<?= base_url('js/custom.min.js') ?>"></script>
<script src="<?= base_url('vendor/datatables/js/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('js/plugins-init/datatables.init.js') ?>"></script>

<script>
   var $jq = jQuery.noConflict();
   $jq(document).ready(function() {
       var table = $jq('#example').DataTable();
   });
</script>
