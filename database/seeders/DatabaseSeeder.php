<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Factory;
use App\Models\Machine;
use App\Models\Employee;
use App\Models\Production;
use App\Models\Payment;
use App\Models\Attendence;
use App\Models\Notification;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with complete, rich, interconnected demo data.
     */
    public function run(): void
    {
        // ── 0. CLEAR SPATIE PERMISSION CACHE ───────────────────────────────────
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // ── 1. ROLES & PERMISSIONS SETUP ──────────────────────────────────────
        $permissions = [
            'view factories',    'create factories',    'edit factories',    'delete factories',
            'view machines',     'create machines',     'edit machines',     'delete machines',
            'view productions',  'create productions',  'edit productions',  'delete productions',
            'view employees',    'create employees',    'edit employees',    'delete employees',
            'view attendance',   'create attendance',   'edit attendance',   'delete attendance',
            'view users',        'create users',        'edit users',        'delete users',
            'approve production', 'reject production', 'mark attendance',
            'scan qr', 'view profile', 'verify production',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
        }

        $ownerRole    = Role::firstOrCreate(['name' => 'owner',    'guard_name' => 'web']);
        $managerRole  = Role::firstOrCreate(['name' => 'manager',  'guard_name' => 'web']);
        $employeeRole = Role::firstOrCreate(['name' => 'employee', 'guard_name' => 'web']);

        // Owner: full access to all permissions
        $ownerRole->syncPermissions(Permission::all());

        // Manager: operational floor permissions
        $managerRole->syncPermissions([
            'view factories',
            'view machines',     'create machines',    'edit machines',
            'view productions',  'create productions', 'edit productions',
            'view employees',
            'view attendance',   'create attendance',  'edit attendance',
            'approve production', 'reject production', 'verify production',
            'scan qr', 'view profile',
        ]);

        // Employee: logging & self-service permissions
        $employeeRole->syncPermissions([
            'view productions', 'create productions',
            'view machines',
            'view attendance',  'create attendance',  'mark attendance',
            'scan qr', 'view profile',
        ]);

        // ── 2. FACTORIES SETUP ────────────────────────────────────────────────
        $factoriesData = [
            [
                'name'           => 'Al-Rehman Weaving Mills (Unit 1)',
                'address'        => 'Plot 14-B, Small Industrial Estate, Jaranwala Road',
                'city'           => 'Faisalabad',
                'week_start_day' => 1, // Monday
            ],
            [
                'name'           => 'Master Looming Complex (Unit 2)',
                'address'        => 'GT Road, Near Kamoki Industrial Area',
                'city'           => 'Gujranwala',
                'week_start_day' => 1, // Monday
            ],
            [
                'name'           => 'Sapphire Textile Facility (Unit 3)',
                'address'        => '24-KM Multan Road, Chung Industrial Sector',
                'city'           => 'Lahore',
                'week_start_day' => 1, // Monday
            ],
        ];

        $factories = [];
        foreach ($factoriesData as $fData) {
            $factories[] = Factory::updateOrCreate(
                ['name' => $fData['name']],
                $fData
            );
        }

        // ── 3. USERS SETUP (Owner, Manager, Employee all password: 123456) ───
        $defaultPassword = Hash::make('123456');

        // A. OWNERS
        $ownerUser1 = User::updateOrCreate(
            ['email' => 'own@gmail.com'],
            [
                'name'             => 'Haji Muhammad Imran (Owner)',
                'phone_no'         => '0300-1234567',
                'cnic'             => '33100-1234567-1',
                'address'          => 'Civil Lines, Faisalabad',
                'employee_details' => 'Executive Managing Director & Chairman',
                'password'         => $defaultPassword,
                'factory_id'       => $factories[0]->id,
            ]
        );
        $ownerUser1->syncRoles(['owner']);

        $ownerUser2 = User::updateOrCreate(
            ['email' => 'owner@example.com'],
            [
                'name'             => 'Ali Imran (Managing Director)',
                'phone_no'         => '0300-7654321',
                'cnic'             => '33100-7654321-2',
                'address'          => 'Gulberg III, Lahore',
                'employee_details' => 'Co-Owner & Operations Lead',
                'password'         => $defaultPassword,
                'factory_id'       => $factories[0]->id,
            ]
        );
        $ownerUser2->syncRoles(['owner']);

        // B. MANAGERS
        $managerUser1 = User::updateOrCreate(
            ['email' => 'man@gmail.com'],
            [
                'name'             => 'Tariq Mehmood (Shift Manager)',
                'phone_no'         => '0301-8889991',
                'cnic'             => '33102-3344556-3',
                'address'          => 'Peoples Colony, Faisalabad',
                'employee_details' => 'Head of Weaving & Production (Unit 1)',
                'password'         => $defaultPassword,
                'factory_id'       => $factories[0]->id,
            ]
        );
        $managerUser1->syncRoles(['manager']);

        $managerUser2 = User::updateOrCreate(
            ['email' => 'manager@example.com'],
            [
                'name'             => 'Bilal Hassan (Production Manager)',
                'phone_no'         => '0302-7776662',
                'cnic'             => '34101-9988776-5',
                'address'          => 'Model Town, Gujranwala',
                'employee_details' => 'Plant Manager (Unit 2)',
                'password'         => $defaultPassword,
                'factory_id'       => $factories[1]->id,
            ]
        );
        $managerUser2->syncRoles(['manager']);

        // Link manager to factories
        $factories[0]->update(['manager_id' => $managerUser1->id]);
        $factories[1]->update(['manager_id' => $managerUser2->id]);
        $factories[2]->update(['manager_id' => $managerUser1->id]);

        // C. EMPLOYEES (Loom Operators)
        $employeesSeed = [
            [
                'email'   => 'emp@gmail.com',
                'name'    => 'Zain-Ul-Abdeen (Master Weaver)',
                'phone'   => '0303-1112233',
                'cnic'    => '33100-9876543-1',
                'address' => 'Ghulam Muhammad Abad, Faisalabad',
                'factory' => $factories[0],
                'emp_id'  => 'EMP-1001',
                'start'   => '08:00:00',
                'end'     => '16:00:00',
            ],
            [
                'email'   => 'employee@example.com',
                'name'    => 'Usman Ghani (Airjet Operator)',
                'phone'   => '0304-4445566',
                'cnic'    => '33100-4445566-2',
                'address' => 'Samanabad, Faisalabad',
                'factory' => $factories[0],
                'emp_id'  => 'EMP-1002',
                'start'   => '08:00:00',
                'end'     => '16:00:00',
            ],
            [
                'email'   => 'ahmad@employee.com',
                'name'    => 'Ahmad Raza (Senior Loom Operator)',
                'phone'   => '0305-5556677',
                'cnic'    => '33100-5556677-3',
                'address' => 'D-Type Colony, Faisalabad',
                'factory' => $factories[0],
                'emp_id'  => 'EMP-1003',
                'start'   => '16:00:00',
                'end'     => '00:00:00',
            ],
            [
                'email'   => 'hamza@employee.com',
                'name'    => 'Hamza Tariq (Rapier Specialist)',
                'phone'   => '0306-6667788',
                'cnic'    => '34101-6667788-4',
                'address' => 'Kamoki Bypass, Gujranwala',
                'factory' => $factories[1],
                'emp_id'  => 'EMP-2001',
                'start'   => '08:00:00',
                'end'     => '16:00:00',
            ],
            [
                'email'   => 'rashid@employee.com',
                'name'    => 'Rashid Ali (Loom Technician)',
                'phone'   => '0307-7778899',
                'cnic'    => '34101-7778899-5',
                'address' => 'Wapda Town, Gujranwala',
                'factory' => $factories[1],
                'emp_id'  => 'EMP-2002',
                'start'   => '16:00:00',
                'end'     => '00:00:00',
            ],
            [
                'email'   => 'kashif@employee.com',
                'name'    => 'Kashif Mehmood (Waterjet Operator)',
                'phone'   => '0308-8889900',
                'cnic'    => '35201-8889900-6',
                'address' => 'Chung Phatak, Lahore',
                'factory' => $factories[2],
                'emp_id'  => 'EMP-3001',
                'start'   => '08:00:00',
                'end'     => '16:00:00',
            ],
        ];

        $employeeRecords = [];
        foreach ($employeesSeed as $emp) {
            $user = User::updateOrCreate(
                ['email' => $emp['email']],
                [
                    'name'             => $emp['name'],
                    'phone_no'         => $emp['phone'],
                    'cnic'             => $emp['cnic'],
                    'address'          => $emp['address'],
                    'employee_details' => 'Certified Textile Machine Operator',
                    'password'         => $defaultPassword,
                    'factory_id'       => $emp['factory']->id,
                ]
            );
            $user->syncRoles(['employee']);

            $employeeModel = Employee::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'employee_id'     => $emp['emp_id'],
                    'factory_id'      => $emp['factory']->id,
                    'shift_starttime' => $emp['start'],
                    'shift_endtime'   => $emp['end'],
                    'timestamp'       => now(),
                ]
            );
            $employeeRecords[] = [
                'user'     => $user,
                'employee' => $employeeModel,
                'factory'  => $emp['factory'],
            ];
        }

        // ── 4. MACHINES (LOOMS) SETUP ─────────────────────────────────────────
        $machineTypes = [
            ['name' => 'Loom-A1 (Tsudakoma ZAX-9100)', 'type' => 'Airjet Loom'],
            ['name' => 'Loom-A2 (Toyota JAT-810)',      'type' => 'Airjet Loom'],
            ['name' => 'Loom-B1 (Picanol OmniPlus)',    'type' => 'Airjet Loom'],
            ['name' => 'Loom-B2 (Sulzer G6300)',        'type' => 'Rapier Loom'],
            ['name' => 'Loom-C1 (Itema R9500)',         'type' => 'Rapier Loom'],
            ['name' => 'Loom-C2 (Dornier A1)',          'type' => 'Airjet Loom'],
            ['name' => 'Loom-D1 (Tsudakoma ZAX-e)',     'type' => 'Airjet Loom'],
            ['name' => 'Loom-D2 (Waterjet W800)',       'type' => 'Waterjet Loom'],
        ];

        $allMachinesByFactory = [];
        foreach ($factories as $fIndex => $factory) {
            $allMachinesByFactory[$factory->id] = [];
            foreach ($machineTypes as $mIndex => $mDef) {
                $code = 'F' . ($fIndex + 1) . '-' . $mDef['name'];
                $machine = Machine::updateOrCreate(
                    [
                        'factory_id'   => $factory->id,
                        'machine_name' => $code,
                    ],
                    [
                        'machine_type' => $mDef['type'],
                        'status'       => 'active',
                        'time'         => now(),
                    ]
                );
                $allMachinesByFactory[$factory->id][] = $machine;
            }
        }

        // ── 5. PRODUCTIONS & BATCHES SETUP ────────────────────────────────────
        $varieties = [
            ['type' => 'Cotton Lawn 80/80',      'rate' => 18.50, 'target' => 1200],
            ['type' => 'Denim Twill 3/1 Heavy',  'rate' => 28.00, 'target' => 900],
            ['type' => 'Organic Poplin 60/60',   'rate' => 22.00, 'target' => 1500],
            ['type' => 'Polyester Georgette 50D','rate' => 14.00, 'target' => 1800],
            ['type' => 'Silk Satin Jacquard',    'rate' => 45.00, 'target' => 600],
            ['type' => 'Linen Oxford Weave',     'rate' => 32.00, 'target' => 1000],
        ];

        // Production statuses:
        // 1 = In Progress / Assigned
        // 2 = Submitted by Operator (Pending Manager)
        // 3 = Manager Approved (Pending Owner)
        // 4 = Owner Approved (Completed & Payable)
        // 5 = Rejected / Rework

        $now = Carbon::now();
        $batchCounter = 100;
        $createdApprovedProductions = [];

        foreach ($employeeRecords as $empIndex => $empBundle) {
            $empModel   = $empBundle['employee'];
            $factory    = $empBundle['factory'];
            $factoryMachines = $allMachinesByFactory[$factory->id];
            $managerUser = ($factory->id === $factories[1]->id) ? $managerUser2 : $managerUser1;

            // Generate 8-12 production batches spread across time windows (Today, This Week, Last Week, Last Month)
            for ($b = 0; $b < 8; $b++) {
                $batchCounter++;
                $variety = $varieties[($empIndex + $b) % count($varieties)];
                $machine = $factoryMachines[$b % count($factoryMachines)];

                // Date distribution
                if ($b === 0) {
                    // Today
                    $date = $now->copy();
                    $status = 2; // Submitted today
                    $ready = (int) ($variety['target'] * 0.45);
                    $waste = 12.0;
                } elseif ($b === 1) {
                    // Today in progress
                    $date = $now->copy();
                    $status = 1; // In progress
                    $ready = (int) ($variety['target'] * 0.20);
                    $waste = 5.0;
                } elseif ($b === 2) {
                    // This week - pending owner
                    $date = $now->copy()->subDays(2);
                    $status = 3; // Manager approved
                    $ready = (int) ($variety['target'] * 0.92);
                    $waste = 25.0;
                } elseif ($b === 3 || $b === 4) {
                    // This week - completed & owner approved
                    $date = $now->copy()->subDays(3 + $b);
                    $status = 4; // Owner approved
                    $ready = (int) ($variety['target'] * 0.95);
                    $waste = 30.0;
                } elseif ($b === 5 || $b === 6) {
                    // Previous week - owner approved
                    $date = $now->copy()->subWeeks(1)->subDays($b - 4);
                    $status = 4; // Owner approved
                    $ready = (int) ($variety['target'] * 0.96);
                    $waste = 35.0;
                } else {
                    // Previous month
                    $date = $now->copy()->subMonth()->addDays($b * 2);
                    $status = 4; // Owner approved
                    $ready = (int) ($variety['target'] * 0.94);
                    $waste = 40.0;
                }

                $totalLength = (float) $variety['target'];
                $rate = (float) $variety['rate'];
                $earnedAmount = ($status === 4) ? ($ready * $rate) : 0.0;
                $remaining = max(0, $totalLength - $ready - $waste);

                $production = Production::updateOrCreate(
                    ['batch_id' => 'BATCH-' . str_pad($batchCounter, 5, '0', STR_PAD_LEFT)],
                    [
                        'variety_type'     => $variety['type'],
                        'total_length'     => $totalLength,
                        'amount_per_meter' => $rate,
                        'select_days'      => $date->format('l'),
                        'ready_production' => $ready,
                        'waste_production' => $waste,
                        'remaining'        => $remaining,
                        'alert_threshold'  => 100,
                        'alert_sent'       => 0,
                        'machine_id'       => $machine->id,
                        'employee_id'      => $empModel->id,
                        'manager_id'       => $managerUser->id,
                        'factory_id'       => $factory->id,
                        'shift_start'      => $date->copy()->setTime(8, 0, 0),
                        'shift_end'        => $date->copy()->setTime(16, 0, 0),
                        'status'           => $status,
                        'earned_amount'    => $earnedAmount,
                        'created_at'       => $date,
                        'updated_at'       => $date,
                    ]
                );

                if ($status === 4) {
                    $createdApprovedProductions[] = [
                        'production' => $production,
                        'employee'   => $empModel,
                        'earned'     => $earnedAmount,
                    ];
                }
            }
        }

        // ── 6. PAYMENTS SETUP ─────────────────────────────────────────────────
        // Disburse realistic partial and full payouts for approved batches
        foreach ($createdApprovedProductions as $idx => $item) {
            $prod   = $item['production'];
            $emp    = $item['employee'];
            $earned = $item['earned'];

            // Every alternate approved batch receives a payment record
            if ($idx % 2 === 0 && $earned > 0) {
                // Disburse partial or full payment
                $amountToPay = ($idx % 4 === 0) ? $earned : round($earned * 0.70, 2);

                Payment::create([
                    'amount_paid'   => $amountToPay,
                    'employee_id'   => $emp->id,
                    'user_id'       => $ownerUser1->id,
                    'production_id' => $prod->id,
                    'created_at'    => $prod->created_at->copy()->addDays(1),
                    'updated_at'    => $prod->created_at->copy()->addDays(1),
                ]);
            }
        }

        // ── 7. ATTENDANCE SETUP ───────────────────────────────────────────────
        foreach ($employeeRecords as $empBundle) {
            $empModel = $empBundle['employee'];
            $factory  = $empBundle['factory'];
            $factoryMachines = $allMachinesByFactory[$factory->id];

            // 14 days of attendance history
            for ($day = 0; $day < 14; $day++) {
                $attDate = $now->copy()->subDays($day);
                $isSunday = ($attDate->dayOfWeek === Carbon::SUNDAY);
                $type = $isSunday ? 'leave' : 'present';

                Attendence::create([
                    'employee_id' => $empModel->id,
                    'machine_id'  => $factoryMachines[$day % count($factoryMachines)]->id,
                    'type'        => $type,
                    'timestamp'   => $attDate->setTime(8, rand(0, 15), 0),
                    'created_at'  => $attDate,
                    'updated_at'  => $attDate,
                ]);
            }
        }

        // ── 8. NOTIFICATIONS SETUP (If table exists) ──────────────────────────
        if (Schema::hasTable('notifications')) {
            $sampleNotifications = [
                [
                    'user_id'   => $ownerUser1->id,
                    'sender_id' => $managerUser1->id,
                    'title'     => 'Production Batch Pending Review',
                    'message'   => 'Shift Manager Tariq approved BATCH-00103 for Cotton Lawn. Waiting for your final approval.',
                    'type'      => 'production_approval',
                    'is_read'   => false,
                ],
                [
                    'user_id'   => $ownerUser1->id,
                    'sender_id' => $managerUser1->id,
                    'title'     => 'Weekly Production Target Reached',
                    'message'   => 'Unit 1 has completed 96.4% of weekly scheduled yardage with minimal yarn waste.',
                    'type'      => 'target_reached',
                    'is_read'   => true,
                ],
                [
                    'user_id'   => $managerUser1->id,
                    'sender_id' => $employeeRecords[0]['user']->id,
                    'title'     => 'Shift Output Submitted',
                    'message'   => 'Master Weaver Zain logged 540 meters on Loom-A1 for Denim Twill.',
                    'type'      => 'shift_log',
                    'is_read'   => false,
                ],
                [
                    'user_id'   => $employeeRecords[0]['user']->id,
                    'sender_id' => $ownerUser1->id,
                    'title'     => 'Payment Credited',
                    'message'   => 'Your wage disbursement for BATCH-00104 has been approved and processed.',
                    'type'      => 'payment_disbursed',
                    'is_read'   => false,
                ],
            ];

            foreach ($sampleNotifications as $nData) {
                Notification::create($nData);
            }
        }
    }
}