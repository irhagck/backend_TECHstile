<?php

namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\BackupService;
use App\Models\Setting;
use App\Models\Backup;
class BackupController extends Controller
{
    public function __construct(private BackupService $service) {}

    public function index()
    {
        return response()->json([
            'auto_backup' => Setting::get('auto_backup', '0') === '1',
            'backups'     => Backup::with('creator:id,name')->latest()->take(20)->get(),
        ]);
    }

    public function store(Request $request)
    {
        $backup = $this->service->create('manual', $request->user()->id);
        return response()->json($backup, $backup->status === 'completed' ? 201 : 500);
    }

    public function toggle(Request $request)
    {
        $request->validate(['enabled' => 'required|boolean']);
        $enabled = $request->boolean('enabled');

        Setting::set('auto_backup', $enabled ? '1' : '0');

        $backup = $enabled ? $this->service->create('auto', $request->user()->id) : null;

        return response()->json(['auto_backup' => $enabled, 'backup' => $backup]);
    }

    public function download(Backup $backup)
    {
        return Storage::disk('local')->download('backups/' . $backup->filename);
    }

    public function restore(Backup $backup)
    {
        try {
            $this->service->restore($backup);
            return response()->json(['message' => 'Data restore ho gaya']);
        } catch (\Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}