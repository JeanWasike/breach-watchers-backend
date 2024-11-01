<?php
namespace App\Services;

use GuzzleHttp\Client;

class SupabaseService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => config('supabase.url'),
            'headers' => [
                'apikey' => config('supabase.key'),
                'Authorization' => 'Bearer ' . config('supabase.key'),
            ],
        ]);
    }

    public function getUserById($id)
    {
        try {
            $response = $this->client->get("/rest/v1/users?id=eq.{$id}", [
                'query' => ['select' => '*'],
            ]);

            $data = json_decode($response->getBody(), true);
            return $data ? $data[0] : null;
        } catch (\Exception $e) {
            \Log::error("Error fetching user from Supabase: " . $e->getMessage());
            return null;
        }
    }
}
