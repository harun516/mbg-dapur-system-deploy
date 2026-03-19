<?php

namespace Database\Seeders;

use App\Models\Delivery;
use App\Models\ProductionPlan;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DeliverySeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Get or create a courier user
        $courier = User::firstOrCreate(
            ['email' => 'kurir@example.com'],
            [
                'name' => 'Kurir Test',
                'password' => bcrypt('password'),
                'role' => 'kurir',
                'status_enable' => true,
            ]
        );

        // Get production plans
        $productionPlans = ProductionPlan::where('status_enable', 1)->take(5)->get();

        if ($productionPlans->isEmpty()) {
            // If no production plans, skip seeding
            $this->command->warn('No production plans found. Skipping delivery seeding.');
            return;
        }

        // Create sample deliveries
        foreach ($productionPlans as $plan) {
            Delivery::create([
                'production_plan_id' => $plan->id,
                'recipient_id' => $plan->recipient_id ?? 1,
                'courier_id' => $courier->id,
                'status' => 'Menunggu Kurir',
                'status_enable' => true,
                'waktu_antar' => now(),
                'waktu_sampai' => null,
                'foto_bukti' => null,
            ]);

            // Also create one with "Proses Antar" status
            Delivery::create([
                'production_plan_id' => $plan->id,
                'recipient_id' => $plan->recipient_id ?? 1,
                'courier_id' => $courier->id,
                'status' => 'Proses Antar',
                'status_enable' => true,
                'waktu_antar' => now()->subHours(2),
                'waktu_sampai' => null,
                'foto_bukti' => null,
            ]);
        }

        $this->command->info('Delivery seeder executed successfully!');
    }
}
