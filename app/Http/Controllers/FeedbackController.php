<?php

namespace App\Http\Controllers;

use App\Feedback;
use Illuminate\Http\Request;
use App\Events\FeedbackReceived;

class FeedbackController extends Controller
{
    protected function createOrIncrement(String $word) {
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
        $data = null;
        $message = 'data berhasil ditambahkan';
        $status = 'success';
        $status_code = 500;

        $words = $request->get('words');

        // ? tidak boleh menggunakan tanda koma
        if(strpos($words, ',') !== false) {
            $message = 'Kata tidak boleh mengandung tanda koma';
            $status = 'error';
            $status_code = 400;
        }

        // ? Memisahkan spasi
        $words = explode(' ', $words);

        // ? cek kata lebih dari 3
        if(count($words) > 3) {
            $message = 'kata tidak boleh lebih dari 3';
            $status = 'error';
            $status_code = 400;
        }

        foreach($words as $word) {
            $this->createOrIncrement($word);
        }

        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data,
        ], $status_code);
    }

    public function dashboard() {
        return view('dashboard');
    }

    public function dashboardData() {
        return response()->json($this->getTopTen());
    }

    protected function getTopTen() {
        $topTen = Feedback::orderBy('count', 'DESC')->get()->take(10);

        return $topTen;
    }
}
