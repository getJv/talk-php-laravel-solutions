<?php

use App\Solutions\ExceptionWithRunnable;
use App\Solutions\ExceptionWithSolution;
use App\Solutions\ExceptionWithSolutionNotes;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/custom', function () {
    throw new ExceptionWithSolution();
});

Route::get('/custom-with-notes', function () {
    throw new ExceptionWithSolutionNotes();
});

Route::get('/custom-solution-provider', function () {
    return 100/0;
});

Route::get('/runnable-solution', function () {
    throw new ExceptionWithRunnable();
});

Route::get('/ai-solution', function () {
    throw new \App\Solutions\ExceptionWithAi("We cant find the word 'potato'. Review your regex.");
});



// Extra --- ollama

Route::get('/ollama-prompt', function () {
    return view('ollama-prompt');
});

//http://host.docker.internal:11434/api/chat
Route::get('/stream/{q?}', function () {
    $question = request()->query('q');
    $url = 'http://host.docker.internal:11434/api/chat';
    $payload = [
        'model' => 'mistral',
        'messages' => [
            ['role' => 'user', 'content' => $question],
        ],
        'stream' => true,
    ];



    return response()->stream(function () use ($url, $payload) {
        $client = new Client();

        $request = new \GuzzleHttp\Psr7\Request(
            'POST',
            $url,
            ['Content-Type' => 'application/json'],
            json_encode($payload)
        );

        $response = $client->send($request, [
            'stream' => true,
            'timeout' => 120
        ]);

        $tt = [];
        $phpStream = $response->getBody()->detach();
        if ($phpStream) {

            while (!feof($phpStream)) {
                $line = stream_get_line($phpStream, 4096, "\n");
                $tt[] = $line;
                if (trim($line) !== '') {
                    try {

                        $json = json_decode($line, true);
                        $tt = $json;
                        echo "data: " . $line . "\n\n";
                        ob_flush();
                        flush();
                    } catch (\Exception $e) {
                        // invalid json
                        //echo "data: " . $line . "\n\n";
                    }
                }
            }
            fclose($phpStream);

            echo "data: " . json_encode(['done' => true]) . "\n\n";
            ob_flush();
            flush();
        }




    }, 200, [
        'Content-Type' => 'text/event-stream',
        'Cache-Control' => 'no-cache',
        'X-Accel-Buffering' => 'no',
        'Connection' => 'keep-alive',
    ]);
});
