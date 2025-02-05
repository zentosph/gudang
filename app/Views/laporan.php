<style>
    .btntambah {
        width: 100px;
        height: 30px;
        font-size: 12px;
    }
</style>

<div class="content-body">
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Manajemen Barang</h4>
                    <span class="ml-1">Datatable</span>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Table</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Manajemen Barang</a></li>
                </ol>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h4>Filter Data</h4>
            </div>
            <div class="card-body">
                <form method="GET" action="<?= base_url('home/filtertanggal') ?>">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="startDate">Tanggal Mulai:</label>
                            <input type="date" id="startDate" name="start_date" class="form-control" value="<?= isset($_GET['start_date']) ? $_GET['start_date'] : '' ?>">
                        </div>
                        <div class="col-md-3">
                            <label for="endDate">Tanggal Akhir:</label>
                            <input type="date" id="endDate" name="end_date" class="form-control" value="<?= isset($_GET['end_date']) ? $_GET['end_date'] : '' ?>">
                        </div>
                        <div class="col-md-2">
                            <label>&nbsp;</label>
                            <button type="submit" class="btn btn-primary btn-block">Filter</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Gudang Table -->
        <!-- Gudang Table -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4>Gudang</h4>
        <button class="btn btn-danger" onclick="printPDF('gudang')">Print PDF</button>
    </div>
    <div class="card-body">
        <table id="gudangTable" class="display table table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Barcode</th>
                    <th>Kode Barang</th>
                    <th>Nama Barang</th>
                    <th>Stok</th>
                    <th>Foto</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; foreach ($barang as $record): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><img src="<?= base_url('barcode/barcode_' . $record->barcode .'.png') ?>" width="50"></td>
                    <td><?= $record->kode_barang ?></td>
                    <td><?= $record->nama_barang ?></td>
                    <td><?= $record->stok ?></td>
                    <td><img src="<?= base_url('foto/' . $record->foto) ?>" width="50"></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Barang Masuk Table -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4>Barang Masuk</h4>
        <button class="btn btn-danger" onclick="printPDF('barangmasuk')">Print PDF</button>
    </div>
    <div class="card-body">
        <table id="barangMasukTable" class="display table table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Barang</th>
                    <th>Quantity</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; foreach ($barangmasuk as $record): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= $record->nama_barang ?></td>
                    <td><?= $record->quantity ?></td>
                    <td><?= $record->tanggal ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Barang Keluar Table -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4>Barang Keluar</h4>
        <button class="btn btn-danger" onclick="printPDF('barangkeluar')">Print PDF</button>
    </div>
    <div class="card-body">
        <table id="barangKeluarTable" class="display table table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Barang</th>
                    <th>Jumlah</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; foreach ($barangkeluar as $record): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= $record->nama_barang ?></td>
                    <td><?= $record->jumlah ?></td>
                    <td><?= $record->tanggal ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function printPDF(type) {
    var startDate = document.getElementById("startDate").value;
    var endDate = document.getElementById("endDate").value;
    
    if (!startDate || !endDate) {
        alert("Silakan pilih rentang tanggal terlebih dahulu!");
        return;
    }
    
    var url = '<?= base_url('home/generate_pdf') ?>/' + type + '?start_date=' + encodeURIComponent(startDate) + '&end_date=' + encodeURIComponent(endDate);
    window.open(url, '_blank');
}

</script>


    </div>
</div>

<script>
function generateReport(type) {
    var startDate = document.getElementById("startDate").value;
    var endDate = document.getElementById("endDate").value;
    var url = '<?= base_url('home/generate_report') ?>/' + type + '?start_date=' + encodeURIComponent(startDate) + '&end_date=' + encodeURIComponent(endDate);
    window.open(url, '_blank');
}
</script>
