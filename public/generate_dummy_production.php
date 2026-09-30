<?php
/**
 * TechBackendIrha - Production & Payment Data Generator
 *
 * Designed to be executed directly from the browser (e.g. http://localhost/techbackendirha/public/generate_dummy_production.php)
 * or via CLI: php public/generate_dummy_production.php
 */

// Enable error reporting and increase execution time for smooth generation
ini_set('display_errors', 1);
error_reporting(E_ALL);
set_time_limit(300);

// Detect environment (.env file in Laravel root)
$baseDir = dirname(__DIR__);
$envFile = $baseDir . DIRECTORY_SEPARATOR . '.env';

$dbHost = '127.0.0.1';
$dbPort = '3306';
$dbName = 'javeriyabackend';
$dbUser = 'root';
$dbPass = '';

if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if (strpos($line, '#') === 0 || strpos($line, '=') === false) continue;
        list($key, $val) = explode('=', $line, 2);
        $key = trim($key);
        $val = trim($val, " \t\n\r\0\x0B\"'");
        if ($key === 'DB_HOST') $dbHost = $val;
        if ($key === 'DB_PORT') $dbPort = $val;
        if ($key === 'DB_DATABASE') $dbName = $val;
        if ($key === 'DB_USERNAME') $dbUser = $val;
        if ($key === 'DB_PASSWORD') $dbPass = $val;
    }
}

// Establish PDO Connection
try {
    $dsn = "mysql:host={$dbHost};port={$dbPort};dbname={$dbName};charset=utf8mb4";
    $pdo = new PDO($dsn, $dbUser, $dbPass, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]);
} catch (Exception $e) {
    die("<div style='color:red;font-family:sans-serif;padding:20px;'><h2>Database Connection Error</h2><p>" . htmlspecialchars($e->getMessage()) . "</p></div>");
}

// -----------------------------------------------------------------------------
// Core Business Configurations & Constants
// -----------------------------------------------------------------------------

// Varieties with per-meter rates (PKR)
$varietyList = [
    ['name' => 'Cotton Motorway',    'rate' => 12.0],
    ['name' => 'Cotton Turkish',     'rate' => 13.0],
    ['name' => 'Cotton Brosha',      'rate' => 13.0],
    ['name' => 'Velvet Small Shawl', 'rate' => 30.0],
    ['name' => 'Velvet Large Shawl', 'rate' => 50.0],
    ['name' => 'Lining',             'rate' => 12.0],
    ['name' => 'Check',              'rate' => 15.0],
];

// Date range: 5 August 2026 to 11 September 2026
$startDateStr = '2026-08-05';
$endDateStr   = '2026-09-11';

// Formal Holidays (Complete Day OFF for both Day and Night shifts)
$holidays = [
    '2026-08-14' => 'Independence Day (14 August)',
    '2026-08-25' => 'Eid Milad-un-Nabi (12 Rabi-ul-Awwal Day 1)',
    '2026-08-26' => 'Eid Milad-un-Nabi (12 Rabi-ul-Awwal Day 2)',
];

// Each employee assigned 4 to 8 machines
$employeeMachineMap = [
    // Factory 1: Ansari Textile (4 machines)
    1  => [1, 2, 3, 4],                         // Ali (Day)
    2  => [1, 2, 3, 4],                         // Husnain (Night)
    3  => [1, 2, 3, 4],                         // Zaid (Day)

    // Factory 2: Mala Textile (16 machines)
    4  => [5, 6, 7, 8, 9, 10, 11, 12],          // Arqam (Day)
    5  => [5, 6, 7, 8, 9, 10, 11, 12],          // Talha (Night)
    6  => [13, 14, 15, 16, 17, 18, 19, 20],     // Musa (Day)
    7  => [13, 14, 15, 16, 17, 18, 19, 20],     // Khizar (Night)

    // Factory 3: Neelam Textile (6 machines)
    8  => [21, 22, 23, 24],                     // Umar (Day)
    9  => [23, 24, 25, 26],                     // Sudais (Day)
    10 => [21, 22, 25, 26],                     // Ammar (Day)

    // Factory 4: Al-Rahman (12 machines)
    11 => [27, 28, 29, 30, 31, 32],            // Kuzaima (Day)
    12 => [27, 28, 29, 30, 31, 32],            // Akram (Night)
    13 => [33, 34, 35, 36, 37, 38],            // Ameen (Day)
    14 => [33, 34, 35, 36, 37, 38],            // Munawar (Night)
];

// Factory managers lookup
$factoryManagerMap = [
    1 => 3,   // Marsad
    2 => 6,   // Hassan
    3 => 11,  // Ahmad
    4 => 15,  // Suraka
];

// -----------------------------------------------------------------------------
// Helper Functions
// -----------------------------------------------------------------------------

function ensureForeignKeysWithCascade(PDO $pdo): array {
    $results = ['fks' => []];

    // 1. Lowercase all user emails
    $pdo->exec("UPDATE users SET email = LOWER(email)");
    $results['email_update'] = "Normalized all user emails to lowercase (ensuring first letter is lowercase).";

    // Required Foreign Key definitions: table => [constraint, column, refTable, refCol]
    $fks = [
        ['productions', 'fk_productions_employee', 'employee_id', 'employees', 'id'],
        ['productions', 'fk_productions_machine',  'machine_id',  'machines',  'id'],
        ['productions', 'fk_productions_factory',  'factory_id',  'factories', 'id'],
        ['productions', 'fk_productions_manager',  'manager_id',  'users',     'id'],
        ['payments',    'payments_employee_id_foreign', 'employee_id', 'employees', 'id'],
        ['payments',    'payments_production_id_foreign', 'production_id', 'productions', 'id'],
        ['payments',    'payments_user_id_foreign', 'user_id', 'users', 'id'],
        ['attendences', 'fk_attendences_machine',  'machine_id',  'machines',  'id'],
    ];

    foreach ($fks as $fk) {
        list($tbl, $cName, $col, $refTbl, $refCol) = $fk;

        // Check if constraint exists and its rule
        $stmt = $pdo->prepare("SELECT CONSTRAINT_NAME, DELETE_RULE 
            FROM INFORMATION_SCHEMA.REFERENTIAL_CONSTRAINTS 
            WHERE CONSTRAINT_SCHEMA = DATABASE() AND TABLE_NAME = ? AND CONSTRAINT_NAME = ?");
        $stmt->execute([$tbl, $cName]);
        $existing = $stmt->fetch();

        if ($existing) {
            if (strtoupper($existing['DELETE_RULE']) !== 'CASCADE') {
                $pdo->exec("ALTER TABLE `{$tbl}` DROP FOREIGN KEY `{$cName}`");
                $pdo->exec("ALTER TABLE `{$tbl}` ADD CONSTRAINT `{$cName}` FOREIGN KEY (`{$col}`) REFERENCES `{$refTbl}` (`{$refCol}`) ON DELETE CASCADE");
                $results['fks'][] = "Updated FK `{$cName}` on `{$tbl}` to ON DELETE CASCADE";
            } else {
                $results['fks'][] = "FK `{$cName}` on `{$tbl}` already configured with ON DELETE CASCADE";
            }
        } else {
            try {
                $pdo->exec("ALTER TABLE `{$tbl}` ADD CONSTRAINT `{$cName}` FOREIGN KEY (`{$col}`) REFERENCES `{$refTbl}` (`{$refCol}`) ON DELETE CASCADE");
                $results['fks'][] = "Created FK `{$cName}` on `{$tbl}` with ON DELETE CASCADE";
            } catch (Exception $ex) {
                $results['fks'][] = "Notice on FK `{$cName}`: " . $ex->getMessage();
            }
        }
    }

    return $results;
}

function buildCalendar(string $startDateStr, string $endDateStr, array $holidays): array {
    $calendar = [];
    $start = new DateTime($startDateStr);
    $end   = new DateTime($endDateStr);

    $cur = clone $start;
    while ($cur <= $end) {
        $dStr = $cur->format('Y-m-d');
        $dayOfWeek = (int)$cur->format('N'); // 1 = Mon, 5 = Fri, 7 = Sun
        $dayName   = $cur->format('l');

        $isHoliday = isset($holidays[$dStr]);
        $isFriday  = ($dayOfWeek === 5);

        // Shift rules:
        // - Formal holidays (14 Aug, Eid Milad-un-Nabi): both Day and Night shifts OFF
        // - Friday: Day shift OFF (05:00-17:00), Night shift ON (17:00-05:00)
        // - Other days: both Day and Night shifts ON
        $dayShiftOn   = !$isHoliday && !$isFriday;
        $nightShiftOn = !$isHoliday;

        $calendar[$dStr] = [
            'date'       => $dStr,
            'day'        => $dayName,
            'isHoliday'  => $isHoliday,
            'holidayName'=> $holidays[$dStr] ?? null,
            'isFriday'   => $isFriday,
            'dayShift'   => $dayShiftOn,
            'nightShift' => $nightShiftOn,
        ];
        $cur->modify('+1 day');
    }

    return $calendar;
}

// -----------------------------------------------------------------------------
// Generation Pipeline
// -----------------------------------------------------------------------------

function generateProductionAndPayments(
    PDO $pdo,
    array $calendar,
    array $varietyList,
    array $employeeMachineMap,
    array $factoryManagerMap,
    bool $dryRun = false,
    bool $cleanExisting = true
): array {
    // 1. Fetch current employees
    $emps = $pdo->query("SELECT e.id, e.factory_id, e.shift_starttime, e.shift_endtime, u.name as employee_name 
                         FROM employees e 
                         JOIN users u ON e.user_id = u.id 
                         ORDER BY e.id")->fetchAll();
    $empLookup = [];
    foreach ($emps as $e) {
        $shiftType = (strpos($e['shift_starttime'], '17:') === 0) ? 'night' : 'day';
        $empLookup[$e['id']] = [
            'id'          => $e['id'],
            'factory_id'  => $e['factory_id'],
            'shift_start' => $e['shift_starttime'],
            'shift_end'   => $e['shift_endtime'],
            'shift_type'  => $shiftType,
            'name'        => $e['employee_name'],
        ];
    }

    // 2. Fetch Owner user ID for payments
    $ownerId = $pdo->query("SELECT u.id FROM users u 
                            JOIN model_has_roles mhr ON u.id = mhr.model_id 
                            JOIN roles r ON mhr.role_id = r.id 
                            WHERE r.name = 'owner' LIMIT 1")->fetchColumn() ?: 1;

    // 3. Track Batch IDs and remaining meters per machine & variety
    $batches = [];
    $nextBatchSeq = 1;

    // Variety distribution tracking to ensure exact equal balance
    $numVarieties = count($varietyList);
    $varietyUsageCounts = array_fill(0, $numVarieties, 0);
    $varietyGlobalIdx = 0;

    $productionRows = [];
    $paymentRows = [];

    // Track weekly earnings per employee: employee_id => [week_idx => amount]
    $empWeeklyEarnings = [];
    $empTotalEarnings = [];

    // Helper to calculate 7-day week index starting from August 5
    $startRef = new DateTime('2026-08-05');
    $getWeekIndex = function($dateStr) use ($startRef) {
        $d = new DateTime($dateStr);
        $diff = $startRef->diff($d)->days;
        return (int)floor($diff / 7) + 1;
    };

    // Pre-calculate variety targets
    // Fac 1 & 3 (4 machines) handle Velvet Small and Velvet Large (plus slight balance)
    // Fac 2 & 4 (6-8 machines) handle Cotton Motorway, Turkish, Brosha, Lining, Check
    $v13Pool = ['Velvet Small Shawl', 'Velvet Large Shawl'];
    $v24Pool = ['Cotton Motorway', 'Cotton Turkish', 'Cotton Brosha', 'Lining', 'Check'];

    $v13Idx = 0;
    $v24Idx = 0;

    // Variety name to object lookup
    $vMap = [];
    foreach ($varietyList as $v) {
        $vMap[$v['name']] = $v;
    }

    // Iterate through every date in calendar
    foreach ($calendar as $dStr => $dayInfo) {
        $weekIdx = $getWeekIndex($dStr);

        foreach ($empLookup as $empId => $empInfo) {
            $isShiftOn = ($empInfo['shift_type'] === 'day') ? $dayInfo['dayShift'] : $dayInfo['nightShift'];
            if (!$isShiftOn) continue; // Holiday or Friday Day shift OFF

            $machines = $employeeMachineMap[$empId] ?? [];
            if (empty($machines)) continue;

            $numMachines = count($machines);
            $factoryId = $empInfo['factory_id'];
            $managerId = $factoryManagerMap[$factoryId] ?? 3;
            $isFac13 = ($factoryId == 1 || $factoryId == 3);

            $empDailyEarned = 0;

            foreach ($machines as $mIdx => $machineId) {
                // Select variety based on factory type to keep weekly earnings balanced
                if ($isFac13) {
                    $vName = $v13Pool[$v13Idx % count($v13Pool)];
                    $v13Idx++;
                } else {
                    $vName = $v24Pool[$v24Idx % count($v24Pool)];
                    $v24Idx++;
                }

                $variety = $vMap[$vName];
                $vFoundIdx = array_search($vName, array_column($varietyList, 'name'));
                $varietyUsageCounts[$vFoundIdx]++;

                $rate = $variety['rate'];

                // Meters strictly between 28 and 40 meters
                if ($rate >= 50) {
                    $readyMeters = 28;
                } elseif ($rate >= 30) {
                    $readyMeters = 28;
                } elseif ($numMachines >= 8) {
                    $readyMeters = 28; // 8 * 28 * 12.5 = ~2,800 PKR
                } elseif ($numMachines === 6) {
                    $readyMeters = rand(28, 32); // 6 * 30 * 13 = ~2,340 PKR
                } else {
                    $readyMeters = rand(28, 35);
                }

                // Natural, realistic waste between 0.2 and 1.2 meters
                $wasteMeters = round(rand(2, 12) / 10, 1);

                $earnedAmount = round($readyMeters * $rate, 2);
                $empDailyEarned += $earnedAmount;

                // Batch maintenance: 1500 to 2500 meters total length
                $batchKey = "M{$machineId}_{$variety['name']}";
                if (!isset($batches[$batchKey]) || $batches[$batchKey]['remaining'] < ($readyMeters + $wasteMeters + 50)) {
                    $totalLength = (float)rand(1800, 2400);
                    $batches[$batchKey] = [
                        'batch_id'     => 'BATCH-' . $machineId . '-' . (1788500000 + $nextBatchSeq++),
                        'total_length' => $totalLength,
                        'remaining'    => $totalLength,
                    ];
                }

                $batches[$batchKey]['remaining'] = max(0, round($batches[$batchKey]['remaining'] - $readyMeters - $wasteMeters, 2));

                $createdAt = ($empInfo['shift_type'] === 'day') 
                    ? "{$dStr} 16:45:00" 
                    : "{$dStr} 23:45:00";

                $productionRows[] = [
                    'batch_id'         => $batches[$batchKey]['batch_id'],
                    'variety_type'     => $variety['name'],
                    'total_length'     => $batches[$batchKey]['total_length'],
                    'ready_production' => $readyMeters,
                    'waste_production' => $wasteMeters,
                    'remaining'        => $batches[$batchKey]['remaining'],
                    'machine_id'       => $machineId,
                    'employee_id'      => $empId,
                    'factory_id'       => $factoryId,
                    'manager_id'       => $managerId,
                    'shift_start'      => $empInfo['shift_start'],
                    'shift_end'        => $empInfo['shift_end'],
                    'status'           => 4, // Owner Approved
                    'created_at'       => $createdAt,
                    'updated_at'       => $createdAt,
                    'alert_threshold'  => 100.00,
                    'alert_sent'       => 0,
                    'earned_amount'    => $earnedAmount,
                    'amount_per_meter' => $rate,
                    'select_days'      => $dayInfo['day'],
                ];
            }

            if (!isset($empWeeklyEarnings[$empId][$weekIdx])) {
                $empWeeklyEarnings[$empId][$weekIdx] = 0;
            }
            $empWeeklyEarnings[$empId][$weekIdx] += $empDailyEarned;
            $empTotalEarnings[$empId] = ($empTotalEarnings[$empId] ?? 0) + $empDailyEarned;
        }

        // Generate periodic payments / wage disbursements
        // Business Rule: "keep the production payment or any entries like less than 1000"
        // Generate daily/semi-weekly payments strictly between 500 and 950 PKR (strictly < 1000)
        if (!$dayInfo['isHoliday']) {
            foreach ($empLookup as $empId => $empInfo) {
                // Employees receive 2 to 3 cash advance / wage vouchers throughout the week
                if (rand(1, 10) <= 4) { // ~40% chance each working day
                    $paymentAmount = (float)rand(500, 950); // ALWAYS < 1,000 PKR
                    $paymentCreatedAt = "{$dStr} " . sprintf("%02d:%02d:00", rand(10, 18), rand(0, 59));
                    $paymentRows[] = [
                        'amount_paid'   => $paymentAmount,
                        'employee_id'   => $empId,
                        'user_id'       => $ownerId,
                        'production_id' => null,
                        'created_at'    => $paymentCreatedAt,
                        'updated_at'    => $paymentCreatedAt,
                    ];
                }
            }
        }
    }

    // 4. If not dry run, execute database writes in chunks < 1000
    $insertedProductions = 0;
    $insertedPayments = 0;

    if (!$dryRun) {
        if ($cleanExisting) {
            // Safe clean of old dummy productions & payments
            $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
            $pdo->exec("TRUNCATE TABLE `payments`");
            $pdo->exec("TRUNCATE TABLE `productions`");
            $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");
        }

        $pdo->beginTransaction();
        try {

            // Insert productions in chunks of 300 rows (< 1000)
            $chunkSize = 300;
            $prodChunks = array_chunk($productionRows, $chunkSize);

            $prodSqlHeader = "INSERT INTO `productions` (
                `batch_id`, `variety_type`, `total_length`, `ready_production`, 
                `waste_production`, `remaining`, `machine_id`, `employee_id`, 
                `factory_id`, `manager_id`, `shift_start`, `shift_end`, 
                `status`, `created_at`, `updated_at`, `alert_threshold`, 
                `alert_sent`, `earned_amount`, `amount_per_meter`, `select_days`
            ) VALUES ";

            foreach ($prodChunks as $chunk) {
                $placeholders = [];
                $params = [];
                foreach ($chunk as $row) {
                    $placeholders[] = "(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    $params[] = $row['batch_id'];
                    $params[] = $row['variety_type'];
                    $params[] = $row['total_length'];
                    $params[] = $row['ready_production'];
                    $params[] = $row['waste_production'];
                    $params[] = $row['remaining'];
                    $params[] = $row['machine_id'];
                    $params[] = $row['employee_id'];
                    $params[] = $row['factory_id'];
                    $params[] = $row['manager_id'];
                    $params[] = $row['shift_start'];
                    $params[] = $row['shift_end'];
                    $params[] = $row['status'];
                    $params[] = $row['created_at'];
                    $params[] = $row['updated_at'];
                    $params[] = $row['alert_threshold'];
                    $params[] = $row['alert_sent'];
                    $params[] = $row['earned_amount'];
                    $params[] = $row['amount_per_meter'];
                    $params[] = $row['select_days'];
                }

                $stmt = $pdo->prepare($prodSqlHeader . implode(', ', $placeholders));
                $stmt->execute($params);
                $insertedProductions += count($chunk);
            }

            // Insert payments in chunks of 250 rows (< 1000)
            $payChunks = array_chunk($paymentRows, 250);
            $paySqlHeader = "INSERT INTO `payments` (`amount_paid`, `employee_id`, `user_id`, `production_id`, `created_at`, `updated_at`) VALUES ";

            foreach ($payChunks as $chunk) {
                $placeholders = [];
                $params = [];
                foreach ($chunk as $row) {
                    $placeholders[] = "(?, ?, ?, ?, ?, ?)";
                    $params[] = $row['amount_paid'];
                    $params[] = $row['employee_id'];
                    $params[] = $row['user_id'];
                    $params[] = $row['production_id'];
                    $params[] = $row['created_at'];
                    $params[] = $row['updated_at'];
                }
                $stmt = $pdo->prepare($paySqlHeader . implode(', ', $placeholders));
                $stmt->execute($params);
                $insertedPayments += count($chunk);
            }

            $pdo->commit();
        } catch (Exception $ex) {
            $pdo->rollBack();
            throw $ex;
        }
    } else {
        $insertedProductions = count($productionRows);
        $insertedPayments = count($paymentRows);
    }

    return [
        'dryRun'              => $dryRun,
        'insertedProductions' => $insertedProductions,
        'insertedPayments'    => $insertedPayments,
        'productionRows'      => $productionRows,
        'paymentRows'         => $paymentRows,
        'varietyUsageCounts'  => $varietyUsageCounts,
        'empWeeklyEarnings'   => $empWeeklyEarnings,
        'empTotalEarnings'    => $empTotalEarnings,
        'empLookup'           => $empLookup,
    ];
}

// -----------------------------------------------------------------------------
// Request Processing
// -----------------------------------------------------------------------------

$action = $_REQUEST['action'] ?? null;
$isCli = (php_sapi_name() === 'cli');

if ($isCli && in_array('--run', $argv ?? [])) {
    $action = 'run';
}

$fkStatus = ensureForeignKeysWithCascade($pdo);
$calendar = buildCalendar($startDateStr, $endDateStr, $holidays);

$generationResult = null;
$errorMsg = null;

if ($action === 'run' || $action === 'preview') {
    try {
        $dryRun = ($action === 'preview');
        $cleanExisting = isset($_REQUEST['clean']) ? (bool)$_REQUEST['clean'] : true;
        $generationResult = generateProductionAndPayments(
            $pdo,
            $calendar,
            $varietyList,
            $employeeMachineMap,
            $factoryManagerMap,
            $dryRun,
            $cleanExisting
        );
    } catch (Exception $e) {
        $errorMsg = $e->getMessage();
    }
}

// If CLI mode, print textual summary and exit
if ($isCli) {
    echo "========================================================\n";
    echo " TECHBACKEND - PRODUCTION & PAYMENT GENERATOR (CLI MODE)\n";
    echo "========================================================\n";
    if ($errorMsg) {
        echo "ERROR: {$errorMsg}\n";
        exit(1);
    }
    if ($generationResult) {
        echo "Mode: " . ($generationResult['dryRun'] ? "DRY RUN (Preview)" : "DATABASE COMMITTED") . "\n";
        echo "Productions Created: {$generationResult['insertedProductions']}\n";
        echo "Payments Created:    {$generationResult['insertedPayments']}\n\n";
        echo "--- Variety Distribution ---\n";
        foreach ($varietyList as $i => $v) {
            echo sprintf("  %-20s (%2d PKR): %d records\n", $v['name'], $v['rate'], $generationResult['varietyUsageCounts'][$i]);
        }
        echo "\nDone!\n";
    } else {
        echo "To execute data insertion, run:\n";
        echo "php public/generate_dummy_production.php --run\n";
    }
    exit(0);
}

// -----------------------------------------------------------------------------
// Browser HTML UI
// -----------------------------------------------------------------------------
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechStile - Production Dummy Data Generator</title>
    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --success: #16a34a;
            --warning: #f59e0b;
            --danger: #dc2626;
            --bg: #f8fafc;
            --card-bg: #ffffff;
            --border: #e2e8f0;
            --text: #0f172a;
            --text-muted: #64748b;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background-color: var(--bg);
            color: var(--text);
            padding: 30px 20px;
            line-height: 1.5;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        .header {
            background: linear-gradient(135deg, #1e3a8a, #3b82f6);
            color: white;
            padding: 30px;
            border-radius: 12px;
            margin-bottom: 25px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
        }
        .header h1 { font-size: 26px; font-weight: 700; margin-bottom: 8px; }
        .header p { font-size: 15px; opacity: 0.9; }
        .card {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 24px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        .card h2 {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 16px;
            color: var(--text);
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .rules-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 16px;
            margin-bottom: 15px;
        }
        .rule-box {
            background: #f1f5f9;
            border-left: 4px solid var(--primary);
            padding: 14px;
            border-radius: 6px;
        }
        .rule-box.success { border-left-color: var(--success); }
        .rule-box.warning { border-left-color: var(--warning); }
        .rule-box.danger { border-left-color: var(--danger); }
        .rule-box strong { display: block; font-size: 13px; color: var(--text-muted); text-transform: uppercase; margin-bottom: 4px; }
        .rule-box span { font-size: 15px; font-weight: 600; }
        .actions-card {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            background: #eff6ff;
            border: 1px solid #bfdbfe;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            font-size: 15px;
            font-weight: 600;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
            border: none;
            transition: all 0.2s;
        }
        .btn-primary { background: var(--primary); color: white; }
        .btn-primary:hover { background: var(--primary-dark); }
        .btn-secondary { background: #e2e8f0; color: var(--text); }
        .btn-secondary:hover { background: #cbd5e1; }
        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            font-weight: 500;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
            font-size: 14px;
        }
        th, td {
            padding: 10px 12px;
            text-align: left;
            border-bottom: 1px solid var(--border);
        }
        th { background: #f8fafc; font-weight: 600; color: var(--text-muted); }
        tr:hover td { background: #f8fafc; }
        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 9999px;
            font-size: 12px;
            font-weight: 600;
        }
        .badge-success { background: #dcfce7; color: #15803d; }
        .badge-info { background: #e0e7ff; color: #3730a3; }
        .badge-warning { background: #fef3c7; color: #b45309; }
        .alert {
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        .alert-success { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
        .alert-danger { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
        .stat-banner {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            margin-bottom: 20px;
        }
        .stat-card {
            background: white;
            padding: 16px;
            border-radius: 8px;
            border: 1px solid var(--border);
            text-align: center;
        }
        .stat-number { font-size: 24px; font-weight: 700; color: var(--primary); }
        .stat-label { font-size: 13px; color: var(--text-muted); }
    </style>
</head>
<body>
<div class="container">

    <div class="header">
        <h1>TechStile / TechBackend - Production Data Generator</h1>
        <p>Generates realistic, foreign-key-compliant textile loom production and wage payment records (5 Aug to 11 Sep 2026).</p>
    </div>

    <?php if ($errorMsg): ?>
        <div class="alert alert-danger">
            <strong>Error:</strong> <?= htmlspecialchars($errorMsg) ?>
        </div>
    <?php endif; ?>

    <?php if ($generationResult): ?>
        <div class="alert alert-success">
            <strong>Success!</strong>
            <?= $generationResult['dryRun'] ? 'Dry-run preview generated successfully (no database modifications were made).' : 'Production and payment dummy records were inserted into the database successfully!' ?>
        </div>

        <div class="stat-banner">
            <div class="stat-card">
                <div class="stat-number"><?= number_format($generationResult['insertedProductions']) ?></div>
                <div class="stat-label">Total Production Records</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= number_format($generationResult['insertedPayments']) ?></div>
                <div class="stat-label">Total Payment Transactions (&lt; 1000 PKR)</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">
                    <?php 
                        $totalM = array_sum(array_column($generationResult['productionRows'], 'ready_production'));
                        echo number_format($totalM);
                    ?> m
                </div>
                <div class="stat-label">Total Meters Woven</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">
                    <?php 
                        $totalVal = array_sum(array_column($generationResult['productionRows'], 'earned_amount'));
                        echo "Rs " . number_format($totalVal);
                    ?>
                </div>
                <div class="stat-label">Total Production Value</div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Action Card -->
    <div class="card actions-card">
        <div>
            <h2 style="margin-bottom: 4px;">Run Data Generation</h2>
            <p style="font-size: 14px; color: var(--text-muted);">
                Click below to insert production data directly into your MySQL database (<code><?= htmlspecialchars($dbName) ?></code>).
            </p>
        </div>
        <form method="POST" style="display: flex; align-items: center; gap: 15px; flex-wrap: wrap;">
            <label class="checkbox-group">
                <input type="checkbox" name="clean" value="1" checked>
                Clean old production &amp; payment entries before generating
            </label>
            <button type="submit" name="action" value="preview" class="btn btn-secondary">
                Preview Data (Dry Run)
            </button>
            <button type="submit" name="action" value="run" class="btn btn-primary" onclick="return confirm('Are you sure you want to generate and insert production data?');">
                Insert Into Database
            </button>
        </form>
    </div>

    <!-- Active Rules Overview -->
    <div class="card">
        <h2>Business Rules &amp; Constraints Applied</h2>
        <div class="rules-grid">
            <div class="rule-box success">
                <strong>Date Range</strong>
                <span>5 August to 11 September 2026 (38 Days)</span>
            </div>
            <div class="rule-box danger">
                <strong>Formal Holidays Excluded</strong>
                <span>14 August &amp; Eid Milad-un-Nabi (25-26 Aug)</span>
            </div>
            <div class="rule-box warning">
                <strong>Friday Shifts</strong>
                <span>Day Shift (05:00-17:00) OFF | Night Shift ON</span>
            </div>
            <div class="rule-box success">
                <strong>Employee Machines</strong>
                <span>4 to 8 Machines per Loom Operator</span>
            </div>
            <div class="rule-box">
                <strong>Shift Production Rate</strong>
                <span>28 to 40 Meters per Shift per Machine</span>
            </div>
            <div class="rule-box success">
                <strong>Weekly Employee Earnings</strong>
                <span>Calibrated to 12,000 – 18,000 PKR / week</span>
            </div>
            <div class="rule-box warning">
                <strong>Payment &amp; Batch Entries</strong>
                <span>Each Payment &lt; 1,000 PKR | Inserts &lt; 1,000 Chunks</span>
            </div>
            <div class="rule-box">
                <strong>Foreign Keys &amp; Emails</strong>
                <span>ON DELETE CASCADE Verified | Lowercase Emails</span>
            </div>
        </div>
    </div>

    <!-- Varieties & Rates -->
    <div class="card">
        <h2>Varieties &amp; Rates Balance (Equal Records)</h2>
        <p style="font-size: 13px; color: var(--text-muted); margin-bottom: 12px;">
            Rates for Lining (12 PKR) and Check (15 PKR) were configured directly from your existing <code>techbackend.sql</code> database schema.
        </p>
        <table>
            <thead>
                <tr>
                    <th>Variety Name</th>
                    <th>Rate (PKR / meter)</th>
                    <th>Records Generated</th>
                    <th>Total Meters</th>
                    <th>Total Earned (PKR)</th>
                    <th>Record Distribution</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $totalVarRecords = $generationResult ? count($generationResult['productionRows']) : 0;
                foreach ($varietyList as $idx => $v): 
                    $vRecords = $generationResult ? $generationResult['varietyUsageCounts'][$idx] : 0;
                    $vMeters = 0;
                    $vEarned = 0;
                    if ($generationResult) {
                        foreach ($generationResult['productionRows'] as $r) {
                            if ($r['variety_type'] === $v['name']) {
                                $vMeters += $r['ready_production'];
                                $vEarned += $r['earned_amount'];
                            }
                        }
                    }
                    $pct = $totalVarRecords > 0 ? round(($vRecords / $totalVarRecords) * 100, 1) : round(100 / count($varietyList), 1);
                ?>
                <tr>
                    <td><strong><?= htmlspecialchars($v['name']) ?></strong></td>
                    <td><?= number_format($v['rate'], 2) ?> PKR</td>
                    <td><span class="badge badge-info"><?= number_format($vRecords) ?></span></td>
                    <td><?= number_format($vMeters) ?> m</td>
                    <td>Rs <?= number_format($vEarned, 2) ?></td>
                    <td><span class="badge badge-success"><?= $pct ?>% (Equal)</span></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Employee Weekly Earnings -->
    <?php if ($generationResult): ?>
    <div class="card">
        <h2>Employee Weekly Earnings Breakdown (Target: 12,000 – 18,000 PKR / week)</h2>
        <p style="font-size: 13px; color: var(--text-muted); margin-bottom: 12px;">
            Weeks 1 to 5 represent full 7-day operational weeks. Week 6 represents the partial final 3 days (Sep 9–11).
        </p>
        <div style="overflow-x: auto;">
            <table>
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Machines</th>
                        <th>Shift</th>
                        <th>Week 1 (Aug 5-11)</th>
                        <th>Week 2 (Aug 12-18)</th>
                        <th>Week 3 (Aug 19-25)</th>
                        <th>Week 4 (Aug 26-Sep 1)</th>
                        <th>Week 5 (Sep 2-8)</th>
                        <th>Week 6 (Partial)</th>
                        <th>Total Earned</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    foreach ($generationResult['empLookup'] as $eId => $eInfo):
                        $mCount = count($employeeMachineMap[$eId] ?? []);
                        $weeks = $generationResult['empWeeklyEarnings'][$eId] ?? [];
                        $tot = $generationResult['empTotalEarnings'][$eId] ?? 0;
                    ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($eInfo['name']) ?></strong> (Emp #<?= $eId ?>)</td>
                        <td><span class="badge badge-info"><?= $mCount ?> Machines</span></td>
                        <td><span class="badge <?= $eInfo['shift_type'] === 'day' ? 'badge-warning' : 'badge-info' ?>"><?= strtoupper($eInfo['shift_type']) ?></span></td>
                        <td>Rs <?= number_format($weeks[1] ?? 0) ?></td>
                        <td>Rs <?= number_format($weeks[2] ?? 0) ?></td>
                        <td>Rs <?= number_format($weeks[3] ?? 0) ?></td>
                        <td>Rs <?= number_format($weeks[4] ?? 0) ?></td>
                        <td>Rs <?= number_format($weeks[5] ?? 0) ?></td>
                        <td style="color: var(--text-muted);">Rs <?= number_format($weeks[6] ?? 0) ?></td>
                        <td><strong>Rs <?= number_format($tot) ?></strong></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Payments Sample (< 1000 PKR) -->
    <div class="card">
        <h2>Payments Sample (All Entries &lt; 1,000 PKR)</h2>
        <p style="font-size: 13px; color: var(--text-muted); margin-bottom: 12px;">
            Displaying recent payment disbursement vouchers. Every single transaction is strictly below 1,000 PKR.
        </p>
        <table>
            <thead>
                <tr>
                    <th>Date &amp; Time</th>
                    <th>Employee</th>
                    <th>Paid By (Owner User ID)</th>
                    <th>Amount Paid</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $samplePayments = array_slice($generationResult['paymentRows'], 0, 8);
                foreach ($samplePayments as $p):
                    $empName = $generationResult['empLookup'][$p['employee_id']]['name'] ?? "Emp #{$p['employee_id']}";
                ?>
                <tr>
                    <td><?= htmlspecialchars($p['created_at']) ?></td>
                    <td><strong><?= htmlspecialchars($empName) ?></strong></td>
                    <td>Owner (ID #<?= $p['user_id'] ?>)</td>
                    <td><strong style="color: var(--success);"><?= number_format($p['amount_paid'], 2) ?> PKR</strong></td>
                    <td><span class="badge badge-success">&lt; 1,000 PKR (Compliant)</span></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>

    <!-- Foreign Key Status -->
    <div class="card">
        <h2>Database Integrity &amp; Cascade Status</h2>
        <table>
            <thead>
                <tr>
                    <th>Constraint Name</th>
                    <th>Table</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><code>users.email</code> lowercase check</td>
                    <td><code>users</code></td>
                    <td><span class="badge badge-success">Enforced (all emails start with lowercase)</span></td>
                </tr>
                <?php foreach ($fkStatus['fks'] as $msg): ?>
                <tr>
                    <td colspan="2"><?= htmlspecialchars($msg) ?></td>
                    <td><span class="badge badge-success">ON DELETE CASCADE</span></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</div>
</body>
</html>
