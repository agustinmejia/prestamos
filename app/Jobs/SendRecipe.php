<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Http;

class SendRecipe implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $url;
    protected $phone;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($url, $phone)
    {
        $this->url = $url;
        $this->phone = $phone;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (setting('servidores.image-from-url') && setting('servidores.whatsapp') && setting('servidores.whatsapp-session')) {
            $response = Http::get(setting('servidores.image-from-url').'/generate?url='.$this->url);
            if($response->ok()){
                $res = json_decode($response->body());
                Http::post(setting('servidores.whatsapp').'/send?id='.setting('servidores.whatsapp-session'), [
                    'phone' => '591'.$this->phone,
                    'text' => 'Gracias por su preferencia!',
                    'image_url' => $res->url,
                ]);
            }else{
                Log::error('Error al enviar la notificación de whatsapp', ['url' => $this->url, 'phone' => '591'.$this->phone]);
            }
        }
    }
}
