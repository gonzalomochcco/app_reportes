<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $sqlPath = database_path('seeders/data.sql');

        if (file_exists($sqlPath)) {

            $sql = file_get_contents($sqlPath);
            $now = now()->format('Y-m-d H:i:s');

            // Extraer todos los INSERTs desde el archivo SQL
            preg_match('/INSERT INTO subscriptions.*?;/s', $sql, $subscriptionsMatch);
            preg_match('/INSERT INTO subscription_reports.*?;/s', $sql, $reportsMatch);
            preg_match('/INSERT INTO report_loans.*?;/s', $sql, $loansMatch);
            preg_match('/INSERT INTO report_other_debts.*?;/s', $sql, $debtsMatch);
            preg_match('/INSERT INTO report_credit_cards.*?;/s', $sql, $cardsMatch);

            if (!empty($subscriptionsMatch[0])) {
                $query = str_replace(
                    'INSERT INTO subscriptions (full_name, document, email, phone)',
                    "INSERT INTO subscriptions (full_name, document, email, phone, created_at, updated_at)",
                    $subscriptionsMatch[0]
                );
                $query = str_replace("'),", "', '$now', '$now'),", $query);
                $query = str_replace("');", "', '$now', '$now');", $query);
                DB::unprepared($query);
                $this->command->info('✅ Subscriptions importados');
            }

            if (!empty($reportsMatch[0])) {
                $query = str_replace(
                    'INSERT INTO subscription_reports (subscription_id, period)',
                    "INSERT INTO subscription_reports (subscription_id, period, created_at, updated_at)",
                    $reportsMatch[0]
                );
                $query = str_replace("),", ", '$now', '$now'),", $query);
                $query = str_replace(");", ", '$now', '$now');", $query);
                DB::unprepared($query);
                $this->command->info('✅ Subscription Reports importados');
            }

            if (!empty($loansMatch[0])) {
                $query = str_replace(
                    'INSERT INTO report_loans (subscription_report_id, bank, status, currency, amount, expiration_days)',
                    "INSERT INTO report_loans (subscription_report_id, bank, status, currency, amount, expiration_days, created_at, updated_at)",
                    $loansMatch[0]
                );
                $query = str_replace("),", ", '$now', '$now'),", $query);
                $query = str_replace(");", ", '$now', '$now');", $query);
                DB::unprepared($query);
                $this->command->info('✅ Report Loans importados');
            }

            if (!empty($debtsMatch[0])) {
                $query = str_replace(
                    'INSERT INTO report_other_debts (subscription_report_id, entity, currency, amount, expiration_days)',
                    "INSERT INTO report_other_debts (subscription_report_id, entity, currency, amount, expiration_days, created_at, updated_at)",
                    $debtsMatch[0]
                );
                $query = str_replace("),", ", '$now', '$now'),", $query);
                $query = str_replace(");", ", '$now', '$now');", $query);
                DB::unprepared($query);
                $this->command->info('✅ Report Other Debts importados');
            }

            if (!empty($cardsMatch[0])) {
                $query = str_replace(
                    'INSERT INTO report_credit_cards (subscription_report_id, bank, currency, line, used)',
                    "INSERT INTO report_credit_cards (subscription_report_id, bank, currency, line, used, created_at, updated_at)",
                    $cardsMatch[0]
                );
                $query = str_replace("),", ", '$now', '$now'),", $query);
                $query = str_replace(");", ", '$now', '$now');", $query);
                DB::unprepared($query);
                $this->command->info('✅ Report Credit Cards importados');
            }
        } else {
            $this->command->error('⚠️  Archivo data.sql no encontrado en la raíz del proyecto');
        }
    }
}
