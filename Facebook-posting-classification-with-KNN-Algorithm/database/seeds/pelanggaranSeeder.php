<?php

use Illuminate\Database\Seeder;

class pelanggaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pelanggaran= array(
        	['id'=>1, 'pasal'=>'27 ayat 1','hukum' =>'6','denda'=>'Rp1.000.000.000,00(satu miliar rupiah)',
        		'keterangan'=>'memiliki muatan yang melanggar kesusilaan.'],
        	['id'=>2, 'pasal'=>'27 ayat 2','hukum' =>'6','denda'=>'Rp1.000.000.000,00(satu miliar rupiah)',
        		'keterangan'=>'memiliki muatan perjudian'],
        	 ['id'=>3, 'pasal'=>'27 ayat 3','hukum' =>'4','denda'=>'Rp750.000.000,00(Tujuh ratus lima puluh juta rupiah)', 'keterangan'=>'memiliki muatan penghinaan dan/atau pencemaran nama baik.'],
        	 ['id'=>4, 'pasal'=>'27 ayat 4','hukum' =>'6','denda'=>'Rp1.000.000.000,00(satu miliar rupiah)',
        		'keterangan'=>'memiliki muatan pemerasan dan/atau pengancaman.'],
        	 ['id'=>5, 'pasal'=>'28 ayat 1','hukum' =>'6','denda'=>'Rp1.000.000.000,00(satu miliar rupiah)',
        		'keterangan'=>'menyebarkan berita bohong dan menyesatkan yang mengakibatkan kerugian konsumen dalam Transaksi Elektronik.'],
        	 ['id'=>6, 'pasal'=>'28 ayat 2','hukum' =>'6','denda'=>'Rp1.000.000.000,00(satu miliar rupiah)',
        		'keterangan'=>' menyebarkan informasi yang ditujukan untuk menimbulkan rasa kebencian atau permusuhan individu dan/atau kelompok masyarakat tertentu berdasarkan atas suku, agama, ras, dan antargolongan (SARA).'],
        	['id'=>7, 'pasal'=>'29','hukum' =>'4','denda'=>'Rp750.000.000,00(Tujuh ratus lima puluh juta rupiah)', 'keterangan'=>'berisi ancaman kekerasan atau menakut-nakuti yang ditujukan secara pribadi.'],
        	  	 
        );
        DB::table('pelanggaran')->insert($pelanggaran);
    }
}
