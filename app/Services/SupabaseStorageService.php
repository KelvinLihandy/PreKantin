<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\UploadedFile;

class SupabaseStorageService
{
    protected $url;
    protected $anonKey;

    public function __construct()
    {
        $this->url = env('SUPABASE_URL') . '/storage/v1/object';
        $this->anonKey = env('SUPABASE_ANON_KEY'); 
        // kalau butuh akses lebih tinggi bisa pakai SUPABASE_SERVICE_ROLE_KEY
    }

    /**
     * Upload file ke Supabase Storage
     *
     * @param string $bucket Nama bucket 
     * @param string $path Path file di bucket 
     * @param UploadedFile $file File dari request
     * @return array|string
     */
    public function upload(string $bucket, string $path, UploadedFile $file)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->anonKey,
            'Content-Type' => $file->getMimeType(),
        ])->post("{$this->url}/{$bucket}/{$path}", $file->get());

        
        if ($response->successful()) {
            return [
                'success' => true,
                'path' => $path,
                'public_url' => env('SUPABASE_URL') . "/storage/v1/object/public/{$bucket}/{$path}"
            ];
        }

        return [
            'success' => false,
            'error' => $response->body()
        ];
    }
}
