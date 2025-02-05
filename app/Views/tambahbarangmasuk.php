<div class="content-body">
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Tambah Barang Masuk</h4>
                    </div>
                    <div class="card-body">
                        <div class="basic-form">
                            <form action="<?= base_url('home/aksi_tambah_barangmasuk') ?>" method="post">
                                
                                <!-- Pilih Barang -->
                                <div class="form-group">
                                    <h6 class="text-label">Barang</h6>
                                    <select class="form-control" name="id_barang" required>
                                        <option value="">Pilih Barang</option>
                                        <?php foreach ($barang as $record): ?>
                                            <option value="<?= $record->id_barang ?>"><?= $record->nama_barang ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <!-- Quantity -->
                                <div class="form-group">
                                    <h6 class="text-label">Quantity</h6>
                                    <input type="number" class="form-control" name="quantity" placeholder="Masukkan jumlah barang" required>
                                </div>

                                <!-- Tanggal -->
                                <div class="form-group">
                                    <h6 class="text-label">Tanggal</h6>
                                    <input type="date" class="form-control" name="tanggal" required>
                                </div>

                                <!-- Submit Button -->
                                <button type="submit" class="btn btn-primary">Tambah Barang Masuk</button>
                                <button type="button" class="btn btn-light">Batal</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
