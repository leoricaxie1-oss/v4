<?php

namespace Database\Seeders;

use App\Models\Announcement;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AnnouncementSeeder extends Seeder
{
    public function run(): void
    {
        $captain = User::role('captain')->first();
        if (! $captain) return;

        $samples = [
            [
                'title'    => 'Welcome to PanipOne — Your Digital Barangay',
                'body'     => 'PanipOne is the official online system of Barangay Panipuan for document requests, complaints, and community services. Register today to enjoy the convenience of online transactions.',
                'priority' => 'high',
            ],
            [
                'title'    => 'Monthly Clean-up Drive — Every First Saturday',
                'body'     => 'Join your fellow residents every first Saturday of the month for our community clean-up drive. Assembly area: Barangay Hall, 6:00 AM.',
                'priority' => 'normal',
            ],
            [
                'title'    => 'Emergency Hotlines',
                'body'     => 'Save these hotlines: Brgy. Hotline (045) 000-0000, Tanod 0917-000-0000, City Disaster Office 0917-111-1111.',
                'priority' => 'urgent',
            ],
        ];

        foreach ($samples as $a) {
            Announcement::firstOrCreate(
                ['slug' => Str::slug($a['title'])],
                array_merge($a, [
                    'author_id'    => $captain->id,
                    'is_published' => true,
                    'published_at' => now(),
                ])
            );
        }
    }
}
