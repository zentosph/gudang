<div class="content-body">
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Edit Barang Keluar</h4>
                    </div>
                    <div class="card-body">
                        <div class="basic-form">
                            <form action="<?= base_url('home/aksi_edit_barangkeluar/' . $barangkeluar->id_bkeluar) ?>" method="post">
                            <input type="hidden" class="form-control" name="id" value="<?= $barangkeluar->id_bkeluar ?>" placeholder="Masukkan jumlah barang" required>
                                <!-- Pilih Barang -->
                                <div class="form-group">
                                    <h6 class="text-label">Barang</h6>
                                    <select class="form-control" name="id_barang" required>
                                        <option value="">Pilih Barang</option>
                                        <?php foreach ($barang as $record): ?>
                                            <option value="<?= $record->id_barang ?>" <?= ($record->id_barang == $barangkeluar->id_barang) ? 'selected' : '' ?>>
                                                <?= $record->nama_barang ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <!-- Jumlah -->
                                <div class="form-group">
                                    <h6 class="text-label">Jumlah</h6>
                                    <input type="number" class="form-control" name="jumlah" value="<?= $barangkeluar->jumlah ?>" placeholder="Masukkan jumlah barang" required>
                                </div>

                                <!-- Tanggal -->
                                <div class="form-group">
                                    <h6 class="text-label">Tanggal</h6>
                                    <input type="date" class="form-control" name="tanggal" value="<?= $barangkeluar->tanggal ?>" required>
                                </div>

                                <!-- Submit Button -->
                                <button type="submit" class="btn btn-primary">Update Barang Keluar</button>
                                <button type="button" class="btn btn-light">Batal</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
