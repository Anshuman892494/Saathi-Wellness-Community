<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * DatabaseSeeder — seeds sample users, posts, and comments for development.
 */
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Create sample users ─────────────────────────────────────────────
        $users = [
            [
                'name'      => 'Sarah Mitchell',
                'email'     => 'sarah@saathi.com',
                'password'  => Hash::make('password'),
                'bio'       => 'Yoga instructor & mindfulness advocate. Sharing what works for me. 🧘',
                'bookmarks' => [],
            ],
            [
                'name'      => 'James Okafor',
                'email'     => 'james@saathi.com',
                'password'  => Hash::make('password'),
                'bio'       => 'Marathon runner | Plant-based nutrition enthusiast 🌱',
                'bookmarks' => [],
            ],
            [
                'name'      => 'Priya Sharma',
                'email'     => 'priya@saathi.com',
                'password'  => Hash::make('password'),
                'bio'       => 'Mental health advocate & certified wellness coach. Here to support you. 💚',
                'bookmarks' => [],
            ],
        ];

        $createdUsers = [];
        foreach ($users as $userData) {
            // Avoid duplicates on re-seed
            $user = User::firstOrCreate(['email' => $userData['email']], $userData);
            $createdUsers[] = $user;
        }

        // ── Create sample posts ─────────────────────────────────────────────
        $posts = [
            [
                'user'     => $createdUsers[0],
                'title'    => 'How 10 Minutes of Morning Yoga Changed My Life',
                'category' => 'meditation',
                'tags'     => ['yoga', 'morning-routine', 'flexibility'],
                'content'  => "I used to tell myself I didn't have time for yoga. I was wrong.\n\nThree months ago, I committed to just 10 minutes every morning before checking my phone. Sun salutations, a few hip openers, and a minute of quiet breathing.\n\nThe results have been remarkable:\n- My back pain (from sitting at a desk all day) has almost completely disappeared\n- I fall asleep faster at night\n- My mood in the mornings is noticeably better\n- I feel more patient throughout the day\n\nThe secret? I don't wait until I 'feel like it'. I roll out my mat the night before so there's no friction in the morning. Those 10 minutes are now the most important part of my day.\n\nIf you're thinking about starting yoga, don't wait for the perfect moment. Start with 5 minutes. Your future self will thank you.",
            ],
            [
                'user'     => $createdUsers[1],
                'title'    => 'My Plant-Based Journey: 6 Months In',
                'category' => 'nutrition',
                'tags'     => ['plant-based', 'vegan', 'nutrition'],
                'content'  => "Six months ago, I decided to shift to a predominantly plant-based diet. Here's my honest review.\n\nWhat improved:\n✅ My energy levels are more stable throughout the day\n✅ Digestion has dramatically improved\n✅ My marathon recovery times got noticeably faster\n✅ Cholesterol levels dropped 18% (confirmed by blood test)\n✅ I discovered so many foods I'd never tried before!\n\nChallenges:\n⚠️ Meal prep takes more thought, especially for protein\n⚠️ Eating out can be tricky in some places\n⚠️ Had to supplement B12 and Vitamin D\n\nMy key lesson: it doesn't have to be all-or-nothing. Even replacing 3–4 meat-based meals per week with plant-based alternatives makes a significant difference. Progress over perfection.\n\nHappy to answer any questions in the comments!",
            ],
            [
                'user'     => $createdUsers[2],
                'title'    => 'Breaking the Stigma: My Experience with Therapy',
                'category' => 'mental-health',
                'tags'     => ['mental-health', 'therapy', 'self-care'],
                'content'  => "It took me 27 years to walk into a therapist's office. I want to talk about why that took so long — and why I wish I'd gone sooner.\n\nGrowing up, mental health was simply not talked about. Feeling low was 'weakness'. Seeking help was something 'other people' did.\n\nWhat changed? A close friend's honest conversation about their own therapy experience. It made me realise that asking for help is one of the bravest things a person can do.\n\nMy first session was terrifying. But my therapist asked thoughtful questions and, for the first time, I felt truly heard.\n\nSix months of weekly sessions later:\n- I understand my emotional triggers far better\n- My relationships have improved because I communicate more openly\n- I have tools to manage anxiety that actually work\n- I feel more like myself than I have in years\n\nIf you're on the fence, I encourage you with my whole heart: reach out. You deserve support. 💚",
            ],
            [
                'user'     => $createdUsers[0],
                'title'    => '5 Sleep Hygiene Habits That Actually Work',
                'category' => 'general',
                'tags'     => ['sleep', 'rest', 'recovery'],
                'content'  => "After years of poor sleep, I finally cracked the code. Here are the 5 habits that made the biggest difference:\n\n1. No screens 60 minutes before bed. I replaced scrolling with reading. Game-changer.\n\n2. Same wake time every day — including weekends. Your circadian rhythm loves consistency.\n\n3. Keep the room cool. Research suggests 16–19°C is optimal for sleep quality.\n\n4. No caffeine after 12pm. Caffeine has a 5–7 hour half-life. That 3pm coffee is still affecting you at 9pm.\n\n5. A 10-minute wind-down ritual. Mine is: herbal tea, gentle stretching, gratitude journaling.\n\nThe biggest shift was accepting that sleep is productive. You're not 'wasting time' sleeping — you're repairing your body, consolidating memories, and regulating emotions. Prioritise it.",
            ],
            [
                'user'     => $createdUsers[1],
                'title'    => 'The Beginner\'s Complete Guide to Running',
                'category' => 'fitness',
                'tags'     => ['running', 'beginner', 'cardio'],
                'content'  => "Everyone starts somewhere. When I ran my first 'lap' around the block 4 years ago, I had to stop and catch my breath after 200 metres. Last month, I finished my first marathon. Here's what I learned:\n\nWeek 1–4: Walk-Run intervals\nAlternate 1 min running with 2 min walking for 20 minutes, 3×/week. Don't worry about speed — just finish.\n\nWeek 5–8: Build base\nGradually extend running intervals. By week 8, aim to run 20 minutes without stopping.\n\nThe golden rules:\n- The 10% rule: never increase weekly mileage by more than 10%\n- Easy runs should feel genuinely easy (you can hold a conversation)\n- Invest in proper running shoes — this prevents most injuries\n- Rest days are part of the programme, not cheating\n\nThe mental game is real. On tough days, tell yourself: 'I just need to get to the end of the street.' Often, that's enough to keep going.",
            ],
        ];

        $createdPosts = [];
        foreach ($posts as $postData) {
            $user = $postData['user'];
            unset($postData['user']);

            $post = Post::create(array_merge($postData, [
                'user_id' => (string) $user->_id,
                'likes'   => [],
                'views'   => rand(10, 200),
            ]));
            $createdPosts[] = ['post' => $post, 'user' => $user];
        }

        // ── Create sample comments ──────────────────────────────────────────
        $sampleComments = [
            "This really resonated with me. Thank you for sharing! 💚",
            "I've been thinking about trying this — your post convinced me to give it a go.",
            "Such an important topic. More people need to talk about this.",
            "Great tips! I've been implementing #2 for a month and already feel different.",
            "This is exactly what I needed to read today. Bookmarking this!",
            "How long did it take before you noticed a difference?",
            "I had a similar experience. It really does work!",
        ];

        foreach ($createdPosts as $i => $item) {
            $commentCount = rand(1, 3);
            for ($j = 0; $j < $commentCount; $j++) {
                $commenter = $createdUsers[($i + $j + 1) % count($createdUsers)];
                Comment::create([
                    'post_id' => (string) $item['post']->_id,
                    'user_id' => (string) $commenter->_id,
                    'comment' => $sampleComments[array_rand($sampleComments)],
                ]);
            }
        }

        $this->command->info('✅ Seeded ' . count($createdUsers) . ' users, ' . count($createdPosts) . ' posts, and sample comments.');
        $this->command->info('   Login with: sarah@saathi.com / password');
    }
}
