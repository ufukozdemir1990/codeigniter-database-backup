<?php
    /**
     * Created by PhpStorm.
     * User: ufukozdemir
     * Date: 7.08.2018
     * Time: 16:43
     */

    defined('BASEPATH') OR exit('No direct script access allowed');

    class Welcome extends CI_Controller {

        public function __construct() {
            parent::__construct();

            // Yüklü veritabanlarını indirmek için memory limitini yükseltiyoruz
            ini_set("memory_limit","512M");

            // Library kütüphanelerimizi yüklüyoruz
            $this->load->library(array('session'));

            // Database kütüphanesini yüklüyoruz
            $this->load->database();

            // Helperlarımızı yüklüyoruz
            $this->load->helper(array('url', 'file', 'download'));

            // Veritabanındaki tabloları depolamak için kullanıyoruz
            $data_table = array();
            $data_view = array();
        }

        public function index() {
            $data['tables'] = $this->db->list_tables();
            $this->load->view('welcome_message', $data);
        }

        public function db_backup() {

            $tables = $this->db->list_tables();

            // Tabloları ve viewleri listeleme
            foreach ($tables as $table){
                $konum = strpos($table, '_view');
                if($konum === false) $data_table[]= $table;
                else $data_view[]= $table;
            }

            /*
             * İki farklı arrayi tek bir değişkene atamak için array_merge($array1, $array2) kullanıyoruz
             * Bunun nedeni database import ederken önce tabloları ve eklenen tablolara görede viewleri eklemesi için
             * Aksi taktirde import işlemi başarılı olmaz
             */

            // Yedekleme işlemini başlatıyoruz
            $this->load->dbutil();
            $prefs = array(
                'tables'        => array_merge($data_table, $data_view),    // Yedeklenecek tablo dizisi
                'ignore'        => array(),                                 // Yedeklemeden çıkarılacak tabloların listesi
                'format'        => 'zip',                                   // Format türleri gzip, zip, txt
                'filename'      => 'backup.sql',                            // Dosya adı - (Dosya adına sadece zip formatında yedekleme yapılırsa ihtiyaç vardır)
                'add_drop'      => TRUE,                                    // DROP TABLE ifadelerinin yedekleme dosyasına eklenip eklenmeyeceği
                'add_insert'    => TRUE,                                    // INSERT verilerini yedekleme dosyasına eklemek ister
                'newline'       => "\n"                                     // Yedek dosyada kullanılan yeni satır karakteri
            );
            $backup = $this->dbutil->backup($prefs);
            $db_name = 'backup-'.date("d-m-Y-H-i-s").'.zip';
            $save = 'backup/'.$db_name;
            write_file($save, $backup);

            // Yedekleme işlemi bittikten sonra ekrana mesaj yazdırıyoruz
            $this->session->set_flashdata('msg', 'Database yedeği başarılı bir şekilde alındı');
            $this->session->set_flashdata('link', $db_name);

            /*
             * Eğer Yedekleme işleminden hemen sonra backup dosyasını indirmek isterseniz force_download(); metodunu kullanabilirsiniz
             * force_download($db_name, $backup);
             */

            redirect();

        }
    }
