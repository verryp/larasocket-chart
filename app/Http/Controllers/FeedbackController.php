<?php

namespace App\Http\Controllers;

use App\Feedback;
use Illuminate\Http\Request;
use App\Events\FeedbackReceived;

class FeedbackController extends Controller
{
    public function index() {
        return view('dashboard');
    }

    public function create() {
        return view('input');
    }

    protected function createOrIncrement(String $word) {
        // ? convert ke lower case
        $word = strtolower($word);

        $feedback = Feedback::where('word', $word)->first();

        if($feedback) {
            $feedback->increment('count');
        } else {
            Feedback::create([
                'word' => $word,
                'count' => 1,
            ]);
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

        $words = $request->get('words');

        // ? tidak boleh menggunakan tanda koma
        if(strpos($words, ',') !== false) {
            return response()->json([
                'message' => 'Kata tidak boleh mengandung tanda koma'
            ], 400);
        }

        // ? Memisahkan spasi
        $words = explode(' ', $words);

        // ? cek kata lebih dari 3
        if(count($words) > 3) {
            return response()->json([
                'message' => 'kata tidak boleh lebih dari 3'
            ], 400);
        }

        foreach($words as $word) {
            $this->createOrIncrement($word);
        }

        $data = json_decode($this->getTopTen());
        broadcast(new FeedbackReceived($data));

        return response()->json('success');
    }

    public function dashboardData() {
        return response()->json($this->getTopTen());
    }

    protected function getTopTen() {
        $topTen = Feedback::orderBy('count', 'DESC')->get()->take(10);

        return $topTen;
    }
}
