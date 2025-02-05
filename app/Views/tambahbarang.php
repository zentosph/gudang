<div class="content-body">
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Tambah Barang</h4>
                    </div>
                    <div class="card-body">
                        <div class="basic-form">
                            <form action="<?= base_url('home/aksi_tambah_barang') ?>" method="post" enctype="multipart/form-data">
                                
                                <!-- Tampilkan Live Kamera -->
                                <div class="form-group">
                                    <h6 class="text-label">Scan Barcode (Kamera)</h6>
                                    <video id="cameraPreview" autoplay playsinline style="width: 250px; height: 150px; border: 2px solid #000; border-radius: 5px;"></video>
                                </div>

                                <!-- Barcode -->
                                <div class="form-group">
                                    <h6 class="text-label">Barcode (Hasil Scan / Manual)</h6>
                                    <input type="text" class="form-control" name="barcode" id="barcode" placeholder="Hasil scan akan muncul di sini" required>
                                </div>

                                <!-- Kode Barang -->
                                <div class="form-group">
                                    <h6 class="text-label">Kode Barang</h6>
                                    <input type="text" class="form-control" name="kode_barang" placeholder="Masukkan kode barang" required>
                                </div>

                                <!-- Nama Barang -->
                                <div class="form-group">
                                    <h6 class="text-label">Nama Barang</h6>
                                    <input type="text" class="form-control" name="nama_barang" placeholder="Masukkan nama barang" required>
                                </div>

                                <!-- Harga Jual -->
                                <div class="form-group">
                                    <h6 class="text-label">Harga Jual</h6>
                                    <input type="number" class="form-control" name="harga_jual" placeholder="Masukkan harga jual" required>
                                </div>

                                <!-- Harga Beli -->
                                <div class="form-group">
                                    <h6 class="text-label">Harga Beli</h6>
                                    <input type="number" class="form-control" name="harga_beli" placeholder="Masukkan harga beli" required>
                                </div>

                                <!-- Stok -->
                                <div class="form-group">
                                    <h6 class="text-label">Stok</h6>
                                    <input type="number" class="form-control" name="stok" placeholder="Masukkan jumlah stok" required>
                                </div>

                                <!-- Foto Barang -->
                                <div class="form-group">
                                    <h6 class="text-label">Foto Barang</h6>
                                    <input type="file" class="form-control" name="foto" accept="image/*" required>
                                </div>

                                <!-- Submit Button -->
                                <button type="submit" class="btn btn-primary">Tambah Barang</button>
                                <button type="button" class="btn btn-light">Batal</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Panggil QuaggaJS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>

<script>
    // **1. Tampilkan Kamera saat Halaman Dimuat**
async function startCamera() {
    try {
        const stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: "environment" } });
        document.getElementById("cameraPreview").srcObject = stream;
        startScanner();
    } catch (err) {
        console.error("Gagal mengakses kamera:", err);
    }
}

// **2. Mulai Scan Barcode**
function startScanner() {
    Quagga.init({
        inputStream: {
            name: "Live",
            type: "LiveStream",
            target: document.querySelector("#cameraPreview"),
            constraints: { width: 640, height: 480, facingMode: "environment" }  // Increased resolution
        },
        decoder: { readers: ["ean_reader", "code_128_reader", "upc_reader"] }  // Added upc_reader
    }, function(err) {
        if (err) {
            console.error("QuaggaJS Error:", err);
            return;
        }
        Quagga.start();
    });

    // **3. Saat Barcode Terdeteksi, Masukkan ke Input**
    Quagga.onDetected(function(result) {
        document.getElementById("barcode").value = result.codeResult.code;
        stopScanner();
    });
}

// **4. Berhenti Scan Setelah Barcode Terdeteksi**
function stopScanner() {
    Quagga.stop();
    const stream = document.getElementById("cameraPreview").srcObject;
    if (stream) {
        stream.getTracks().forEach(track => track.stop());
    }
}

// **5. Jalankan Kamera saat Halaman Dimuat**
document.addEventListener("DOMContentLoaded", startCamera);
</script>
