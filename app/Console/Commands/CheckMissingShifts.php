<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\EmployeeShift;
use App\Models\AttendanceLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CheckMissingShifts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'shifts:check-missing';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for missing shifts and send data to webhook';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for missing shifts...');

        // Get employee shifts that are pending or accepted but have no attendance log
        $missingShifts = EmployeeShift::whereIn('status', ['pending', 'accepted'])
            ->whereDoesntHave('attendanceLog')
            ->with(['employee', 'shift'])
            ->get();

        if ($missingShifts->isEmpty()) {
            $this->info('No missing shifts found.');
            return;
        }

        $webhookUrl = 'https://services.leadconnectorhq.com/hooks/KVgMIrEYRkKRcfeicJBm/webhook-trigger/4138e295-74e6-47c6-b6fc-8307aafe8856';

        foreach ($missingShifts as $employeeShift) {
            $data = [
                'employee_id' => $employeeShift->employee_id,
                'employee_name' => $employeeShift->employee->name,
                'shift_id' => $employeeShift->shift_id,
                'shift_date' => $employeeShift->shift_date,
                'status' => $employeeShift->status,
                'shift_details' => $employeeShift->shift,
            ];

            try {
                $response = Http::post($webhookUrl, $data);

                if ($response->successful()) {
                    $this->info("Successfully sent data for shift ID {$employeeShift->id} to webhook.");
                    Log::info("Missing shift data sent to webhook", ['shift_id' => $employeeShift->id, 'data' => $data]);
                } else {
                    $this->error("Failed to send data for shift ID {$employeeShift->id}. Response: " . $response->body());
                    Log::error("Failed to send missing shift data to webhook", ['shift_id' => $employeeShift->id, 'response' => $response->body()]);
                }
            } catch (\Exception $e) {
                $this->error("Exception occurred while sending data for shift ID {$employeeShift->id}: " . $e->getMessage());
                Log::error("Exception in sending missing shift data", ['shift_id' => $employeeShift->id, 'error' => $e->getMessage()]);
            }
        }

        $this->info('Finished checking missing shifts.');
    }
}
