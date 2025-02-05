<?php

namespace App\Controllers;
use CodeIgniter\Models\Controller;
use App\Models\M_z;
use Picqer\Barcode\BarcodeGeneratorPNG;
use Dompdf\Dompdf;
use Dompdf\Options;
class Home extends BaseController
{


    
	public function index()
	{
        if(session()->get('level')  > 0){
        $model = new M_z();
        $where5 = array('id_setting' => 1);
        $data['setting'] = $model->getwhere('setting',$where5);
		echo view('header',$data);
        echo view('dashboard', $data);
		echo view('menu', $data);
		echo view('footer');
        }else{
            return redirect()->to('home/login');
        }
	}

	private function log_activity($activity)
    {
		$model = new M_z();
        $data = [
            'id_user'    => session()->get('id'),
            'activity'   => $activity,
			'timestamp' => date('Y-m-d H:i:s'),
			'delete' => Null
        ];

        $model->tambah('activity', $data);
    }

	public function login(){
        $model = new M_z();
        $where5 = array('id_setting' => 1);
        $data['setting'] = $model->getwhere('setting', $where5);
		echo view('header', $data);
		echo view('login');
	}

	public function generateCaptcha()
{
    // Create a string of possible characters
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    $captcha_code = '';
    
    // Generate a random CAPTCHA code with letters and numbers
    for ($i = 0; $i < 6; $i++) {
        $captcha_code .= $characters[rand(0, strlen($characters) - 1)];
    }
    
    // Store CAPTCHA code in session
    session()->set('captcha_code', $captcha_code);
    
    // Create an image for CAPTCHA
    $image = imagecreate(120, 40); // Increased size for better readability
    $background = imagecolorallocate($image, 200, 200, 200);
    $text_color = imagecolorallocate($image, 0, 0, 0);
    $line_color = imagecolorallocate($image, 64, 64, 64);
    
    imagefilledrectangle($image, 0, 0, 120, 40, $background);
    
    // Add some random lines to the CAPTCHA image for added complexity
    for ($i = 0; $i < 5; $i++) {
        imageline($image, rand(0, 120), rand(0, 40), rand(0, 120), rand(0, 40), $line_color);
    }
    
    // Add the CAPTCHA code to the image
    imagestring($image, 5, 20, 10, $captcha_code, $text_color);
    
    // Output the CAPTCHA image
    header('Content-type: image/png');
    imagepng($image);
    imagedestroy($image);
}


public function aksi_login()
{
    // Periksa koneksi internet
    if (!$this->checkInternetConnection()) {
        // Jika tidak ada koneksi, cek CAPTCHA gambar
        $captcha_code = $this->request->getPost('captcha_code');
        if (session()->get('captcha_code') !== $captcha_code) {
            session()->setFlashdata('toast_message', 'Invalid CAPTCHA');
            session()->setFlashdata('toast_type', 'danger');
            return redirect()->to('home/login');
        }
    } else {
        // Jika ada koneksi, cek Google reCAPTCHA
        $recaptchaResponse = trim($this->request->getPost('g-recaptcha-response'));
        $secret = '6LeKfiAqAAAAAFkFzd_B9MmWjX76dhdJmJFb6_Vi'; // Ganti dengan Secret Key Anda
        $credential = array(
            'secret' => $secret,
            'response' => $recaptchaResponse
        );

        $verify = curl_init();
        curl_setopt($verify, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
        curl_setopt($verify, CURLOPT_POST, true);
        curl_setopt($verify, CURLOPT_POSTFIELDS, http_build_query($credential));
        curl_setopt($verify, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($verify, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($verify);
        curl_close($verify);

        $status = json_decode($response, true);

        if (!$status['success']) {
            session()->setFlashdata('toast_message', 'Captcha validation failed');
            session()->setFlashdata('toast_type', 'danger');
            return redirect()->to('home/login');
        }
    }

    // Proses login seperti biasa
    $u = $this->request->getPost('username');
    $p = $this->request->getPost('password');

    $where = array(
        'username' => $u,
        'password' => md5($p),
    );
    $model = new M_z;
    $cek = $model->getWhere('user', $where);

    if ($cek) {
        // $this->log_activitys('User Mel$where5 = array('id_setting' => 1);
        session()->set('nama', $cek->username);
        session()->set('id', $cek->id_user);
        session()->set('level', $cek->level);
        return redirect()->to('home/');
    } else {
        session()->setFlashdata('toast_message', 'Invalid login credentials');
        session()->setFlashdata('toast_type', 'danger');
        return redirect()->to('home/login');
    }
}

public function checkInternetConnection()
{
    $connected = @fsockopen("www.google.com", 80);
    if ($connected) {
        fclose($connected);
        return true;
    } else {
        return false;
    }
}

public function logout()
{
    // $this->log_activity('User Logout');
    session()->destroy();
    return redirect()->to('home/login');
}





public function updateMenuVisibilityAjax()
{
    // Get data from the AJAX request
    $menu = $this->request->getPost('menu'); // e.g., 'data', 'dashboard'
    $level = $this->request->getPost('level'); // e.g., 1, 2, 3
    $visibility = $this->request->getPost('visibility'); // 1 or 0

    // Logging the data received from AJAX request
    log_message('debug', 'Received data from AJAX - Menu: ' . $menu . ', Level: ' . $level . ', Visibility: ' . $visibility);

    // Prepare data for the update
    $updateData = [$menu => $visibility];
    $whereCondition = ['level' => $level];

    // Logging the prepared data for the update
    log_message('debug', 'Update Data: ' . json_encode($updateData));
    log_message('debug', 'Where Condition: ' . json_encode($whereCondition));

    // Initialize the model
    $menuModel = new M_z();

    // Call the model method to update the menu visibility
    $result = $menuModel->updateMenuVisibility('menu', $updateData, $whereCondition);

    // Check if the update was successful and log the result
    if ($result) {
        log_message('debug', 'Menu visibility updated successfully.');
        return $this->response->setJSON(['status' => 'success', 'message' => 'Menu visibility updated successfully.']);
    } else {
        log_message('error', 'Failed to update menu visibility.');
        return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to update menu visibility.']);
    }
}

public function aksi_edit_website()
{
    
    // Load the model that interacts with your settings
    $model = new M_z(); // Replace M_p with the actual model name

    // Retrieve the settings from the database
    $where5 = array('id_setting' => 1);
    $setting = $model->getwhere('setting',$where5); // Assuming you have a method to get current settings

    // Get the name from the request
    $name = $this->request->getPost('name');

    $icon = $this->request->getFile('icon');

    // Array to hold image names
    $images = [];

    // Check and upload icon
    if ($icon && $icon->isValid()) {
        $images['icon'] = $icon->getName();
        $model->uploadimages($icon); // Call uploadimages from the model
    } else {
        // Keep the existing icon name if no new file is uploaded
        $images['icon'] = $setting->logo;
    }



    // Update the settings in the database with the new image names and the new name
    $model->updateSettings($name, $images['icon']); // Corrected parameter usage

    return redirect()->to('home/Website'); // Redirect after processing
}

public function filteruserlog() {
    if(session()->get('level')  > 0){
    $model = new M_z(); // Make sure to replace with your actual model for logs
    $idUser = $this->request->getGet('id_user'); // Get the selected user ID from the query string

    // Fetch users for the filter dropdown
    $data['users'] = $model->tampil('user'); // Adjust this method based on how you retrieve users

    // Get logs based on user filter
    if ($idUser) {
        $where = array('activity.id_user' => $idUser, 'activity.delete' => Null);
        $data['log'] = $model->join1where1('activity','user','activity.id_User = user.id_user',$where); // Method to get logs for a specific user
    } else {
        $data['log'] = $model->join1('activity','user','activity.id_User = user.id_user'); // Fetch all logs if no user is selected
    }
    $data['logss'] = $model->join1('activity','user','activity.id_User = user.id_user'); // Fetch all logs if no user is selected
    $where5 = array('id_setting' => 1);
    $data['setting'] = $model->getwhere('setting', $where5);
    $where6 = array('level' => session()->get('level'));
        $data['menu'] = $model->getwhere('menu', $where6);
    echo view('header',$data);
    echo view('menu',$data);
    echo view('activitylog', $data);
    echo view('footer');
}else{
    return redirect()->to('home/login');
}
}

public function LogActivity(){
    if(session()->get('level')  > 0){
    $model = new M_z();
    $where1 = array('activity.delete' => null);
    $data['log'] = $model->join1where1('activity','user','activity.id_user = user.id_user',$where1);
    $data['menus'] = $model->tampil('menu');
    $data['users'] = $model->tampil('user');
    $where5 = array('id_setting' => 1);
    $data['setting'] = $model->getwhere('setting', $where5);
    $where6 = array('level' => session()->get('level'));
        $data['menu'] = $model->getwhere('menu', $where6);
        $this->log_activity('User membuka Setting Website');
    echo view('header', $data);
    echo view('menu', $data);
    echo view('activitylog', $data);
    echo view('footer');
    }else{
        return redirect()->to('home/login');
    }
}

public function Website(){
    if(session()->get('level')  > 0){
    $model = new M_z();
    $data['menus'] = $model->tampil('menu');
    $where5 = array('id_setting' => 1);
    $data['setting'] = $model->getwhere('setting', $where5);
    $where6 = array('level' => session()->get('level'));
        $data['menu'] = $model->getwhere('menu', $where6);
        $this->log_activity('User membuka Setting Website');
    echo view('header', $data);
    echo view('menu', $data);
    echo view('website', $data);
    echo view('footer');
}else{
    return redirect()->to('home/login');
}
}

public function MenuManage(){
    $model = new M_z();
    $data['menus'] = $model->tampil('menu');
    $where5 = array('id_setting' => 1);
    $data['setting'] = $model->getwhere('setting', $where5);
    $where6 = array('level' => session()->get('level'));
        $data['menu'] = $model->getwhere('menu', $where6);
        $this->log_activity('User membuka Manage Menu');
        if ($data['menu']->setting == 1) {
    echo view('header', $data);
    echo view('menu', $data);
    echo view('menu_manage', $data);
    echo view('footer');
}else{
    return redirect()->to('home/login');
}
}

public function user(){
    if(session()->get('level')  > 0){
    $model = new M_z();
    $where5 = array('user.deleted' => Null);
    $data['user'] = $model->join1where1('user','level','user.level = level.id_level', $where5);
    $where5 = array('id_setting' => 1);
    $data['setting'] = $model->getwhere('setting',$where5);
    echo view('header', $data);
    echo view('menu', $data);
    echo view('user', $data);
    echo view('footer');
}else{
    return redirect()->to('home/login');
}
}

public function TambahUser(){
    if(session()->get('level')  > 0){
    $model = new M_z();
    $where6 = array('level' => session()->get('level'));
    $data['menu'] = $model->getwhere('menu', $where6);
    $where5 = array('id_setting' => 1);
    $data['setting'] = $model->getwhere('setting',$where5);

    $model = new M_z();
    $data['menus'] = $model->tampil('menu');
    $data['level'] = $model->tampil('level');
    // Ambil setting dari model
    $where5 = array('id_setting' => 1);
    $data['setting'] = $model->getwhere('setting', $where5);
    $this->log_activity('User membuka Kategori');


    echo view('header', $data);
    echo view('menu', $data);
    echo view('tambahuser', $data);
    echo view('footer');
}else{
    return redirect()->to('home/login');
}
}


public function aksi_tambah_User() {
    $model = new M_z();
    
    // Ambil data dari form
    $user = $this->request->getPost('nama_user');
    $level = $this->request->getPost('level');
    $email = $this->request->getPost('email');
    // Set password default
    $password = md5('sph');
    
    // Menyusun data yang akan dimasukkan ke dalam database
    $data = [
        'username' => $user,
        'password' => $password,
        'level' => $level,
        'email' => $email,
        'created_at' => date('Y-m-d H:i:s')
    ];

    // Menambahkan data user ke dalam tabel 'user'
    $model->tambah('user', $data);

    // Mengalihkan ke halaman daftar user setelah berhasil
    return redirect()->to('home/User');
}

public function EditUser($id){
    if(session()->get('level')  > 0){
    $model = new M_z();
    $where6 = array('level' => session()->get('level'));
    $data['menu'] = $model->getwhere('menu', $where6);

    $model = new M_z();
    $data['menus'] = $model->tampil('menu');
    
    // Ambil setting dari model
    $where5 = array('id_setting' => 1);
    $data['setting'] = $model->getwhere('setting', $where5);
    $this->log_activity('User membuka Kategori');
    
    // Ambil kategori dari model
    $where = array('deleted' => Null);
    $where1 = array('id_user' => $id);
    $data['user'] = $model->tampilwhere2Row('user', $where, $where1);
    $data['level'] = $model->tampil('level');

    echo view('header', $data);
    echo view('menu', $data);
    echo view('edituser', $data);
    echo view('footer');
}else{
    return redirect()->to('home/login');
}
}

public function aksi_edit_User() {
    $model = new M_z();
    
    // Ambil data dari form
    $user = $this->request->getPost('nama_user');
    $level = $this->request->getPost('level');
    $email = $this->request->getPost('email'); // Only for Siswa
    $id = $this->request->getPost('id');

    // Menyusun data yang akan dimasukkan ke dalam database
    $data = [
        'username' => $user,
        'level' => $level,
        'email' => $email,  // If level is Siswa, update id_kelas
    ];

    // If password is being updated
    $password = $this->request->getPost('password');
    if (!empty($password)) {
        $data['password'] = md5($password);  // Update password if provided
    }

    $where = ['id_user' => $id];

    // Menyimpan perubahan data user ke dalam database
    $model->edit('user', $data, $where);

    // Mengalihkan ke halaman daftar user setelah berhasil
    return redirect()->to('home/user');
}

public function SDuser($id){
    $model = new M_z();
    $data = [
        'deleted' => date('Y-m-d H:i:s')
    ];
    $where = array('id_user' => $id);
    $model->edit('user', $data, $where);
    return redirect()->to('home/user');
}

public function Gudang(){
    if(session()->get('level')  > 0){
    $model = new M_z();
    $where5 = array('barang.deleted' => Null);
    $data['barang'] = $model->tampilwhere('barang',$where5);
    $where5 = array('id_setting' => 1);
    $data['setting'] = $model->getwhere('setting',$where5);
    echo view('header', $data);
    echo view('menu', $data);
    echo view('gudang', $data);
    echo view('footer');
}else{
    return redirect()->to('home/login');
}
}

public function TambahBarang(){
    if(session()->get('level')  > 0){
    $model = new M_z();
    $where5 = array('barang.deleted' => Null);
    $data['barang'] = $model->tampilwhere('barang',$where5);
    $where5 = array('id_setting' => 1);
    $data['setting'] = $model->getwhere('setting',$where5);
    echo view('header', $data);
    echo view('menu', $data);
    echo view('tambahbarang', $data);
    echo view('footer');
}else{
    return redirect()->to('home/login');
}
}

public function aksi_tambah_Barang() {
    $model = new M_z();

    // Ambil data dari form
    $barcode = $this->request->getPost('barcode'); // Get barcode input
    $kode_barang = $this->request->getPost('kode_barang');
    $nama_barang = $this->request->getPost('nama_barang');
    $harga_jual = $this->request->getPost('harga_jual');
    $harga_beli = $this->request->getPost('harga_beli');
    $stok = $this->request->getPost('stok');

    // Handle the uploaded file (foto)
    $foto = $this->request->getFile('foto');
    $fotoName = ''; // Initialize variable for photo file name

    // Check if a file was uploaded
    if ($foto->isValid() && !$foto->hasMoved()) {
        // Move the uploaded photo to the public/foto folder
        $fotoName = 'foto_' . $barcode . '.' . $foto->getExtension(); // Use barcode as part of filename
        $foto->move(ROOTPATH . 'public/foto', $fotoName); // Store in public/foto folder
    }

    // Generate the barcode image
    $generator = new BarcodeGeneratorPNG();
    $barcodeImageName = 'barcode_' . $barcode . '.png'; // Barcode file name
    $barcodePath = ROOTPATH . 'public/barcode/' . $barcodeImageName;

    // Generate the barcode PNG and save it
    file_put_contents($barcodePath, $generator->getBarcode($barcode, $generator::TYPE_CODE_128)); // Generate barcode with CODE128 format

    // Menyusun data yang akan dimasukkan ke dalam database
    $data = [
        'barcode' => $barcode, // Store the barcode directly
        'kode_barang' => $kode_barang,
        'nama_barang' => $nama_barang,
        'harga_jual' => $harga_jual,
        'harga_beli' => $harga_beli,
        'stok' => $stok,
        'foto' => $fotoName, // Store the name of the uploaded photo
        'create_at' => date('Y-m-d H:i:s')
    ];

    // Menambahkan data barang ke dalam tabel 'barang'
    $model->tambah('barang', $data);

    // Mengalihkan ke halaman daftar barang setelah berhasil
    return redirect()->to('home/Gudang');
}

public function EditBarang($id){
    if(session()->get('level')  > 0){
    $model = new M_z();
    $where5 = array('barang.deleted' => Null);
    $where6 = array('id_barang' => $id);
    $data['barang'] = $model->tampilwhere2Row('barang',$where5, $where6);
    $where5 = array('id_setting' => 1);
    $data['setting'] = $model->getwhere('setting',$where5);
    echo view('header', $data);
    echo view('menu', $data);
    echo view('editbarang', $data);
    echo view('footer');
}else{
    return redirect()->to('home/login');
}
}

public function aksi_edit_Barang() {
    $model = new M_z();

    // Get data from the form
    $id_barang = $this->request->getPost('id'); // Item ID
    $nama_barang = $this->request->getPost('nama_barang');
    $kode_barang = $this->request->getPost('kode_barang');
    $harga = $this->request->getPost('harga');
    $harga_beli = $this->request->getPost('harga_beli');
    $stok = $this->request->getPost('stok');
    $barcode = $this->request->getPost('barcode'); // Barcode input from form
    $foto = $this->request->getFile('foto'); // Get the uploaded file



    // Generate barcode image (PNG format)
    $generator = new BarcodeGeneratorPNG();
    $barcodeImageName = 'barcode_' . $barcode . '.png'; // Barcode file name
    $barcodePath = ROOTPATH . 'public/barcode/' . $barcodeImageName;

    // Generate the barcode PNG and save it
    file_put_contents($barcodePath, $generator->getBarcode($barcode, $generator::TYPE_CODE_128)); // Generate barcode with CODE128 format

    // Handle photo upload (if any)
    $fotoName = $this->request->getPost('existing_foto'); // Get existing photo if not updated
    if ($foto->isValid() && !$foto->hasMoved()) {
        // Move the uploaded file to the desired folder
        $fotoName = $foto->getRandomName(); // Generate a random name for the uploaded file
        $foto->move(ROOTPATH . 'public/foto/', $fotoName); // Move file to public/foto/ directory
    }

    // Prepare data to update the database
    $data = [
        'nama_barang' => $nama_barang,
        'kode_barang' => $kode_barang,
        'harga_jual' => $harga,
        'harga_beli' => $harga_beli,
        'stok' => $stok,
        'barcode' => $barcode,  // Insert barcode
        'foto' => $fotoName, // Save the photo name (new or existing) in the database
    ];

    // Where clause to find the item by ID
    $where = ['id_barang' => $id_barang];

    // Update the barang data in the database
    $model->edit('barang', $data, $where);

    // Redirect to the item list page after successful update
    return redirect()->to('home/Gudang');
}

public function SDBarang($id){
    $model = new M_z();
    $data = [
        'deleted' => date('Y-m-d H:i:s')
    ];
    $where = array('id_barang' => $id);
    $model->edit('barang', $data, $where);
    return redirect()->to('home/Gudang');
}





public function BarangMasuk(){
    if(session()->get('level')  > 0){
    $model = new M_z();
    $where5 = array('barangmasuk.deleted' => Null);
    $data['barangmasuk'] = $model->join1where1('barangmasuk','barang','barangmasuk.id_barang = barang.id_barang',$where5);
    $where5 = array('id_setting' => 1);
    $data['setting'] = $model->getwhere('setting',$where5);
    echo view('header', $data);
    echo view('menu', $data);
    echo view('barangmasuk', $data);
    echo view('footer');
}else{
    return redirect()->to('home/login');
}
}

public function TambahBarangMasuk(){
    if(session()->get('level')  > 0){
    $model = new M_z();
    $where5 = array('barang.deleted' => Null);
    $data['barang'] = $model->tampilwhere('barang',$where5);
    $where5 = array('id_setting' => 1);
    $data['setting'] = $model->getwhere('setting',$where5);
    echo view('header', $data);
    echo view('menu', $data);
    echo view('tambahbarangmasuk', $data);
    echo view('footer');
}else{
    return redirect()->to('home/login');
}
}

public function aksi_tambah_BarangMasuk() {
    $model = new M_z();

    // Ambil data dari form
    $id_barang = $this->request->getPost('id_barang'); // Get selected barang ID
    $quantity = $this->request->getPost('quantity');   // Get quantity
    $tanggal = $this->request->getPost('tanggal');     // Get date of entry

    // Menyusun data yang akan dimasukkan ke dalam database
    $data = [
        'id_barang' => $id_barang, // Store the selected id_barang
        'quantity' => $quantity,   // Store the quantity
        'tanggal' => $tanggal,     // Store the tanggal
        'create_at' => date('Y-m-d H:i:s')
    ];

    // Menambahkan data barang masuk ke dalam tabel 'barangmasuk'
    $model->tambah('barangmasuk', $data);

    // Mengalihkan ke halaman daftar barang masuk setelah berhasil
    return redirect()->to('home/BarangMasuk'); // Redirect to barangmasuk page
}


public function EditBarangMasuk($id){
    if(session()->get('level')  > 0){
    $model = new M_z();
    $where5 = array('barangmasuk.deleted' => Null);
    $where6 = array('barang.deleted' => Null);
    $data['barang'] = $model->tampilwhere('barang',$where6);
    $where5 = array('barangmasuk.deleted' => Null);
    $data['barangmasuk'] = $model->join1where1Row('barangmasuk','barang','barangmasuk.id_barang = barang.id_barang',$where5);
    $where5 = array('id_setting' => 1);
    $data['setting'] = $model->getwhere('setting',$where5);
    echo view('header', $data);
    echo view('menu', $data);
    echo view('editbarangmasuk', $data);
    echo view('footer');
}else{
    return redirect()->to('home/login');
}
}

public function aksi_edit_BarangMasuk() {
    $model = new M_z();

    // Get data from the form
    $id_bmasuk = $this->request->getPost('id'); // Barang Masuk ID
    $id_barang = $this->request->getPost('id_barang'); // Selected Barang ID
    $quantity = $this->request->getPost('quantity'); // Quantity input
    $tanggal = $this->request->getPost('tanggal'); // Date input



    // Prepare data to update the database
    $data = [
        'id_barang' => $id_barang,   // Update Barang ID
        'quantity' => $quantity,     // Update quantity
        'tanggal' => $tanggal,       // Update the date
    ];

    // Where clause to find the item by ID
    $where = ['id_bmasuk' => $id_bmasuk];

    // Update the barangmasuk data in the database
    $model->edit('barangmasuk', $data, $where);

    // Redirect to the Barang Masuk page after successful update
    return redirect()->to('home/BarangMasuk');
}


public function SDBarangMasuk($id){
    $model = new M_z();
    $data = [
        'deleted' => date('Y-m-d H:i:s')
    ];
    $where = array('id_bmasuk' => $id);
    $model->edit('barangmasuk', $data, $where);
    return redirect()->to('home/BarangMasuk');
}








public function BarangKeluar(){
    if(session()->get('level')  > 0){
    $model = new M_z();
    $where5 = array('barangkeluar.deleted' => Null);
    $data['barangkeluar'] = $model->join1where1('barangkeluar','barang','barangkeluar.id_barang = barang.id_barang',$where5);
    $where5 = array('id_setting' => 1);
    $data['setting'] = $model->getwhere('setting',$where5);
    echo view('header', $data);
    echo view('menu', $data);
    echo view('barangkeluar', $data);
    echo view('footer');
}else{
    return redirect()->to('home/login');
}
}

public function TambahBarangKeluar(){
    if(session()->get('level')  > 0){
    $model = new M_z();
    $where5 = array('barang.deleted' => Null);
    $data['barang'] = $model->tampilwhere('barang',$where5);
    $where5 = array('id_setting' => 1);
    $data['setting'] = $model->getwhere('setting',$where5);
    echo view('header', $data);
    echo view('menu', $data);
    echo view('tambahbarangkeluar', $data);
    echo view('footer');
}else{
    return redirect()->to('home/login');
}
}

public function aksi_tambah_BarangKeluar() {
    $model = new M_z();

    // Ambil data dari form
    $id_barang = $this->request->getPost('id_barang');    // Get selected barang ID
    $jumlah = $this->request->getPost('jumlah');          // Get jumlah (quantity) from form
    $tanggal = $this->request->getPost('tanggal');        // Get tanggal from form

    // Menyusun data yang akan dimasukkan ke dalam database
    $data = [
        'id_barang' => $id_barang,        // Store the selected id_barang
        'jumlah' => $jumlah,              // Store the jumlah (quantity)
        'tanggal' => $tanggal,            // Store the tanggal
        'create_at' => date('Y-m-d H:i:s') // Store the current timestamp
    ];

    // Menambahkan data barang keluar ke dalam tabel 'barangkeluar'
    $model->tambah('barangkeluar', $data);

    // Mengalihkan ke halaman daftar barang keluar setelah berhasil
    return redirect()->to('home/BarangKeluar'); // Redirect to barangkeluar page
}



public function EditBarangKeluar($id){
    if(session()->get('level')  > 0){
    $model = new M_z();
    $where6 = array('barang.deleted' => Null);
    $data['barang'] = $model->tampilwhere('barang',$where6);
    $where5 = array('barangkeluar.deleted' => Null);
    $data['barangkeluar'] = $model->join1where1Row('barangkeluar','barang','barangkeluar.id_barang = barang.id_barang',$where5);
    $where5 = array('id_setting' => 1);
    $data['setting'] = $model->getwhere('setting',$where5);
    echo view('header', $data);
    echo view('menu', $data);
    echo view('editbarangkeluar', $data);
    echo view('footer');
}else{
    return redirect()->to('home/login');
}
}

public function aksi_edit_BarangKeluar() {
    $model = new M_z();

    // Get data from the form
    $id_bkeluar = $this->request->getPost('id'); // Selected Barang ID
    $id_barang = $this->request->getPost('id_barang'); // Selected Barang ID
    $jumlah = $this->request->getPost('jumlah'); // Quantity input
    $tanggal = $this->request->getPost('tanggal'); // Date input

    // Prepare data to update the database
    $data = [
        'id_barang' => $id_barang,   // Update Barang ID
        'jumlah' => $jumlah,         // Update quantity
        'tanggal' => $tanggal,       // Update the date
    ];

    // Where clause to find the item by ID (barangkeluar ID)
    $where = ['id_bkeluar' => $id_bkeluar];

    // Update the barangkeluar data in the database
    $model->edit('barangkeluar', $data, $where);

    // Redirect to the Barang Keluar page after successful update
    return redirect()->to('home/BarangKeluar');
}



public function SDBarangKeluar($id){
    $model = new M_z();
    $data = [
        'deleted' => date('Y-m-d H:i:s')
    ];
    $where = array('id_bkeluar' => $id);
    $model->edit('barangkeluar', $data, $where);
    return redirect()->to('home/BarangKeluar');
}



//RECYLE RECYLE RECYLERECYLE RECYLE RECYLERECYLERECYLERECYLERECYLERECYLERECYLERECYLERECYLERECYLERECYLERECYLE//



public function RUser(){
    if(session()->get('level')  > 0){
    $model = new M_z();
    $where5 = 'user.deleted is not null';
    $data['user'] = $model->join1where1('user','level','user.level = level.id_level', $where5);
    $where5 = array('id_setting' => 1);
    $data['setting'] = $model->getwhere('setting',$where5);
    echo view('header', $data);
    echo view('menu', $data);
    echo view('ruser', $data);
    echo view('footer');
}else{
    return redirect()->to('home/login');
}
}

public function RDuser($id){
    $model = new M_z();
    $data = [
        'deleted' => NULL
    ];
    $where = array('id_user' => $id);
    $model->edit('user', $data, $where);
    return redirect()->to('home/RUser');
}

public function RBarangKeluar(){
    if(session()->get('level')  > 0){
    $model = new M_z();
    $where5 = 'barangkeluar.deleted is not Null';
    $data['barangkeluar'] = $model->join1where1('barangkeluar','barang','barangkeluar.id_barang = barang.id_barang',$where5);
    $where5 = array('id_setting' => 1);
    $data['setting'] = $model->getwhere('setting',$where5);
    echo view('header', $data);
    echo view('menu', $data);
    echo view('rbarangkeluar', $data);
    echo view('footer');
}else{
    return redirect()->to('home/login');
}
}

public function RDBarangKeluar($id){
    $model = new M_z();
    $data = [
        'deleted' => NULL
    ];
    $where = array('id_bkeluar' => $id);
    $model->edit('barangkeluar', $data, $where);
    return redirect()->to('home/RBarangKeluar');
}

public function RBarangMasuk(){
    if(session()->get('level')  > 0){
    $model = new M_z();
    $where5 = 'barangmasuk.deleted is not null';
    $data['barangmasuk'] = $model->join1where1('barangmasuk','barang','barangmasuk.id_barang = barang.id_barang',$where5);
    $where5 = array('id_setting' => 1);
    $data['setting'] = $model->getwhere('setting',$where5);
    echo view('header', $data);
    echo view('menu', $data);
    echo view('rbarangmasuk', $data);
    echo view('footer');
}else{
    return redirect()->to('home/login');
}
}

public function RDBarangMasuk($id){
    $model = new M_z();
    $data = [
        'deleted' => NULL
    ];
    $where = array('id_bmasuk' => $id);
    $model->edit('barangmasuk', $data, $where);
    return redirect()->to('home/RBarangmasuk');
}


public function RGudang(){
    if(session()->get('level')  > 0){
    $model = new M_z();
    $where5 = 'barang.deleted is not null';
    $data['barang'] = $model->tampilwhere('barang',$where5);
    $where5 = array('id_setting' => 1);
    $data['setting'] = $model->getwhere('setting',$where5);
    echo view('header', $data);
    echo view('menu', $data);
    echo view('rgudang', $data);
    echo view('footer');
}else{
    return redirect()->to('home/login');
}
}

public function RDBarang($id){
    $model = new M_z();
    $data = [
        'deleted' => Null
    ];
    $where = array('id_barang' => $id);
    $model->edit('barang', $data, $where);
    return redirect()->to('home/RGudang');
}


public function Laporan(){
    if(session()->get('level')  > 0){
    $model = new M_z();
    $where4 = array('barangkeluar.deleted' => Null);
    $data['barangkeluar'] = $model->join1where1('barangkeluar','barang','barangkeluar.id_barang = barang.id_barang',$where4);
    $where6 = array('barangmasuk.deleted' => Null);
    $data['barangmasuk'] = $model->join1where1('barangmasuk','barang','barangmasuk.id_barang = barang.id_barang',$where6);
    $where7 = array('barang.deleted' => Null);
    $data['barang'] = $model->tampilwhere('barang',$where7);
    $where5 = array('id_setting' => 1);
    $data['setting'] = $model->getwhere('setting',$where5);
    echo view('header', $data);
    echo view('menu', $data);
    echo view('laporan', $data);
    echo view('footer');
}else{
    return redirect()->to('home/login');
}
}

public function filterTanggal()
{
    if (session()->get('level')  > 0) {
        // Ambil parameter tanggal dari query string
        $startDate = $this->request->getGet('start_date') ?: '0000-00-00';
        $endDate = $this->request->getGet('end_date') ?: '9999-12-31';

        $model = new M_z(); // Model utama
        $whereUser = ['id_user' => session()->get('id')];
        $data['dua'] = $model->getwhere('user', $whereUser);
        $data['setting'] = $model->getwhere('setting', ['id_setting' => 1]);

        // Filter barang
        $whereBarang = [
            'barang.deleted' => NULL,
            'barang.create_at >=' => $startDate,
            'barang.create_at <=' => $endDate
        ];
        $data['barang'] = $model->tampilwhere('barang', $whereBarang);

        // Filter barang masuk
        $whereBarangMasuk = [
            'barangmasuk.deleted' => NULL,
            'barangmasuk.tanggal >=' => $startDate,
            'barangmasuk.tanggal <=' => $endDate
        ];
        $data['barangmasuk'] = $model->join1where1('barangmasuk', 'barang', 'barangmasuk.id_barang = barang.id_barang', $whereBarangMasuk);

        // Filter barang keluar
        $whereBarangKeluar = [
            'barangkeluar.deleted' => NULL,
            'barangkeluar.tanggal >=' => $startDate,
            'barangkeluar.tanggal <=' => $endDate
        ];
        $data['barangkeluar'] = $model->join1where1('barangkeluar', 'barang', 'barangkeluar.id_barang = barang.id_barang', $whereBarangKeluar);

        // Load tampilan
        echo view('header', $data);
        echo view('menu', $data);
        echo view('laporan', $data);
        echo view('footer');

    } else {
        return redirect()->to('home/login');
    }
}

public function generate_pdf($type)
{
    $start_date = $this->request->getGet('start_date') ?? date('Y-m-d');
    $end_date = $this->request->getGet('end_date') ?? date('Y-m-d');

    $model = new M_z();
    $title = "";
    $data = [];

    switch ($type) {
        case 'gudang':
            $data['records'] = $model->getGudang($start_date, $end_date);
            $title = "Laporan Gudang";
            break;
        case 'barangmasuk':
            $data['records'] = $model->getBarangMasuk($start_date, $end_date);
            $title = "Laporan Barang Masuk";
            break;
        case 'barangkeluar':
            $data['records'] = $model->getBarangKeluar($start_date, $end_date);
            $title = "Laporan Barang Keluar";
            break;
        default:
            return redirect()->to(base_url('home'));
    }

    $html = view('pdf_template', ['data' => $data, 'title' => $title]);

    $options = new Options();
    $options->set('defaultFont', 'Courier');
    $dompdf = new Dompdf($options);
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $dompdf->stream($title . ".pdf", ["Attachment" => false]);
}


}
