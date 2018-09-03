# Codeigniter Database Backup

## Gereksinimler
1.  PHP 5.2+
2.  CodeIgniter 2 veya üstü

## Kurulum
#### Adım 1
```php
$tables = $this->db->list_tables();  
  
// Tabloları ve viewleri listeleme  
foreach ($tables as $table){

    // DB view olan dosyları bulma
    $konum = strpos($table, '_view');
    if($konum === false) $data_table[]= $table;
    else $data_view[]= $table;
}
```
> **Not:** İki farklı dizini tek bir değişkene atamak için array_merge($array1, $array2) kullanıyoruz. Bunun nedeni database'i import ederken önce tabloları eklemesi ve eklenen tablolara göre oluşturduğumuz view tablolarını eklemesi sağlamak. Aksi taktirde import işlemi sırasında hata alabilirsiniz.
#### Adım 2
---
```php
// Yedekleme işlemini başlatıyoruz  
$this->load->dbutil();  
$prefs = array(  
    'tables' => array_merge($data_table, $data_view),    // Yedeklenecek tablo dizisi  
    'ignore' => array(),                                 // Yedeklemeden çıkarılacak tabloların listesi  
    'format' => 'zip',                                   // Format türleri gzip, zip, txt  
    'filename' => 'backup.sql',                          // Dosya adı - (Dosya adına sadece zip formatında yedekleme yapılırsa ihtiyaç vardır)  
    'add_drop' => TRUE,                                  // DROP TABLE ifadelerinin yedekleme dosyasına eklenip eklenmeyeceği  
    'add_insert' => TRUE,                                // INSERT verilerini yedekleme dosyasına eklemek ister  
    'newline' => "\n" 					 // Yedek dosyada kullanılan yeni satır karakteri  
);  
$backup = $this->dbutil->backup($prefs);  
$db_name = 'backup-'.date("d-m-Y-H-i-s").'.zip';  
$save = 'backup/'.$db_name;  
write_file($save, $backup);
```
---
> Eğer dosyanın yedeklemeden sonra otomatik inmesini istiyorsak
```php
force_download($db_name, $backup);
```
