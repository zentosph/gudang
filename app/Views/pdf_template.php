<!DOCTYPE html>
<html>
<head>
    <title><?= $title ?></title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h2><?= $title ?></h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Barang</th>
                <th>Jumlah</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; foreach ($data['records'] as $record): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= $record->nama_barang ?></td>
                <td><?= $record->jumlah ?? $record->quantity ?? $record->stok ?></td>
                <td><?= $record->tanggal ?? $record->create_at ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
