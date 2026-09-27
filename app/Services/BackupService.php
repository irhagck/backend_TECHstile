<?php

namespace App\Services;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;
use App\Models\Setting;
use App\Models\Backup;
class BackupService
{
   
    protected array $tables = ['users', 'machines', 'productions', 'attendences', 'payments', 'Factories'];

    public function create(string $type = 'manual', ?int $userId = null): Backup
    {
        $backup = Backup::create([
            'filename'   => 'backup_' . now()->format('Y-m-d_H-i-s') . '.json',
            'type'       => $type,
            'status'     => 'pending',
            'created_by' => $userId,
        ]);

        try {
            $data = [];
            foreach ($this->tables as $table) {
                $data[$table] = DB::table($table)->get()->map(fn ($r) => (array) $r)->all();
            }

            $path = 'backups/' . $backup->filename;
            Storage::disk('local')->put($path, json_encode([
                'created_at' => now()->toIso8601String(),
                'tables'     => $data,
            ]));

            $backup->update([
                'status' => 'completed',
                'size'   => Storage::disk('local')->size($path),
            ]);
        } catch (\Throwable $e) {
            $backup->update(['status' => 'failed', 'error' => $e->getMessage()]);
        }

        return $backup;
    }

    public function restore(Backup $backup): void
    {
        $path = 'backups/' . $backup->filename;
        if (!Storage::disk('local')->exists($path)) {
            throw new \RuntimeException('Backup file nahi mili');
        }

        $payload = json_decode(Storage::disk('local')->get($path), true);

        // Safety: restore se pehle mojooda data ka backup
        $this->create('pre_restore', auth()->id());

        Schema::disableForeignKeyConstraints();
        try {
            DB::transaction(function () use ($payload) {
                foreach ($this->tables as $table) {
                    if (!isset($payload['tables'][$table])) continue;

                    DB::table($table)->delete();
                    foreach (array_chunk($payload['tables'][$table], 500) as $chunk) {
                        DB::table($table)->insert($chunk);
                    }
                }
            });
        } finally {
            Schema::enableForeignKeyConstraints();
        }
    }
}