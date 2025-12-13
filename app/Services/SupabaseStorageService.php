<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SupabaseStorageService
{
    protected $uploadUrl;
    protected $anonKey;
    protected $serviceKey;

    public function __construct()
    {
        $this->uploadUrl = env('SUPABASE_URL') . '/storage/v1/object';
        $this->anonKey = env('SUPABASE_ANON_KEY');
        $this->serviceKey = env('SUPABASE_KEY');
    }

    function getImage($path, $isMenu)
    {
        if (preg_match('/^https?:\/\//', $path)) {
            return $path;
        }
        if (file_exists(public_path($path))) {
            return asset($path);
        }
        $bucket = $isMenu ? env('SUPABASE_BUCKET_MENU') : env('SUPABASE_BUCKET_MERCHANT');

        return "{$this->uploadUrl}/public/{$bucket}/{$path}";
    }

    /**
     * Upload file ke Supabase Storage
     *
     * @param string $bucket Nama bucket 
     * @param string $path Path file di bucket 
     * @param UploadedFile $file File dari request
     * @return array|string
     */
    public function upload(string $bucket, string $path, UploadedFile $file): array
    {
        if ($file->getSize() > 2 * 1024 * 1024) {
            return [
                'success' => false,
                'error' => 'File size exceeds 2MB limit'
            ];
        }
        if (!str_starts_with($file->getMimeType(), 'image/')) {
            return [
                'success' => false,
                'error' => 'Only image files are allowed'
            ];
        }
        $supabaseUrl = "{$this->uploadUrl}/{$bucket}/{$path}";
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->serviceKey,
                'apikey' => $this->serviceKey,
            ])->attach(
                'file',
                file_get_contents($file->getRealPath()),
                $file->getClientOriginalName()
            )->post($supabaseUrl);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'path' => $path,
                    'public_url' => "{$this->uploadUrl}/public/{$bucket}/{$path}"
                ];
            }

            Log::error('Supabase Upload Error', [
                'status' => $response->status(),
                'body' => $response->body(),
                'url' => $supabaseUrl
            ]);
            return [
                'success' => false,
                'error' => $response->json()['message'] ?? $response->body()
            ];
        } catch (\Exception $e) {
            Log::error('Supabase Upload Exception', ['error' => $e->getMessage()]);
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
}
