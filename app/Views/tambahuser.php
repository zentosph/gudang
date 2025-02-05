<div class="content-body">
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Tambah user</h4>
                    <span class="ml-1">Form Tambah user</span>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">user</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Tambah user</a></li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="<?= base_url('home/aksi_tambah_user') ?>" method="POST">
                            <div class="form-group">
                                <label for="user">Nama user</label>
                                <input type="text" name="nama_user" id="user" class="form-control" placeholder="Masukkan Nama user" required>
                            </div>

                            <div class="form-group">
                                <label for="user">Email user</label>
                                <input type="email" name="email"  class="form-control" placeholder="Masukkan Email user" required>
                            </div>

                            <div class="form-group">
    <label for="level">Level</label>
    <select name="level" id="level" class="form-control" required>
        <option value="">Pilih Level</option>
        <?php foreach ($level as $lvl): ?>
            <option value="<?= $lvl->id_level; ?>"><?= $lvl->level; ?></option>
        <?php endforeach; ?>
    </select>
</div>



                            <button type="submit" class="btn btn-primary">Tambah user</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Menampilkan dropdown kelas hanya jika level "Siswa" dipilih
    document.getElementById('level').addEventListener('change', function () {
        var level = this.value;
        var kelasContainer = document.getElementById('kelas-container');
        if (level === 'Siswa') {
            kelasContainer.style.display = 'block';
        } else {
            kelasContainer.style.display = 'none';
        }
    });
</script>
