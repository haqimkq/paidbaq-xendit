<?php

use Illuminate\Database\Seeder;
use App\Models\ApiClient;
class ApiClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ApiClient::insert([[
            "client_id" => "63d078cc-6876-41ce-bee6-dbac485086a2",
            "client_secret" => "2w5ps6uUcFAxPdWkkg6puPtzKrgCe2ncAAn",
            "signature_key" => "SDK9Tgt9htnHMVcgGK7heaC8rL2R5xTB",
            "status" => "active",
            "owner" => 1,
            "environment" => "development",
        ], [
            "client_id" => "75923af4-a988-4057-852c-38240731d70c",
            "client_secret" => "VTnuvszWwxWmp8nE6C9RWPLYmNZDX4qFy38",
            "signature_key" => "67XByY5pVgUHct6xPF62GDYXtPctDRH2",
            "status" => "active",
            "owner" => 1,
            "environment" => "production",
        ]]);
    }
}
