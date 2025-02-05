<div class="content-body">
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Edit Barang</h4>
                    <span class="ml-1">Form Edit Barang</span>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Barang</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Edit Barang</a></li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="<?= base_url('home/aksi_edit_barang') ?>" method="POST" enctype="multipart/form-data">
                            <div class="form-group">
                                <input type="hidden" value="<?= $barang->id_barang ?>" name="id">

                                <!-- Barcode -->
                                <label for="barcode">Barcode</label>
                                <input type="text" name="barcode" id="barcode" class="form-control" placeholder="Masukkan Barcode" value="<?= $barang->barcode ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="kode_barang">Kode Barang</label>
                                <input type="text" name="kode_barang" id="kode_barang" class="form-control" placeholder="Masukkan Kode Barang" value="<?= $barang->kode_barang ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="nama_barang">Nama Barang</label>
                                <input type="text" name="nama_barang" id="nama_barang" class="form-control" placeholder="Masukkan Nama Barang" value="<?= $barang->nama_barang ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="harga_jual">Harga Jual</label>
                                <input type="number" name="harga" id="harga_jual" class="form-control" placeholder="Masukkan Harga Jual" value="<?= $barang->harga_jual ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="harga_beli">Harga Beli</label>
                                <input type="number" name="harga_beli" id="harga_beli" class="form-control" placeholder="Masukkan Harga Beli" value="<?= $barang->harga_beli ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="stok">Stok</label>
                                <input type="number" name="stok" id="stok" class="form-control" placeholder="Masukkan Stok" value="<?= $barang->stok ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="foto">Foto Barang</label>
                                <input type="file" name="foto" id="foto" class="form-control">
                                <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah foto.</small>
                                <?php if ($barang->foto): ?>
                                    <img src="<?= base_url('foto/' . $barang->foto) ?>" value="<?=$barang->foto?>" alt="Foto Barang" width="100" class="mt-2">
                                <?php endif; ?>
                            </div>

                            <button type="submit" class="btn btn-primary">Edit Barang</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Barcode Scanner Script -->
<script src="https://unpkg.com/quagga@0.12.1/dist/quagga.min.js"></script>


