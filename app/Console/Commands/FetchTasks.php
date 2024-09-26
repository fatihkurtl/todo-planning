<?php

namespace App\Console\Commands;

use App\Models\Developer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Task;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Storage;

class FetchTasks extends Command
{
    protected $signature = 'tasks:fetch';
    protected $description = 'Fetch tasks from external APIs';

    public function handle()
    {
        // Provider 1
        // GitHub daki verilerde business task json da }] isaretleri eksik oldugu icin elle aldim ve duzeltip mock data olarak kullandım.
        //$response1 = Http::withoutVerifying()->get('https://gist.githubusercontent.com/firatozpinar/8b6ac47e177f07bd99046f873154cef3/raw/b01e456f644370b1365363005c52631e182e66eb/gistfile1.txt');

        /* $guzzle = new Client([
            'base_uri' => 'https://gist.githubusercontent.com/firatozpinar/8b6ac47e177f07bd99046f873154cef3/raw/b01e456f644370b1365363005c52631e182e66eb/gistfile1.txt',
            'verify' => false
        ]); */

        /* $response1 = $guzzle->request('GET', 'https://gist.githubusercontent.com/firatozpinar/8b6ac47e177f07bd99046f873154cef3/raw/b01e456f644370b1365363005c52631e182e66eb/gistfile1.txt'); */

        //$tasks1 = $response1->getBody()->getContents(); // bu satırda problem cikartiyordu.

        /* Fatih KURT */
        
        $tasks = file_get_contents(public_path('tasks.json'));
        $responseContent = null;

        if ($tasks != null && json_decode($tasks) != null) {
            $responseContent = json_decode($tasks, true);
        }

        if ($responseContent == null) {
            $this->error('Data not found in response 1.');
            return;
        }



        if ($responseContent['status'] !== 'success' || $responseContent['data'] == null) {
            $this->error('Data not found in response 1.');
            return;
        }

        foreach ($responseContent['data'] as $task) {
            Task::updateOrCreate(
                [
                    'title' => $task['title'],
                    'time' => $task['time'],
                    'level' => $task['level'],
                ]
            );
        }

        $developers = file_get_contents(public_path('developers.json'));
        $responseContent2 = null;

        if ($developers != null && json_decode($developers) != null) {
            $responseContent2 = json_decode($developers, true);
        }

        if ($responseContent2 == null) {
            $this->error('Data not found in response 2.');
            return;
        }


        if ($responseContent2['status'] !== 'success' || $responseContent2['data'] == null) {
            $this->error('Data not found in response 2.');
            return;
        }



        foreach ($responseContent2['relations']['developers']['data'] as $developer) {
            Developer::updateOrCreate(
                [
                    'name' => $developer['name'],
                    'time' => $developer['time'],
                    'level' => $developer['level'],
                    'result' => $developer['result'],
                ]
            );
        }
    }
}
