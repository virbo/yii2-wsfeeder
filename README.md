<p align="center">
    <a href="https://dutainformasi.net" target="_blank">
        <img src="https://s3-id-jkt-1.kilatstorage.id/cdn-dutainformasi/assets/img/logo.png" height="100px">
    </a>
    <h1 align="center">Library Untuk Webservice PDDIKTI Feeder</h1>
    <br>
</p>


Installasi
------------

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
-----------------

Tambahkan konfigurasi berikut ke file `config.php` atau `web.php`

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

Untuk menggunakannya cukup mudah, contoh seperti ini
```php
public function actionDictionary()
{
    $data = [
        'act' => 'GetDictionary',
        'fungsi' => 'InsertNilaiTransferPendidikanMahasiswa'
    ];
    return Yii::$app->feeder->actFeeder($data);
}

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