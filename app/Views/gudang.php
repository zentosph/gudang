<style>
    .hihihi{
        margin-left: -100px;
    }
</style>
<div class="content-body">
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Daftar Barang</h4>
                    <span class="ml-1">Datatable</span>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Table</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Barang</a></li>
                </ol>
            </div>
        </div>

        <!-- Tombol Tambah Barang -->
        <div class="row mb-3">
            <div class="col-md-3">
                <a href="<?= base_url('home/TambahBarang') ?>">
                    <button class="btn btn-primary">Tambah Barang</button>                  
                </a>
               
            </div>
            <button class="btn btn-success hihihi" id="scanner-btn">Pindai Barcode</button>
            
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
                                        <th>Barcode</th>
                                        <th>Kode Barang</th>
                                        <th>Nama Barang</th>
                                        <th>Stok</th>
                                        <th>Foto</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $no = 1; 
                                    foreach ($barang as $record): ?>
                                    <tr class="item-row" data-barcode="<?= $record->barcode ?>">
                                        <td><?= $no++ ?></td>
                                        <td> <img src="<?= base_url('barcode/barcode_' . $record->barcode .'.png') ?>" alt="Foto Barang" width="50"> </td>
                                        <td><?= $record->kode_barang ?></td>
                                        <td><?= $record->nama_barang ?></td>
                                        <td><?= $record->stok ?></td>
                                        <td>
                                            <img src="<?= base_url('foto/' . $record->foto) ?>" alt="Foto Barang" width="50">
                                        </td>
                                        <td>
                                            <a href="<?= base_url('home/EditBarang/' . $record->id_barang) ?>">
                                                <button class="btn btn-dark">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                            </a>
                                            <a href="<?= base_url('home/SDBarang/' . $record->id_barang) ?>" onclick="return confirm('Yakin ingin menghapus barang ini?')">
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
                                        <th>Barcode</th>
                                        <th>Kode Barang</th>
                                        <th>Nama Barang</th>
                                        <th>Stok</th>
                                        <th>Foto</th>
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

<!-- Include jQuery and Barcode Scanner Script -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="<?= base_url('vendor/global/global.min.js') ?>"></script>
<script src="<?= base_url('js/quixnav-init.js') ?>"></script>
<script src="<?= base_url('js/custom.min.js') ?>"></script>
<script src="<?= base_url('vendor/datatables/js/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('js/plugins-init/datatables.init.js') ?>"></script>

<!-- Include QuaggaJS for Barcode Scanning -->
<script src="https://cdn.jsdelivr.net/npm/quagga@0.12.1/dist/quagga.min.js"></script>

<script>
    var $jq = jQuery.noConflict();
    $jq(document).ready(function() {
        var table = $jq('#example').DataTable();
    });

    // Barcode scanner button click event
    $jq('#scanner-btn').on('click', function() {
        // Open barcode scanner using QuaggaJS
        Quagga.init({
            inputStream: {
                name: "Live",
                type: "LiveStream",
                target: document.querySelector('#scanner-container') // Container for the camera
            },
            decoder: {
                readers: ["code_128_reader"] // You can add other barcode types here
            }
        }, function(err) {
            if (err) {
                console.log(err);
                return;
            }
            Quagga.start(); // Start scanning
        });
    });

    // When a barcode is detected
    Quagga.onDetected(function(result) {
        var scannedBarcode = result.codeResult.code;
        alert("Scanned Barcode: " + scannedBarcode); // You can replace this with your own action

        // Hide all rows initially
        $jq('.item-row').hide();

        // Show only the row with matching barcode
        $jq('.item-row[data-barcode="' + scannedBarcode + '"]').show();
    });
</script>

<!-- Container for the barcode scanner view -->
<div id="scanner-container" style="width: 100%; height: 300px;"></div>
