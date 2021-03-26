<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Posting;
use App\Pelanggaran;
use Illuminate\Http\Request;

class PelanggaranController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('welcome');
    }

    function StemmerStopword($word) // ini hanya function untuk stemming dan stopword
    {
        $stemmerFactory = new \Sastrawi\Stemmer\StemmerFactory();
        $stemmer  = $stemmerFactory->createStemmer();
        $stopwordremove = new \Sastrawi\StopWordRemover\StopWordRemoverFactory(); 
        $stopword = $stopwordremove->createStopWordRemover();

        $word =  preg_replace('/[0-9]+/', '', $word);
        $word = str_replace('-',' ',$word);
        $word = str_replace('\'',' ',$word);
        //$word = str_replace('id',' ',$word);

        // stem
        $sentence =$word;// $_POST['katadasar'];
        $output   = $stemmer->stem($sentence);
        $hasil = $stopword->remove($output);
        $hasil =  preg_replace(" '/[a-z]+/' ", '', $hasil);

        return $hasil;
    }

    /**
     * Store a newly created resource in storage.
     *
     *
     * @return \Illuminate\Http\Response
     */
    public function cc()
    {
         $ar = array(
            'yang', 'untuk', 'pada', 'ke', 'para', 'namun', 'menurut', 'antara', 'dia', 'dua',
            'ia', 'seperti', 'jika', 'jika', 'sehingga', 'kembali', 'dan', 'tidak', 'ini', 'karena',
            'kepada', 'oleh', 'saat', 'harus', 'sementara', 'setelah', 'belum', 'kami', 'sekitar',
            'bagi', 'serta', 'di', 'dari', 'telah', 'sebagai', 'masih', 'hal', 'ketika', 'adalah',
            'itu', 'dalam', 'bisa', 'bahwa', 'atau', 'hanya', 'kita', 'dengan', 'akan', 'juga',
            'ada', 'mereka', 'sudah', 'saya', 'terhadap', 'secara', 'agar', 'lain', 'anda',
            'begitu', 'mengapa', 'kenapa', 'yaitu', 'yakni', 'daripada', 'itulah', 'lagi', 'maka',
            'tentang', 'demi', 'dimana', 'kemana', 'pula', 'sambil', 'sebelum', 'sesudah', 'supaya',
            'guna', 'kah', 'pun', 'sampai', 'sedangkan', 'selagi', 'sementara', 'tetapi', 'apakah',
            'kecuali', 'sebab', 'selain', 'seolah', 'seraya', 'seterusnya', 'tanpa', 'agak', 'boleh',
            'dapat', 'dsb', 'dst', 'dll', 'dahulu', 'dulunya', 'anu', 'demikian', 'tapi', 'ingin',
            'juga', 'nggak', 'mari', 'nanti', 'melainkan', 'oh', 'ok', 'seharusnya', 'sebetulnya',
            'setiap', 'setidaknya', 'sesuatu', 'pasti', 'saja', 'toh', 'ya', 'walau', 'tolong',
            'tentu', 'amat', 'apalagi', 'bagaimanapun','aku','aja','gua','lu','bukan','tdk','gg','ga','iya','kau', 'ni','n','yg','ko','no','tak','hei','hai','kek','kalo','kalau','ngak','apa','tu','kan','tpi','jd', 'sy','gak','kok','q','lg','lo','si','tp','nya','udh','lgi','mana','yaa','lah','blm','jga','ku','sma','mu','klo','gk','dlm','dgn','aq','udah','nih','sm','jgn','dah','nah','dulu','gx','gue','nih','ayo','perlu','sama', 'elu','jadi','sini','sekali','tiap','sini','cuma','maupun','memang','bagai'
        );
         $ar = implode(',', $ar);
        $stopwordremove = new \Sastrawi\StopWordRemover\StopWordRemoverFactory(); 
        $stopwordremove->save($ar);
    }

    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function pst()
    {
        $idpst = '1';
        // $Posting = Posting::with('Pelanggaran')->whereHas('Pelanggaran',function($q){
        //     $q->where('Pelanggaran.id',6);
        // })->get();
        //
        $Posting = Posting::where('melanggar',0)->get();
        return view('pst', compact('Posting'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
public function stopControl()
{
    $stopwordremove = new \Sastrawi\StopWordRemover\StopWordRemoverFactory(); 
    $stp = $stopwordremove->getStopWords();
    sort($stp);
    $stopword = implode(',', $stp);
    return view('stopword',compact('stopword'));
}

     /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function saveControl(Request $request)
    {
        $stopwordremove = new \Sastrawi\StopWordRemover\StopWordRemoverFactory(); 
        $stopwordremove->save($request->word);
        
        return 'yes';
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function Cek(Request $request)
    {
        $kata = $request->txtinput;  
        
        $res = $this->Calculate($kata, true);
        //return view('input', compact('kata'));
        return $res;
    }

    function Calculate($kata_bersih, $save)
    {
        $hasil = '';
        $knn =49; //berapa knn nya
        $treshold = ceil($knn/5);
        $kata = $this->StemmerStopword($kata_bersih);
        //echo "<h3>".$kata."</h3>";
        //echo "<br>";
        $arkata = explode(' ', $kata); //ini fungsinya seperti split, kemudian menciptakan array sesuai dari query inputan
        $arBobotQuery = array(); // ini adalah array untuk nanti menyimpan bobot TF-RAW dari query inputan
        $arKataQuery= array();
       // echo json_encode($arkata);
        foreach ($arkata as $data) {
            if($data != '' && strlen($data)>2 && $data != 'id')
            {
                if(array_key_exists($data, $arBobotQuery))
                {
                    $arBobotQuery[$data] +=1;
                }
                else
                {
                    $arBobotQuery[$data] =1 ;
                    $arKataQuery[] = $data;
                }
            }
        }
        
        $kata = implode(' ', $arKataQuery);
        
        $arkata = $arKataQuery;
        
        $arDocWeight = array();// ini menyimpan bobot dari TF-IDF dari database
        $wrd = Posting::orderBy('id','desc')->get();
        $path = resource_path('assets/jsonkata.txt');
        $myfile = fopen($path, "r");
        $ka = fread($myfile, filesize($path));
        $ka = json_decode($ka,true);
        // foreach ($arkata as $key => $value) {
        //     $ak = array_key_exists($value, $ka)?$ka[$value]:0;
        //     echo $ak.'<br>';
        // } 
        foreach ($wrd as $key => $row) {        
          // while ($row = mysqli_fetch_assoc($result)) { // masukkan hasil query database ke $ardocweight
                $rowDoc = array('id'=>$row['id'], 'ori'=>$row['posting'],'clean'=>$row['posting_clean'],'melanggar'=>$row['melanggar']); // isi dari arDocWeight itu array lagi yang berisi barisan bobot sesuai jumlah $arkata
                $arpostingg = explode(' ', $rowDoc['clean']);
                $arpostingg = array_count_values($arpostingg);
                foreach ($arkata as $key => $value) {
                    $rowDoc['clean'] = str_replace($value,"<span style='color: red;'>".$value."</span>",$rowDoc['clean']);
                    if(array_key_exists($value, $arpostingg))
                        $rowDoc[$value] = array_key_exists($value, $ka)?$ka[$value]:0* $arpostingg[$value]; //$row[$value];
                    else
                        $rowDoc[$value] = 0;
                }
                $arDocWeight[] = $rowDoc;                       
        } 
         
        //echo "<br><br>";
         //echo json_encode($arDocWeight);

        // Method Cosine
        // $hasil .= "<h2>Hasil Pembobotan menggunakan Metode Cosine</h2>";
        
        foreach ($arDocWeight as $key=>$value) 
        {
            // echo $value['ori'];
            // break;
            
            $pembilang = 0;
            $penyebutq = 0;
            $penyebutt = 0;

            foreach ($arBobotQuery as $data => $bobot) {
                $pembilang += $bobot*$value[$data]; //min([$bobot,$value[$data]]);
               
                $penyebutt += $bobot * $bobot;
                
                $penyebutq += $value[$data] * $value[$data];
              
            }   
            $penyebut = $penyebutq * $penyebutt;
            $penyebut = sqrt($penyebut);
            //echo "$penyebut, $pembilang, $penyebutt, $penyebutq";
            $similarity = 0;
            if($penyebut != 0)
                $similarity = $pembilang/$penyebut;
            $arHasil[] = array('id'=> $value['id'], 'ori'=>$value['ori'],'clean'=>$value['clean'],'bobot'=> $similarity,'pelanggaran'=>$value['melanggar']);
            
        }

        foreach ($arHasil as $key => $bot) {
            $arbobot[$key] = $bot['bobot'];
        }
        array_multisort($arbobot, SORT_DESC, $arHasil);

        // $hasil .= "<br>";
        // $hasil .= "Diambil ".$knn." posting teratas sebagai pembanding";
        // $hasil .= "<br>";
        
        $arKNN = array();
        $p_melanggar=0;
        $p_tdk_melanggar=0;
        $artopPosting = array();

        for ($i=0; $i < $knn; $i++) {
            $getPelanggaran = Posting::find($arHasil[$i]['id']);
            $artopPosting[] = $arHasil[$i]['id'];
            //hitung jumlah yang melanggar
            if($arHasil[$i]['bobot']>0)
            {
                if($arHasil[$i]['pelanggaran'] == '1')
                    $p_melanggar++;
                else
                    $p_tdk_melanggar++;


                $arHasil[$i]['pelanggaran'] .= '=>';
                // echo $getPelanggaran->melanggar.',';
                foreach ($getPelanggaran->Pelanggaran as $row) 
                // while($row = mysqli_fetch_assoc($resultGetPelanggaran))
                {
                    $arHasil[$i]['pelanggaran'] .= $row['id'];
                    if(array_key_exists($row['id'],$arKNN))
                    {
                        //echo "ada";
                        $arKNN[$row['id']]++;
                    }
                    else
                    {
                        //echo "kosong";
                        $arKNN[$row['id']] = 1;
                    }
                } 
            }  
        }
        arsort($arKNN);
     
        // echo json_encode($arKNN);
        // $hasil .= "<ol>";

        foreach ($arKNN as $key => $value) {
            $pel = Pelanggaran::find($key);
            // $sql = 'select * from pelanggaran where id =' .$key;
            // $result = mysqli_query($con,$sql);
            // $hasil = mysqli_fetch_assoc($result);
            // $hasil .= '<li>'.$pel['pasal'].' ('. $pel['keterangan'].') => '. $value.'</li>';
            // $hasil .= "<br>";
        }
        // $hasil .= "</ol>";
        
        
             $tal= '<table border="1px solid">
            <tr>
            <td>Original posting</td>
            <td>Clean Posting</td>      
            <td>Nilai Similiaritas</td>
            <td>Pelanggaran</td></tr>';
                    
            for ($i=0; $i < $knn; $i++)
             {
                $tal.=   "<tr>";
                //$tal.= "<td>" . $b['user'] ."</td>";
                $tal.= "<td>" .$arHasil[$i]['id'].' -> '. $arHasil[$i]['ori'] ."</td>";
                $tal.= "<td>" . $arHasil[$i]['clean'] ."</td>";
                $tal.= "<td>" . $arHasil[$i]['bobot'] ."</td>";
                $tal.= "<td>" . $arHasil[$i]['pelanggaran'] ."</td>";
                $tal.= "</tr>";
             }
             $tal.= "</table>";
               //echo $tal   ; 
        $Posting = new Posting;
        $Posting->posting=$kata_bersih;
        $Posting->posting_clean = $kata;
        $hasil .= "<br>";
        //echo "<h4> Pada Data Sampel posting ini melanggar pasal 29</h4><br>";
        //$hasil .= $p_tdk_melanggar .'>'.$p_melanggar.'<br>';
        $diff = $p_melanggar + $p_tdk_melanggar;
        if($diff < $knn)
        {
            $diff = $knn-$diff;
            $p_tdk_melanggar += $diff;
        }
        if($p_tdk_melanggar >= $p_melanggar)
        {
            $hasil .= "<h3>Selamat!!!</h3>";
            $hasil .= "<h3>Postingan ini tidak melanggar UU ITE dan bebas untuk di posting</h3>";
             
            $Posting->melanggar = 0;
            if($save == true)
                $Posting->save();
        }   
        else
        {
            $hasil .= "<h4>Maaf, postingan ini melanggar UU ITE dan akan terancam terkena pidana UU ITE dengan pasal:</h4>";
            
            $tahun=0;
            $denda='';

            // input posting ke baru
            $Posting->melanggar = 1;
            if($save == true)
                $Posting->save();

            $hasil .= "<ol>";

            $numPasal=0;
            $keyPasal = -1;
            $jumlahp=0;
            // masukkan hukuman dari posting melanggar dan tampilkan juga
            foreach ($arKNN as $key => $value) {
                if($value > $jumlahp)
                {
                    $jumlahp = $value;
                    $keyPasal = $key;
                }
                // $hasil .= '<h4>'.$key.'=>'.$value.'</h4>';
                if($value>= $treshold)
                {
                    $numPasal++;
                    $pel = Pelanggaran::find($key);
                    if($tahun<$pel['hukum'])
                    {
                        $tahun = $pel['hukum'];
                        $denda = $pel['denda'];
                    }
                    
                    
                    if($save == true)
                        $Posting->Pelanggaran()->attach($pel);
                    //echo "<h4>Pada Hasil Output sistem posting ini melanggar pasal ".$pel->pasal."</h4>";
                    //echo "<h4>Validasi pada Posting ini benar!!!";
                    $hasil .=  '<li>'.$pel['pasal'].'('.$pel['keterangan'].')</li>';
                }
            }
            if($numPasal ==0)
            {
                $pel = Pelanggaran::find($keyPasal);
                if($tahun<$pel['hukum'])
                {
                    $tahun = $pel['hukum'];
                    $denda = $pel['denda'];
                }                
                if($save == true)
                    $Posting->Pelanggaran()->attach($pel);
                $hasil .=  '<li>'.$pel['pasal'].'('.$pel['keterangan'].')</li>';
            }
            $hasil .=  "</ol>";

            $hasil .=  "<h4>Jika Anda tetap melakukan posting, anda akan terancam dihukum penjara selama ".$tahun." tahun dan/atau denda maksimal sebanyak ". $denda."</h4>";
            $hasil .=  "<br>";
            // $hst = new History;
            // $hst->posting_id_neighbour =  implode(',', $id_langgar);



        }         
        
        if($save == true)
            $hst = $Posting->History()->create(['posting_id_neighbour'=>implode(',', $artopPosting),]);
        return $hasil;                     
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Pelanggaran  $pelanggaran
     * @return \Illuminate\Http\Response
     */
    public function show(Pelanggaran $pelanggaran)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Pelanggaran  $pelanggaran
     * @return \Illuminate\Http\Response
     */
    public function edit(Pelanggaran $pelanggaran)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Pelanggaran  $pelanggaran
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pelanggaran $pelanggaran)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Pelanggaran  $pelanggaran
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pelanggaran $pelanggaran)
    {
        //
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function Valid()
    {
        set_time_limit(300);
        $row = 30;
           
            //$row++;
        for ($i=7; $i < 50; $i+=2) { 
        if (($handle = fopen("D:/TA/Valid.csv", "r")) !== FALSE) {

                $benar = 0;
                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
               
                // echo json_encode($data);
                 echo $data[1];
                $hsl = $this->CalculateV2($data[0],$i);
                 echo " = ".$hsl."<br>";
                if($data[1] == $hsl )
                    $benar++;

                }
                $percent = $benar / $row * 100;
                echo "persentase k = ".$i. ": ".$percent."<br>";
            }
        }
           
            echo "jumlah row = ".$row;
        
        fclose($handle);
       // echo $row;
    }

     /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function kValid()
    {
        set_time_limit(3600);
        $validation= array();

        $row = 80;
        $awal = 1;
        $akhir = 81;
    for ($v=1; $v < 1464; $v+=$row) { 
              
                    
        $hsl = array();
        // for ($i=7; $i < 50; $i+=2) { 
        //     $arValidKnn[$i] = array();
        // }
        for ($i=$awal; $i < $akhir; $i++) { 
            $pst = Posting::find($i);
            if($pst!= null)
            {
                //echo $pst->posting.'<br>';
                $hsl = $this->CalculateV($pst->posting_clean,$i,$awal,$akhir,$hsl);
                $hukum =array();
                foreach ($pst->Pelanggaran as $key => $value) {
                    $hukum[] = $value->id;
                }

                if(count($hukum)==0)
                    $hukum =0;
                else
                {
                    sort($hukum);
                    $hukum = implode(',', $hukum);
                }
                //echo "<br>".$i."<br>";
                foreach ($hsl as $key => $value) {
                    //echo "Pelanggaran -> ".$hukum.' kena ->'. $value[$i]."<br>";
                    if($hukum==$value[$i])
                    {
                        $hsl[$key][$i] = 1;
                    }
                    else
                    {
                        $hsl[$key][$i] = 0;
                    }
                }
            }


            //echo $pst->posting.'<br>';
            
            //echo "<br><br>";
        }
        foreach ($hsl as $key => $value) {
                $hsl[$key] = array_sum($value)/count($value) * 100;
            }
            $validation[$awal.'-'.$akhir] = $hsl;
            $awal += $row;
            $akhir += $row;
        // foreach ($hsl[7] as $key => $value) {
        //     echo $key.' = '. $value.'<br>';
        // }
       
    }


        echo "<table border='1'>";
        echo "<tr>";
        echo "<td>Urut</td>";
        for ($i=21; $i < 50; $i+=2) { 
            echo "<td>".$i."</td>";
        }
        echo "</tr>";
        foreach ($validation as $key => $value) {
            
            echo "<tr>";
            echo "<td>".$key."</td>";
            foreach ($value as $pos) {
                echo '<td>'.$pos.'</td>';     
            }
            echo "</tr>";
        }
    }

    function CalculateV($kata_bersih, $id,$awal,$akhir,$arValidKnn)
    {
        $arhasil = $arValidKnn;
        $hasil = '';
        //$knn =$kn; //berapa knn nya
        $kata = $kata_bersih;// str_replace('id',' ',$kata_bersih);
        //$kata = $this->StemmerStopword($kata);
        //echo $kata.'<br>';

        $arkata = explode(' ', $kata); //ini fungsinya seperti split, kemudian menciptakan array sesuai dari query inputan
        $arBobotQuery = array(); // ini adalah array untuk nanti menyimpan bobot TF-RAW dari query inputan
        $arKataQuery= array();
        foreach ($arkata as $data) {
            if($data != ''  && strlen($data)>1 && $data != 'id')
            {
                if(array_key_exists($data, $arBobotQuery))
                {
                    $arBobotQuery[$data] +=1;
                }
                else
                {
                    $arBobotQuery[$data] =1 ;
                    $arKataQuery[] = $data;
                }
            }
        }
        $arkata = $arKataQuery;

        $arDocWeight = array();// ini menyimpan bobot dari TF-IDF dari database
        $wrd = Posting::where('id','<',$awal)->orWhere('id','>',$akhir)->orderBy('id','desc')->get();
        $path = resource_path('assets/jsonkata.txt');
        $myfile = fopen($path, "r");
        $ka = fread($myfile, filesize($path));
        $ka = json_decode($ka,true);
        foreach ($wrd as $key => $row) {   

          // while ($row = mysqli_fetch_assoc($result)) { // masukkan hasil query database ke $ardocweight
                $rowDoc = array('id'=>$row['id'], 'ori'=>$row['posting'],'clean'=>$row['posting_clean'],'melanggar'=>$row['melanggar']); // isi dari arDocWeight itu array lagi yang berisi barisan bobot sesuai jumlah $arkata
                $arpostingg = explode(' ', $rowDoc['clean']);
                $arpostingg = array_count_values($arpostingg);

                foreach ($arkata as $key => $value) {
                    $rowDoc['clean'] = str_replace($value,"<span style='color: red;'>".$value."</span>",$rowDoc['clean']);
                    if(array_key_exists($value, $arpostingg))
                        $rowDoc[$value] = array_key_exists($value, $ka)?$ka[$value]:0* $arpostingg[$value]; //$row[$value];
                    else
                        $rowDoc[$value] = 0;
                }
            
                $arDocWeight[] = $rowDoc;                       
        } 
        //echo "<br><br>";
         //echo json_encode($arDocWeight);

        // Method Cosine
        $hasil .= "<h2>Hasil Pembobotan menggunakan Metode Cosine</h2>";
        foreach ($arDocWeight as $key=>$value) 
        {
            $pembilang = 0;
            $penyebutq = 0;
            $penyebutt = 0;
            foreach ($arBobotQuery as $data => $bobot) {
                $pembilang += $bobot*$value[$data]; //min([$bobot,$value[$data]]);
                $penyebutt += $bobot * $bobot;
                $penyebutq += $value[$data] * $value[$data];
            }   
            $penyebut = $penyebutq * $penyebutt;
            $penyebut = sqrt($penyebut);
            //echo "$penyebut, $pembilang, $penyebutt, $penyebutq";
            $similarity = 0;
            if($penyebut != 0)
                $similarity = $pembilang/$penyebut;
            $arHasil[] = array('id'=> $value['id'], 'ori'=>$value['ori'],'clean'=>$value['clean'],'bobot'=> $similarity,'pelanggaran'=>$value['melanggar']);

        }

        foreach ($arHasil as $key => $bot) {
            $arbobot[$key] = $bot['bobot'];
        }
        array_multisort($arbobot, SORT_DESC, $arHasil);

        // $hasil .= "<br>";
        // $hasil .= "Diambil ".$knn." posting teratas sebagai pembanding";
        // $hasil .= "<br>";
        
        //$arValidKnn = array();
        for ($knn=21; $knn < 50; $knn+=2) { 
            $treshold = round($knn/5,0,PHP_ROUND_HALF_UP);
            //echo $treshold;
            //$arValidKnn[$knn] = array();
            
            $arKNN = array();
            
            $p_melanggar=0;
            $p_tdk_melanggar=0;
            $artopPosting = array();
            for ($i=0; $i < $knn; $i++) {
                
                $getPelanggaran = Posting::find($arHasil[$i]['id']);
                $artopPosting[] = $arHasil[$i]['id'];
                //hitung jumlah yang melanggar
                if($arHasil[$i]['bobot']>0)
                {
                    //echo $arHasil[$i]['pelanggaran'];
                    if($arHasil[$i]['pelanggaran'] == '1')
                        $p_melanggar++;
                    else
                        $p_tdk_melanggar++;


                    //$arHasil[$i]['pelanggaran'] .= '=>';
                    // echo $getPelanggaran->melanggar.',';

                    foreach ($getPelanggaran->Pelanggaran as $row) 
                    // while($row = mysqli_fetch_assoc($resultGetPelanggaran))
                    {
                        //$arHasil[$i]['pelanggaran'] .= $row['id'];
                        if(array_key_exists($row['id'],$arKNN))
                        {
                            //echo "ada";
                            $arKNN[$row['id']]++;
                        }
                        else
                        {
                            //echo "kosong";
                            $arKNN[$row['id']] = 1;
                        }
                    } 

                }  
            }
            //echo json_encode($arKNN).'';
            arsort($arKNN);
            $Posting = new Posting;
            $Posting->posting=$kata_bersih;
            $Posting->posting_clean = $kata;
            $hasil .= $p_tdk_melanggar .'>'.$p_melanggar.'<br>';
            //echo $p_tdk_melanggar .'>'.$p_melanggar.'<br>';
            if($p_tdk_melanggar > $p_melanggar)
            {
                $arValidKnn[$knn][$id] = 0;
                //return 0;
            }   
            else
            {
                // masukkan hukuman dari posting melanggar dan tampilkan juga
                $hs =array();
                foreach ($arKNN as $key => $value)
                {
                    
                    if($value>= $treshold)
                    {    
                        //echo "bisa=>".$key;
                        $hs[] = $key;
                    }
                    sort($hs);

                    
                    //return implode(',', $hs);
                }
                $arValidKnn[$knn][$id] = implode(',', $hs);
                //echo implode(',', $hs).'<br>';
            }
        }  
        return $arValidKnn;  
    }


    function CalculateV2($kata_bersih,$knn)
    {

        $hasil = '';
        //$knn =$kn; //berapa knn nya
        $kata = $kata_bersih;// str_replace('id',' ',$kata_bersih);
        $kata = $this->StemmerStopword($kata);
        //echo $kata.'<br>';

        $arkata = explode(' ', $kata); //ini fungsinya seperti split, kemudian menciptakan array sesuai dari query inputan
        $arBobotQuery = array(); // ini adalah array untuk nanti menyimpan bobot TF-RAW dari query inputan
        $arKataQuery= array();
        foreach ($arkata as $data) {
            if($data != ''  && strlen($data)>1 && $data != 'id')
            {
                if(array_key_exists($data, $arBobotQuery))
                {
                    $arBobotQuery[$data] +=1;
                }
                else
                {
                    $arBobotQuery[$data] =1 ;
                    $arKataQuery[] = $data;
                }
            }
        }
        $arkata = $arKataQuery;

        $arDocWeight = array();// ini menyimpan bobot dari TF-IDF dari database
        $wrd = Posting::all();
        $path = resource_path('assets/jsonkata.txt');
        $myfile = fopen($path, "r");
        $ka = fread($myfile, filesize($path));
        $ka = json_decode($ka,true);
        foreach ($wrd as $key => $row) {   

          // while ($row = mysqli_fetch_assoc($result)) { // masukkan hasil query database ke $ardocweight
                $rowDoc = array('id'=>$row['id'], 'ori'=>$row['posting'],'clean'=>$row['posting_clean'],'melanggar'=>$row['melanggar']); // isi dari arDocWeight itu array lagi yang berisi barisan bobot sesuai jumlah $arkata
                $arpostingg = explode(' ', $rowDoc['clean']);
                $arpostingg = array_count_values($arpostingg);

                foreach ($arkata as $key => $value) {
                    $rowDoc['clean'] = str_replace($value,"<span style='color: red;'>".$value."</span>",$rowDoc['clean']);
                    if(array_key_exists($value, $arpostingg))
                        $rowDoc[$value] = array_key_exists($value, $ka)?$ka[$value]:0* $arpostingg[$value]; //$row[$value];
                    else
                        $rowDoc[$value] = 0;
                }
            
                $arDocWeight[] = $rowDoc;                       
        } 
        //echo "<br><br>";
         //echo json_encode($arDocWeight);

        // Method Cosine
        $hasil .= "<h2>Hasil Pembobotan menggunakan Metode Cosine</h2>";
        foreach ($arDocWeight as $key=>$value) 
        {
            $pembilang = 0;
            $penyebutq = 0;
            $penyebutt = 0;
            foreach ($arBobotQuery as $data => $bobot) {
                $pembilang += $bobot*$value[$data]; //min([$bobot,$value[$data]]);
                $penyebutt += $bobot * $bobot;
                $penyebutq += $value[$data] * $value[$data];
            }   
            $penyebut = $penyebutq * $penyebutt;
            $penyebut = sqrt($penyebut);
            //echo "$penyebut, $pembilang, $penyebutt, $penyebutq";
            $similarity = 0;
            if($penyebut != 0)
                $similarity = $pembilang/$penyebut;
            $arHasil[] = array('id'=> $value['id'], 'ori'=>$value['ori'],'clean'=>$value['clean'],'bobot'=> $similarity,'pelanggaran'=>$value['melanggar']);

        }

        foreach ($arHasil as $key => $bot) {
            $arbobot[$key] = $bot['bobot'];
        }
        array_multisort($arbobot, SORT_DESC, $arHasil);

        // $hasil .= "<br>";
        // $hasil .= "Diambil ".$knn." posting teratas sebagai pembanding";
        // $hasil .= "<br>";
        
        //$arValidKnn = array();
            $treshold = round($knn/5,0,PHP_ROUND_HALF_UP);
            echo $treshold;
            //$arValidKnn[$knn] = array();
            
            $arKNN = array();
            
            $p_melanggar=0;
            $p_tdk_melanggar=0;
            $artopPosting = array();
            for ($i=0; $i < $knn; $i++) {
                
                $getPelanggaran = Posting::find($arHasil[$i]['id']);
                $artopPosting[] = $arHasil[$i]['id'];
                //hitung jumlah yang melanggar
                if($arHasil[$i]['bobot']>0)
                {
                    //echo $arHasil[$i]['pelanggaran'];
                    if($arHasil[$i]['pelanggaran'] == '1')
                        $p_melanggar++;
                    else
                        $p_tdk_melanggar++;


                    //$arHasil[$i]['pelanggaran'] .= '=>';
                    // echo $getPelanggaran->melanggar.',';

                    foreach ($getPelanggaran->Pelanggaran as $row) 
                    // while($row = mysqli_fetch_assoc($resultGetPelanggaran))
                    {
                        //$arHasil[$i]['pelanggaran'] .= $row['id'];
                        if(array_key_exists($row['id'],$arKNN))
                        {
                            //echo "ada";
                            $arKNN[$row['id']]++;
                        }
                        else
                        {
                            //echo "kosong";
                            $arKNN[$row['id']] = 1;
                        }
                    } 

                }  
            }
            //echo json_encode($arKNN).'';
            arsort($arKNN);
            $Posting = new Posting;
            $Posting->posting=$kata_bersih;
            $Posting->posting_clean = $kata;
            $hasil .= $p_tdk_melanggar .'>'.$p_melanggar.'<br>';
            //echo $p_tdk_melanggar .'>'.$p_melanggar.'<br>';
            if($p_tdk_melanggar > $p_melanggar)
            {
                //$arValidKnn[$knn][$id] = 0;
                return 0;
            }   
            else
            {
                // masukkan hukuman dari posting melanggar dan tampilkan juga
                $hs =array();
                foreach ($arKNN as $key => $value)
                {
                    
                    if($value>= $treshold)
                    {    
                        //echo "bisa=>".$key;
                        $hs[] = $key;
                    }
                    sort($hs);

                    
                    //return implode(',', $hs);
                }
                //$arValidKnn[$knn][$id] = implode(',', $hs);
                return implode(',', $hs);
            }
        
        return $arValidKnn;  
    }
}


    
