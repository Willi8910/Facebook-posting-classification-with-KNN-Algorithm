<?php

namespace App\Http\Controllers;

use App\Posting;
use App\Pelanggaran;
use App\History;
use Illuminate\Http\Request;

class PostingController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }
/**
     * Store a newly created resource in storage.
     *
     *
     * @return \Illuminate\Http\Response
     */
    public function hapus()
    {
        $stopwordremove = new \Sastrawi\StopWordRemover\StopWordRemoverFactory(); 
        //$stopwordremove->add('yang');
    }

     public function hapushst()
    {
        $idposting =1439;
        $post = Posting::find($idposting);
        $post->Pelanggaran()->detach();
        $hst = History::where('posting_id',$idposting)->first();
        $hst->delete();
        $post->delete();
        // $word = 'la la land a 123 b1 bb2 ^&()_id';
        // $stemmerFactory = new \Sastrawi\Stemmer\StemmerFactory();
        // $stemmer  = $stemmerFactory->createStemmer();
        // $stopwordremove = new \Sastrawi\StopWordRemover\StopWordRemoverFactory(); 
        // $stopword = $stopwordremove->createStopWordRemover();

        // $word =  preg_replace('/[0-9]+/', '', $word);
        // $word = str_replace('-',' ',$word);
        // $word = str_replace('\'',' ',$word);
        // //$word = str_replace('id',' ',$word);

        // // stem
        // $sentence =$word;// $_POST['katadasar'];
        // $output   = $stemmer->stem($sentence);
        // $hasil = $stopword->remove($output);
        // $hasil =  preg_replace(" '/[a-z]+/' ", '', $hasil);
        // $arh = explode(' ', $hasil);
        // echo json_encode($arh).'<br>';
        // foreach ($arh as $key => $data) {
        //     if($data != '' && strlen($data)>2 && $data != 'id')
        //     {
        //         echo $data.'='.strlen($data).'<br>';
        //     }
        // }
        
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listHistory()
    {
        $hst = History::all();
        $urut= 1;
        return view('lstHistory',compact('hst','urut'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function detailHistory($id)
    {
        $hst = History::find($id);
        
        //tentukan hukum maksimal
        $denda='';
        $hukum=0;
        foreach ($hst->Posting->Pelanggaran as $key => $value) {
            if($value->hukum > $hukum)
            {
                $hukum = $value->hukum;
                $denda= $value->denda;
            }
        }

        //ambil posting yang di history yang berkaitan
        $arNeigh = explode(',', $hst->posting_id_neighbour );
        $arPosting = array();
        foreach ($arNeigh as $key => $value) {
            $arPosting[] = Posting::find($value);
        }
        return view('detailHistory',['hst'=>$hst,'hukum'=>$hukum,'arPosting'=>$arPosting, 'denda'=>$denda]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listPosting()
    {
        $posting = Posting::paginate(50);
        $test = $posting->find(1);
        // echo $test->id;
        $pelanggaran = Pelanggaran::all();
        return view('lstPosting',compact('posting','pelanggaran'));   

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updatePosting(Request $request)
    {
        // arpost itu ada 3 isinya, pertama idposting, kedua id pelanggaran sebelum update, dan id pelanggaran setelah update
        $arpost = explode(',', $request->pel);
        $posting = Posting::find($arpost[0]);
        $pel = $posting->Pelanggaran->find($arpost[2]);
        if($pel == null)
        {
            $posting->Pelanggaran()->detach($arpost[1]);

            $posting->Pelanggaran()->attach($arpost[2]);
            $data = $posting->id .' : '. $request->pel;
            return $data;    
        }
        else
            return 0;
            
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getPosting(Request $request)
    {
        $posting = Posting::find($request->post_id);
        return $posting->posting;        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
public function deletePelanggaran(Request $request)
{
    $arpost = explode(',', $request->pel);
    $posting = Posting::find($arpost[0]);
    $posting->Pelanggaran()->detach($arpost[1]);
    // echo $posting->Pelanggaran->count();
    if($posting->Pelanggaran->count() == 0)
    {
        // echo "string";
        $posting->melanggar = 0;
        $posting->save();
    }
    // $posting->Pelanggaran()->attach($arpost[2]);

    $data =$request->pel;
    return $data;        
}

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
public function addPelanggaran(Request $request)
{
    $posting = Posting::find($request->post_id);
    $pel = $posting->Pelanggaran->find($request->pel_id);
    if($pel == null)
    {
        $posting->Pelanggaran()->attach($request->pel_id);
        $append = '<li>';
        $append .= '<select class="select">';
        foreach (Pelanggaran::all() as $key => $value) {
            if($value->id == $request->pel_id)
            {
                $append .= '<option selected value="'.$posting->id.','.$request->pel_id.','.$value->id.'">'.$value->pasal.'</option>';
            }
            else
            {
                 $pellain = $posting->Pelanggaran->find($value->id);
                if($pellain == null)
                $append .= '<option value="'.$posting->id.','.$request->pel_id.','.$value->id.'">'.$value->pasal.'</option>';
            }
        }
    $append .= '</select><span class="close" id="'.$posting->id.','.$request->pel_id.'">x</span>
        </li>';
        return redirect('listPosting');
        //return $append;        
    }
    else
    {
        return 'no';
    }
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
     * @param  \App\Posting  $posting
     * @return \Illuminate\Http\Response
     */
    public function show(Posting $posting)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Posting  $posting
     * @return \Illuminate\Http\Response
     */
    public function edit(Posting $posting)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Posting  $posting
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Posting $posting)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Posting  $posting
     * @return \Illuminate\Http\Response
     */
    public function destroy(Posting $posting)
    {
        //
    }
}
