<p align="center">
    <a href="https://dutainformasi.net" target="_blank">
        <img src="https://s3-id-jkt-1.kilatstorage.id/cdn-dutainformasi/assets/img/logo.png" height="100px">
    </a>
    <h1 align="center">Yii2 library untuk Webservice PDDIKTI Feeder</h1>
    <br>
</p>

Installasi
----------
Pastikan Anda telah menginstall [Composer](http://getcomposer.org/). Jika belum, silahkan install terlebih dahulu dengan mengikuti instruksi yang ada di [getcomposer.org](http://getcomposer.org/doc/00-intro.md#installation-nix)

Jalankan perintah

```
php composer.phar require virbo/yii2-wsfeeder "~1.0"
```

atau tambahkan baris ini

```
"virbo/yii2-wsfeeder": "~1.0"
```

ke file `composer.json` lalu jalankan perintah

```
php composer.phar update
```


Cara menggunakan
----------------
Tambahkan konfigurasi berikut ke file `main.php` atau `main-local.php` atau `web.php`

```php
'components' => [
    ...
    'feeder' => [
        'class' => \virbo\wsfeeder\Feeder::class,
        'endpoint' => 'http://url_feeder:8082/ws',
        'username' => 'username_feeder',
        'password' => 'password_feeder'
    ],
    ...
],
```
Secara default, database yang terkoneksi adalah database `live`. Jika ingin menggunakan database sandbox, tambahkan property `mode = 1` dalam konfigurasi diatas. Contohnya seperti ini

```php
'components' => [
    ...
    'feeder' => [
        'class' => \virbo\wsfeeder\Feeder::class,
        'endpoint' => 'http://url_feeder:8082/ws',
        'username' => 'username_feeder',
        'password' => 'password_feeder',
        'mode' => 1     // 0 = Live, 1 = Sandbox
    ],
    ...
],
``` 

Untuk menggunakannya cukup mudah. Berikut beberapa contoh

### GetDictionary

```php
/*
 * mengambil dictionary dari setiap method. dalam contoh ini akan 
 * ditampilkan dictionary dari method InsertNilaiTransferPendidikanMahasiswa
 * */
public function actionDictionary()
{
    $data = [
        'act' => 'GetDictionary',
        'fungsi' => 'InsertNilaiTransferPendidikanMahasiswa'
    ];
    return Yii::$app->feeder->actFeeder($data);
}
```

### View Data

```php
/*
 * menampilkan data mahasiswa sebanyak 10 data.
 * */
public function actionListData()
{
    $data = [
        'act' => 'GetListMahasiswa',
        'filter' => null,
        'order' => null,
        'limit' => 10,
        'offset' => 0
    ];
    return Yii::$app->feeder->actFeeder($data);
}
```

### Filter data

```php
/*
 * Filter data mahasiswa berdasarkan id mahasiswa (untuk list field2 yang
 * ada di method GetListMahasiswa, bisa diliat menggunakan method GetDictionary.
 * */
public function actionFilterData()
{
    $data = [
        'act' => 'GetListMahasiswa',
        'filter' => "id_mahasiswa = '0d06b023-0ff6-4a08-a8d2-a9e31b39a095'",
        'order' => null,
        'limit' => 10,
        'offset' => 0
    ];
    return Yii::$app->feeder->actFeeder($data);
}
```

### Insert Data

```php
/*
 * Insert data. dalam contoh ini insert biodata mahasiswa menggunakan
 * method InsertBiodataMahasiswa
 * */
public function actionInsertData()
{
    $data = [
        'act' => 'InsertBiodataMahasiswa',
        'record' => [
            'nama_mahasiswa' => 'Pangeran Khairan Asshabir Y Ayuba',
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Banggai',
            'tanggal_lahir' => '2009-09-02',
            'id_agama' => 1,
            'nik' => '1234567890098765',
            'nisn' => null,
            'npwp' => null,
            'kewarganegaraan' => 'ID',
            'jalan' => 'Jl. Burung Mas Kompleks Gorontalo',
            'dusun' => null,
            'rt' => 5,
            'rw' => 0,
            'kelurahan' => 'Kelurahan Tano Bonunungan',
            'kode_pos' => null,
            'id_wilayah' => 180102,
            'id_jenis_tinggal' => 1,
            'id_alat_transportasi' => null,
            'telepon' => null,
            'handphone' => null,
            'email' => null,
            'penerima_kps' => 0,
            'nomor_kps' => null,
            'nik_ayah' => '9087654321234567',
            'nama_ayah' => 'Yusuf Ayuba',
            'tanggal_lahir_ayah' => '1980-08-23',
            'id_pendidikan_ayah' => 35,
            'id_pekerjaan_ayah' => 6,
            'id_penghasilan_ayah' => 13,
            'nik_ibu' => '8907654321234567',
            'nama_ibu_kandung' => 'Isfatian',
            'tanggal_lahir_ibu' => '1982-11-23',
            'id_pendidikan_ibu' => 20,
            'id_pekerjaan_ibu' => 9,
            'id_penghasilan_ibu' => 14,
            'nama_wali' => null,
            'tanggal_lahir_wali' => null,
            'id_pendidikan_wali' => null,
            'id_pekerjaan_wali' => null,
            'id_penghasilan_wali' => null,
            'id_kebutuhan_khusus_mahasiswa' => 0,
            'id_kebutuhan_khusus_ayah' => 0,
            'id_kebutuhan_khusus_ibu' => 0
        ]
    ];
    
    return Yii::$app->feeder->actFeeder($data);
}
```

### Update Data

```php
/*
 * Update data berdasarkan id. Dalam contoh ini update data mahasiswa berdasarkan ID mahasiswa
 * */
public function actionUpdateData()
{
    $data = [
        'act' => 'UpdateBiodataMahasiswa',
        'key' => [
            'id_mahasiswa' => '0d06b023-0ff6-4a08-a8d2-a9e31b39a095'
        ],
        'record' => [
            'nama_mahasiswa' => 'Pangeran Khairan Asshabir Yusuf Ayuba',
        ]
    ];
    
    return Yii::$app->feeder->actFeeder($data);
}
```

### Delete data

```php
/*
 * Delete data. dalam contoh ini menghapus data mahasiswa berdasarkan ID mahasiswa
 * */
public function actionDeleteData()
{
    $data = [
        'act' => 'DeleteBiodataMahasiswa',
        'key' => [
            'id_mahasiswa' => '0d06b023-0ff6-4a08-a8d2-a9e31b39a095'
        ],
    ];
    
    return Yii::$app->feeder->actFeeder($data);
}

```

Dengan menggunakan function `actFeeder` kita dapat menampilkan `dictionary` dari method-method yang ada, dapat juga digunakan untuk menampilkan data-data dan melakukan insert/update data sampai menghapus data tersebut.

Daftar lengkap method-method dapat dilihat melalui halaman [http://alamat_feeder/ws/live2.php](http://alamat_feeder/ws/live2.php) atau [http://alamat_feeder/ws/sandbox2.php](http://alamat_feeder/ws/sandbox2.php) 

Diskusi
--------
Jika ada yang ingin di diskusikan, jangan sungkan untuk menghubungi saya baik lewat email maupun social media saya.

- [Email](yusuf@dutainformasi.net)
- [Facebook](https://facebook.com/yusuf.web)


!!! Happy coding :)