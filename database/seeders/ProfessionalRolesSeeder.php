<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;

class ProfessionalRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $this->seedPermissions();
        $this->seedInternalRoles();
        $this->seedExternalRoles();
    }

    /**
     * Seed all granular permissions across 10 groups.
     */
    protected function seedPermissions()
    {
        $permissions = [
            // ── Users ────────────────────────────────
            ['name' => 'user.read',           'group' => 'users',     'title' => 'View Users'],
            ['name' => 'user.create',         'group' => 'users',     'title' => 'Create Users'],
            ['name' => 'user.edit',           'group' => 'users',     'title' => 'Edit Users'],
            ['name' => 'user.delete',         'group' => 'users',     'title' => 'Delete Users'],
            ['name' => 'user.suspend',        'group' => 'users',     'title' => 'Suspend Users'],
            ['name' => 'user.verify',         'group' => 'users',     'title' => 'Verify Users'],
            ['name' => 'user.reset-password', 'group' => 'users',     'title' => 'Reset Passwords'],
            ['name' => 'user.impersonate',    'group' => 'users',     'title' => 'Impersonate Users'],

            // ── Roles & Permissions ──────────────────
            ['name' => 'role.manage',         'group' => 'roles',     'title' => 'Manage Roles'],
            ['name' => 'permission.manage',   'group' => 'roles',     'title' => 'Manage Permissions'],

            // ── Content ──────────────────────────────
            ['name' => 'content.create',      'group' => 'content',   'title' => 'Create Content'],
            ['name' => 'content.edit',        'group' => 'content',   'title' => 'Edit Content'],
            ['name' => 'content.delete',      'group' => 'content',   'title' => 'Delete Content'],
            ['name' => 'content.moderate',    'group' => 'content',   'title' => 'Moderate Content'],
            ['name' => 'content.approve',     'group' => 'content',   'title' => 'Approve Content'],

            // ── Billing / Finance ────────────────────
            ['name' => 'billing.view-revenue',     'group' => 'billing', 'title' => 'View Revenue'],
            ['name' => 'billing.refund',           'group' => 'billing', 'title' => 'Process Refunds'],
            ['name' => 'billing.manage-invoices',  'group' => 'billing', 'title' => 'Manage Invoices'],
            ['name' => 'billing.manage-disputes',  'group' => 'billing', 'title' => 'Handle Disputes'],
            ['name' => 'billing.manage-plans',     'group' => 'billing', 'title' => 'Manage Plans & Pricing'],

            // ── System ───────────────────────────────
            ['name' => 'system.settings',          'group' => 'system',  'title' => 'Manage Settings'],
            ['name' => 'system.security-policies', 'group' => 'system',  'title' => 'Security Policies'],
            ['name' => 'system.feature-flags',     'group' => 'system',  'title' => 'Feature Flags'],
            ['name' => 'system.infrastructure',    'group' => 'system',  'title' => 'Infrastructure Access'],
            ['name' => 'system.delete-data',       'group' => 'system',  'title' => 'Delete System Data'],
            ['name' => 'system.restore-data',      'group' => 'system',  'title' => 'Restore System Data'],

            // ── Analytics ────────────────────────────
            ['name' => 'analytics.view',      'group' => 'analytics', 'title' => 'View Analytics'],
            ['name' => 'analytics.export',    'group' => 'analytics', 'title' => 'Export Data'],

            // ── Tools ────────────────────────────────
            ['name' => 'tool.manage',         'group' => 'tools',     'title' => 'Manage Tools'],
            ['name' => 'tool.create',         'group' => 'tools',     'title' => 'Create Tools'],
            ['name' => 'tool.edit',           'group' => 'tools',     'title' => 'Edit Tools'],
            ['name' => 'tool.delete',         'group' => 'tools',     'title' => 'Delete Tools'],

            // ── Advertisements ───────────────────────
            ['name' => 'ad.manage',           'group' => 'ads',       'title' => 'Manage Ads'],
            ['name' => 'ad.create',           'group' => 'ads',       'title' => 'Create Ads'],
            ['name' => 'ad.edit',             'group' => 'ads',       'title' => 'Edit Ads'],
            ['name' => 'ad.delete',           'group' => 'ads',       'title' => 'Delete Ads'],

            // ── Feedback ─────────────────────────────
            ['name' => 'feedback.view',       'group' => 'feedback',  'title' => 'View Feedback'],
            ['name' => 'feedback.respond',    'group' => 'feedback',  'title' => 'Respond to Feedback'],
            ['name' => 'feedback.delete',     'group' => 'feedback',  'title' => 'Delete Feedback'],

            // ── Developer ────────────────────────────
            ['name' => 'dev.access-logs',      'group' => 'developer', 'title' => 'Access Logs'],
            ['name' => 'dev.error-monitoring', 'group' => 'developer', 'title' => 'Error Monitoring'],
            ['name' => 'dev.api-keys',         'group' => 'developer', 'title' => 'API Keys Management'],
            ['name' => 'dev.feature-flags',    'group' => 'developer', 'title' => 'Developer Feature Flags'],
        ];

        foreach ($permissions as $p) {
            // Admin guard
            Permission::firstOrCreate(
                ['name' => $p['name'], 'guard_name' => 'admin'],
                ['group' => $p['group'], 'title' => $p['title']]
            );
            // Web guard
            Permission::firstOrCreate(
                ['name' => $p['name'], 'guard_name' => 'web'],
                ['group' => $p['group'], 'title' => $p['title']]
            );
        }
    }

    /**
     * Seed internal (admin-guard) roles with their permission mappings.
     */
    protected function seedInternalRoles()
    {
        $roles = [
            'Super Admin' => [
                'permissions'  => ['*'],
                'description'  => 'Full system control. Billing, infrastructure, security, and role management. Max 1 person.',
            ],
            'Admin' => [
                'permissions' => [
                    'user.read', 'user.create', 'user.edit', 'user.suspend', 'user.verify', 'user.reset-password',
                    'content.create', 'content.edit', 'content.delete', 'content.moderate', 'content.approve',
                    'tool.manage', 'tool.create', 'tool.edit', 'tool.delete',
                    'ad.manage', 'ad.create', 'ad.edit', 'ad.delete',
                    'feedback.view', 'feedback.respond', 'feedback.delete',
                    'analytics.view', 'analytics.export',
                    'billing.manage-plans',
                ],
                'description' => 'Operations & management. User management, content moderation, tools, ads, and analytics. No infrastructure or security access.',
            ],
            'Moderator' => [
                'permissions' => [
                    'content.moderate', 'content.approve', 'content.delete',
                    'user.suspend',
                    'feedback.view', 'feedback.respond',
                ],
                'description' => 'Content moderation. Review flagged content, suspend users, and approve/reject reports.',
            ],
            'Support' => [
                'permissions' => [
                    'user.read', 'user.reset-password', 'user.impersonate',
                    'feedback.view', 'feedback.respond',
                    'billing.view-revenue',
                ],
                'description' => 'Customer success. View accounts, reset passwords, view-only impersonation, and handle feedback. No role changes or deletions.',
            ],
            'Finance Admin' => [
                'permissions' => [
                    'billing.view-revenue', 'billing.refund', 'billing.manage-invoices', 'billing.manage-disputes', 'billing.manage-plans',
                    'analytics.view', 'analytics.export',
                ],
                'description' => 'Financial operations. Revenue, refunds, invoices, disputes. No user management or system access. Separation prevents internal fraud.',
            ],
            'Developer' => [
                'permissions' => [
                    'dev.access-logs', 'dev.error-monitoring', 'dev.api-keys', 'dev.feature-flags',
                    'tool.manage', 'tool.create', 'tool.edit',
                    'analytics.view',
                ],
                'description' => 'Product engineering. Logs, error monitoring, feature flags, API keys. No user data editing.',
            ],
        ];

        foreach ($roles as $name => $data) {
            $role = Role::firstOrCreate(
                ['name' => $name, 'guard_name' => 'admin'],
                ['description' => $data['description']]
            );

            if ($data['permissions'] === ['*']) {
                $role->syncPermissions(Permission::where('guard_name', 'admin')->get());
            } else {
                $role->syncPermissions(
                    Permission::whereIn('name', $data['permissions'])
                        ->where('guard_name', 'admin')
                        ->get()
                );
            }
        }
    }

    /**
     * Seed external (web-guard) roles for frontend user classification.
     */
    protected function seedExternalRoles()
    {
        $roles = [
            'User (Free)' => [
                'description' => 'Free-tier user. Basic access with usage limits.',
            ],
            'User (Paid)' => [
                'description' => 'Paid subscriber (Classic, Plus, or Pro). Enhanced limits and features.',
            ],
            'Enterprise Owner' => [
                'description' => 'Organization account owner. Manages team members and billing for their company.',
            ],
            'Enterprise Member' => [
                'description' => 'Member of an enterprise organization. Access determined by team admin.',
            ],
        ];

        foreach ($roles as $name => $data) {
            Role::firstOrCreate(
                ['name' => $name, 'guard_name' => 'web'],
                ['description' => $data['description']]
            );
        }
    }
}
