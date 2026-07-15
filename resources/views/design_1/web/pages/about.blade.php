@extends('design_1.web.layouts.app')

@push('styles_top')
<style>
    @media (max-width: 1024px) {
        .ag2 { grid-template-columns: 1fr !important; gap: 48px !important; }
        .ag4 { grid-template-columns: repeat(2,1fr) !important; }
        .ag3 { grid-template-columns: 1fr !important; gap: 24px !important; }
        /* Release the sticky "Origin" heading once the grid stacks, or it
           stays pinned and overlaps the story paragraphs below it. */
        .origin-aside { position: static !important; }
    }
    @media (max-width: 640px) {
        .ag4 { grid-template-columns: 1fr !important; }
        .tl-grid { grid-template-columns: 64px 32px 1fr !important; }
        .tl-year { font-size: 36px !important; }
    }
</style>
@endpush

@section('content')
<div class="bg-[#FAFFE0] text-[#072923]">


{{-- ══════════════════════════════════════════════════════
     HERO
══════════════════════════════════════════════════════ --}}
<section style="padding-top:64px;padding-bottom:72px;">
    <div class="ez-container">

        {{-- Eyebrow --}}
        <div class="hidden md:flex" style="justify-content:space-between;align-items:center;
            font-family:'JetBrains Mono',monospace;font-size:11px;letter-spacing:0.14em;
            text-transform:uppercase;color:var(--muted);
            padding-bottom:32px;border-bottom:1px solid var(--line);margin-bottom:64px;">
            <span>—— THE COMPANY</span>
            <span>· Est. 2023 · Cairo ·</span>
            <span>STAFF Nº 12 · STUDENTS 257</span>
        </div>

        <div class="ag2" style="display:grid;grid-template-columns:1.3fr 1fr;gap:80px;align-items:end;">
            <div>
                <div style="font-family:'JetBrains Mono',monospace;font-size:12px;
                    letter-spacing:0.18em;text-transform:uppercase;color:var(--muted);margin-bottom:28px;">
                    About <span style="color:var(--ink);">·</span> elzatuna
                </div>
                <h1 class="serif" style="font-size:clamp(36px,6.5vw,88px);line-height:0.96;
                    margin:0;letter-spacing:-0.035em;font-weight:400;">
                    We're a tiny team<br>
                    building a <em style="font-style:italic;">big</em>,<br>
                    patient olive tree.
                </h1>
            </div>

            <div style="padding-bottom:12px;">
                <p class="serif" style="font-size:22px;line-height:1.38;margin:0;letter-spacing:-0.005em;">
                    <span style="color:var(--muted);">The olive grows slow.</span>
                    It takes seven years to bear fruit, sixty to grow tall, six hundred to feed a village.
                    <span style="color:var(--muted);"> So that's the timescale we chose for what we're trying to do here.</span>
                </p>

                <div style="margin-top:32px;display:grid;grid-template-columns:1fr 1fr 1fr;gap:8px;
                    border-top:1px solid var(--ink);padding-top:14px;">
                    @foreach([['Yr 02','where we are'],['Yr 07','first profit'],['Yr 60','where we\'re going']] as $s)
                    <div>
                        <div class="serif" style="font-size:28px;line-height:1;letter-spacing:-0.02em;">{{ $s[0] }}</div>
                        <div style="font-size:11px;margin-top:6px;color:var(--muted);font-family:'JetBrains Mono',monospace;
                            letter-spacing:0.08em;text-transform:uppercase;">{{ $s[1] }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Olive name strip --}}
        <div class="ez-pad-lg" style="margin-top:80px;background:var(--ink);color:var(--cream);border-radius:24px;
            padding:40px 48px;position:relative;overflow:hidden;
            display:flex;align-items:center;justify-content:space-between;">
            <div style="position:absolute;left:-20px;top:10px;opacity:0.18;">
                <svg width="360" height="220" viewBox="0 0 120 72" fill="none" aria-hidden="true">
                    <path d="M10 60 C 30 50, 60 35, 110 12" stroke="var(--citron)" stroke-width="1.5" stroke-linecap="round"/>
                    <ellipse cx="28" cy="48" rx="6" ry="3.5" transform="rotate(-30 28 48)" fill="var(--citron)"/>
                    <ellipse cx="46" cy="40" rx="6.5" ry="3.6" transform="rotate(-30 46 40)" fill="var(--citron)"/>
                    <ellipse cx="64" cy="32" rx="7" ry="3.8" transform="rotate(-30 64 32)" fill="var(--citron)"/>
                    <ellipse cx="82" cy="24" rx="6.5" ry="3.6" transform="rotate(-30 82 24)" fill="var(--citron)"/>
                </svg>
            </div>
            <div style="position:relative;z-index:1;max-width:700px;">
                <div style="font-family:'JetBrains Mono',monospace;font-size:11px;letter-spacing:0.16em;
                    text-transform:uppercase;color:var(--citron);margin-bottom:16px;">What zatuna means</div>
                <h2 class="serif" style="font-size:clamp(24px,3.8vw,52px);line-height:1.1;
                    margin:0;letter-spacing:-0.015em;">
                    <em style="font-style:italic;">"الزيتونة"</em> — <span style="color:var(--citron);">the olive</span>.
                    Patient, ancient, generous. Plants its own shade.
                </h2>
            </div>
        </div>
    </div>
</section>


{{-- ══════════════════════════════════════════════════════
     ORIGIN / OUR STORY
══════════════════════════════════════════════════════ --}}
<section style="padding:100px 0;">
    <div class="ez-container">
        <div class="ag2" style="display:grid;grid-template-columns:0.85fr 1.4fr;gap:80px;align-items:flex-start;">
            <div class="origin-aside" style="position:sticky;top:100px;">
                <div style="font-family:'JetBrains Mono',monospace;font-size:11px;letter-spacing:0.16em;
                    text-transform:uppercase;color:var(--muted);margin-bottom:24px;">—— 01 / Origin</div>
                <h2 class="serif" style="font-size:clamp(40px,5.5vw,72px);line-height:0.98;
                    margin:0;letter-spacing:-0.025em;font-weight:400;">
                    Why we<br>
                    built <em style="font-style:italic;">this</em>,<br>
                    instead of<br>
                    something<br>
                    easier.
                </h2>
            </div>

            <div style="font-family:'Instrument Serif','Playfair Display',serif;
                font-size:22px;line-height:1.5;letter-spacing:-0.005em;color:var(--ink);">
                <p style="margin:0;">
                    <span style="float:left;font-size:112px;line-height:0.85;font-style:italic;
                        color:var(--citron);padding:6px 16px 0 0;
                        font-family:'Instrument Serif','Playfair Display',serif;">E</span>l Zatuna was founded
                    with a simple yet powerful vision: to make quality education accessible to everyone, everywhere.
                    We believe that knowledge should not be limited by geographical boundaries, financial constraints,
                    or traditional educational barriers.
                </p>

                <p style="margin-top:32px;">
                    Our platform brings together expert instructors from across Egypt with ambitious students eager to
                    develop new skills and pass their exams. Through course-specific tutoring and a commitment to
                    educational excellence, we've created a learning ecosystem that adapts to
                    <span style="color:var(--citron);">each student's exact university, exact course, and exact semester.</span>
                </p>

                <p style="margin-top:32px;">
                    Today, we're proud to serve thousands of students across multiple universities, offering courses
                    carefully designed by tutors who sat through the exact same lectures — usually just one or two
                    years ago.
                </p>

                <p style="margin-top:32px;font-size:26px;font-style:italic;color:var(--ink);">
                    Course-specific tutoring, built for Egyptian universities, taught by students who finished
                    the course one or two years ago.
                </p>

                <p style="margin-top:32px;font-size:16px;color:var(--muted);
                    font-family:'Poppins','Inter',sans-serif;line-height:1.65;">
                    We're not trying to replace your professor. We're not trying to replace Khan Academy.
                    We're the thing in between — the friend who took the course last year, who remembers
                    which slides actually matter and which past papers will probably show up again.
                </p>
            </div>
        </div>
    </div>
</section>


{{-- ══════════════════════════════════════════════════════
     TIMELINE
══════════════════════════════════════════════════════ --}}
<section style="padding:40px 0 100px;">
    <div class="ez-container">
        <div class="ag2" style="display:grid;grid-template-columns:0.9fr 1.4fr;gap:80px;
            align-items:end;margin-bottom:64px;">
            <div>
                <div style="font-family:'JetBrains Mono',monospace;font-size:11px;letter-spacing:0.16em;
                    text-transform:uppercase;color:var(--muted);margin-bottom:24px;">—— 02 / Timeline</div>
                <h2 class="serif" style="font-size:clamp(44px,6.5vw,96px);line-height:0.96;
                    margin:0;letter-spacing:-0.025em;font-weight:400;">
                    Two years,<br>
                    <em style="font-style:italic;">six</em> milestones.
                </h2>
            </div>
            <p style="font-size:16px;line-height:1.55;color:var(--muted);margin:0;max-width:480px;">
                No fundraising rounds, no growth-hacking, no twitter announcements.
                Just one new course at a time, one new tutor at a time.
            </p>
        </div>

        @php
            $events = [
                ['yr'=>'2023','mo'=>'SEP','t'=>'A Google Doc','d'=>'Three friends needed help through their OS final. Notes lived in one shared doc. They passed.','tag'=>'beginning','current'=>false],
                ['yr'=>'2024','mo'=>'JAN','t'=>'First paid lesson','d'=>'A friend-of-a-friend pays for one-on-one help. The doc earns its first cup of coffee.','tag'=>'first $','current'=>false],
                ['yr'=>'2024','mo'=>'JUN','t'=>'Platform launches','d'=>'V1 built from scratch. Course-specific tutoring goes live for Cairo University students.','tag'=>'launch','current'=>false],
                ['yr'=>'2024','mo'=>'OCT','t'=>'First cohort','d'=>'12 students enroll for the Fall semester. 11 pass. The 12th switched majors.','tag'=>'cohort','current'=>false],
                ['yr'=>'2025','mo'=>'MAR','t'=>'5 universities','d'=>'Coverage expands from Cairo Uni to AUC, Ain Shams, Alexandria, and GUC. 7 tutors on the roster.','tag'=>'growth','current'=>false],
                ['yr'=>'2025','mo'=>'SEP','t'=>'12 universities · 257 students','d'=>'Where we are now. Three new courses launching every month. Slow and steady.','tag'=>'today','current'=>true],
            ];
        @endphp

        <div style="position:relative;">
            {{-- vertical rail --}}
            <div style="position:absolute;left:calc(120px + 16px);top:12px;bottom:12px;
                width:1px;background:var(--line);"></div>

            @foreach($events as $i => $e)
            <div class="tl-grid" style="display:grid;grid-template-columns:120px 40px 1fr;
                gap:0;padding:32px 0;
                {{ $i < count($events)-1 ? 'border-bottom:1px solid var(--line);' : '' }}
                align-items:flex-start;">
                <div>
                    <div class="serif tl-year" style="font-size:52px;line-height:0.9;letter-spacing:-0.02em;">{{ $e['yr'] }}</div>
                    <div style="font-family:'JetBrains Mono',monospace;font-size:11px;
                        letter-spacing:0.14em;color:var(--muted);margin-top:6px;">{{ $e['mo'] }}</div>
                </div>

                <div style="display:flex;justify-content:center;padding-top:18px;">
                    <div style="width:14px;height:14px;border-radius:50%;
                        background:{{ $e['current'] ? 'var(--citron)' : 'var(--cream)' }};
                        border:2px solid var(--ink);position:relative;z-index:1;
                        box-shadow:{{ $e['current'] ? '0 0 0 6px rgba(200,205,6,0.2)' : 'none' }};"></div>
                </div>

                <div style="padding-left:32px;">
                    <div style="display:inline-block;font-family:'JetBrains Mono',monospace;font-size:10px;
                        letter-spacing:0.14em;text-transform:uppercase;
                        background:{{ $e['current'] ? 'var(--citron)' : 'transparent' }};
                        border:{{ $e['current'] ? 'none' : '1px solid var(--line)' }};
                        color:{{ $e['current'] ? 'var(--ink)' : 'var(--muted)' }};
                        padding:5px 10px;border-radius:999px;font-weight:600;margin-bottom:14px;">{{ $e['tag'] }}</div>
                    <h3 class="serif" style="font-size:30px;line-height:1.1;margin:0;
                        letter-spacing:-0.015em;font-weight:400;">{{ $e['t'] }}</h3>
                    <p style="margin:12px 0 0;font-size:16px;line-height:1.55;
                        color:var(--muted);max-width:600px;">{{ $e['d'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>


{{-- ══════════════════════════════════════════════════════
     BY THE NUMBERS
══════════════════════════════════════════════════════ --}}
<section style="padding:40px 0 100px;">
    <div class="ez-container">
        <div style="margin-bottom:56px;">
            <div style="font-family:'JetBrains Mono',monospace;font-size:11px;letter-spacing:0.16em;
                text-transform:uppercase;color:var(--muted);margin-bottom:24px;">—— 03 / By the numbers</div>
            <h2 class="serif" style="font-size:clamp(44px,6.5vw,96px);line-height:0.96;
                margin:0;letter-spacing:-0.025em;max-width:900px;font-weight:400;">
                We don't have <em style="font-style:italic;">millions</em> of<br>
                users. We have these.
            </h2>
        </div>

        @php
            $stats = [
                ['n'=>'2,500+','sub'=>'active students','t'=>'cream'],
                ['n'=>'48','sub'=>'courses live','t'=>'leaf'],
                ['n'=>'32','sub'=>'tutors on the roster','t'=>'citron'],
                ['n'=>'12','sub'=>'universities covered','t'=>'cream'],
                ['n'=>'94%','sub'=>'first-time pass rate','t'=>'leaf'],
                ['n'=>'48h','sub'=>'avg. tutor reply','t'=>'citron'],
                ['n'=>'$240k','sub'=>'saved by students this year','t'=>'cream'],
                ['n'=>'4.9','sub'=>'/ 5 average rating','t'=>'leaf'],
            ];
        @endphp
        <div class="ag4" style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px;">
            @foreach($stats as $i => $s)
            @php
                $bg = $s['t'] === 'cream' ? 'var(--cream)' : ($s['t'] === 'leaf' ? '#d4edda' : 'var(--citron)');
                $border = $s['t'] !== 'citron' ? 'border:1px solid var(--line);' : '';
            @endphp
            <div style="background:{{ $bg }};{{ $border }}border-radius:20px;padding:28px;min-height:200px;
                display:flex;flex-direction:column;justify-content:space-between;">
                <div style="font-family:'JetBrains Mono',monospace;font-size:10px;
                    letter-spacing:0.14em;color:var(--ink);opacity:0.55;">
                    {{ str_pad($i+1, 2, '0', STR_PAD_LEFT) }} / {{ str_pad(count($stats), 2, '0', STR_PAD_LEFT) }}
                </div>
                <div>
                    <div class="serif" style="font-size:64px;line-height:0.85;letter-spacing:-0.03em;color:var(--ink);">{{ $s['n'] }}</div>
                    <div style="margin-top:14px;font-size:13px;color:var(--ink);
                        font-family:'JetBrains Mono',monospace;letter-spacing:0.06em;text-transform:uppercase;">{{ $s['sub'] }}</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>


{{-- ══════════════════════════════════════════════════════
     PRINCIPLES / WHAT WE BELIEVE
══════════════════════════════════════════════════════ --}}
<section style="padding:40px 0 100px;">
    <div class="ez-container">
        <div class="ag2" style="display:grid;grid-template-columns:0.9fr 1.4fr;gap:80px;
            align-items:end;margin-bottom:56px;">
            <div>
                <div style="font-family:'JetBrains Mono',monospace;font-size:11px;letter-spacing:0.16em;
                    text-transform:uppercase;color:var(--muted);margin-bottom:24px;">—— 04 / What we believe</div>
                <h2 class="serif" style="font-size:clamp(44px,6.5vw,96px);line-height:0.96;
                    margin:0;letter-spacing:-0.025em;font-weight:400;">
                    Six things<br>
                    we won't<br>
                    <em style="font-style:italic;">change</em>.
                </h2>
            </div>
            <p style="font-size:16px;line-height:1.55;color:var(--muted);margin:0;max-width:460px;">
                These are the rules we believe in. If we ever break one,
                it's probably time to start a new company.
            </p>
        </div>

        @php
            $principles = [
                ['n'=>'01','t'=>'Quality first.','d'=>'Expert-curated courses designed to deliver practical knowledge. Each course is reviewed by tutors who actually took it.'],
                ['n'=>'02','t'=>'Specific beats generic.','d'=>'A course called "Operating Systems" is useless. A course called "CSE 305 at Cairo Uni with Dr. Mansour, Fall semester" is what students need.'],
                ['n'=>'03','t'=>'The tutor is the product.','d'=>'Slides, videos, and PDFs are accessories. The thing that matters is the human at the other end — someone who passed your exact course recently.'],
                ['n'=>'04','t'=>'Flexible learning, real commitment.','d'=>'Learn at your own pace, on your own schedule. But we\'re here when you need us — not a pre-recorded video with no one to answer your questions.'],
                ['n'=>'05','t'=>'Community matters.','d'=>'Join a community of students navigating the same exams, same professors, same pressure. You\'re not alone in this.'],
                ['n'=>'06','t'=>'Local always.','d'=>'We know Egyptian universities, syllabi, and the slang. We will never expand to a country we don\'t live in — proximity is the whole point.'],
            ];
        @endphp
        <div style="border-top:1px solid var(--ink);">
            @foreach($principles as $it)
            <div style="display:grid;grid-template-columns:80px 1fr 1.4fr;gap:40px;
                padding:36px 0;border-bottom:1px solid var(--ink);align-items:baseline;"
                class="ag2 principle-row">
                <div class="serif" style="font-size:52px;line-height:0.9;letter-spacing:-0.02em;color:var(--citron);">{{ $it['n'] }}</div>
                <h3 class="serif" style="font-size:34px;line-height:1.05;margin:0;letter-spacing:-0.02em;font-weight:400;">{{ $it['t'] }}</h3>
                <p style="font-size:17px;line-height:1.55;color:var(--muted);margin:0;">{{ $it['d'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>


{{-- ══════════════════════════════════════════════════════
     VOICES / TESTIMONIALS
══════════════════════════════════════════════════════ --}}
<section style="padding:40px 0 100px;">
    <div class="ez-container">
        <div style="margin-bottom:56px;">
            <div style="font-family:'JetBrains Mono',monospace;font-size:11px;letter-spacing:0.16em;
                text-transform:uppercase;color:var(--muted);margin-bottom:24px;">—— 05 / Voices</div>
            <h2 class="serif" style="font-size:clamp(44px,6.5vw,96px);line-height:0.96;
                margin:0;letter-spacing:-0.025em;font-weight:400;">
                What students <em style="font-style:italic;">actually</em> say.
            </h2>
        </div>

        @php
            $quotes = [
                ['q'=>'I failed my OS midterm in March. By May, my tutor at Zatuna had me at an A-minus. He literally took the same course with the same professor two years ago.','n'=>'Yara Mahmoud','d'=>'3rd year · CS · Cairo Uni'],
                ['q'=>"I'm not a great student, honestly. I just needed someone patient who'd answer the same dumb question four times. That's what I got here.",'n'=>'Hossam Kheir','d'=>'2nd year · Business · AUC'],
                ['q'=>"I tutor here. I'm a real person. They actually pay on time. There's not much else to say.",'n'=>'Adam Khalil','d'=>'Tutor since 2024'],
            ];
        @endphp
        <div class="ag3" style="display:grid;grid-template-columns:repeat(3,1fr);gap:24px;">
            @foreach($quotes as $i => $q)
            <blockquote style="margin:0;
                background:{{ $i === 1 ? 'var(--citron)' : 'var(--cream)' }};
                border:{{ $i === 1 ? 'none' : '1px solid var(--line)' }};
                border-radius:24px;padding:32px;
                display:flex;flex-direction:column;justify-content:space-between;min-height:320px;">
                <div class="serif" style="font-size:72px;line-height:0.6;
                    color:{{ $i === 1 ? 'var(--ink)' : 'var(--citron)' }};font-style:italic;">"</div>
                <p class="serif" style="font-size:21px;line-height:1.35;margin:24px 0 28px;
                    letter-spacing:-0.005em;color:var(--ink);flex:1;">{{ $q['q'] }}</p>
                <div style="padding-top:16px;border-top:1px solid rgba(7,41,35,0.15);
                    display:flex;align-items:center;gap:12px;">
                    <div style="width:38px;height:38px;border-radius:50%;
                        background:{{ $i === 1 ? 'var(--ink)' : 'var(--leaf)' }};flex-shrink:0;"></div>
                    <div>
                        <div style="font-size:14px;font-weight:600;">{{ $q['n'] }}</div>
                        <div style="font-size:12px;color:rgba(7,41,35,0.65);">{{ $q['d'] }}</div>
                    </div>
                </div>
            </blockquote>
            @endforeach
        </div>
    </div>
</section>


{{-- ══════════════════════════════════════════════════════
     CTA
══════════════════════════════════════════════════════ --}}
<section style="padding:40px 0 80px;">
    <div class="ez-container">
        <div class="ez-pad-lg" style="background:var(--ink);color:var(--cream);border-radius:32px;
            padding:88px 64px;position:relative;overflow:hidden;text-align:center;">
            <div style="position:absolute;left:-40px;top:-20px;opacity:0.15;">
                <svg width="360" height="220" viewBox="0 0 120 72" fill="none" aria-hidden="true">
                    <path d="M10 60 C 30 50, 60 35, 110 12" stroke="var(--citron)" stroke-width="1.5" stroke-linecap="round"/>
                    <ellipse cx="28" cy="48" rx="6" ry="3.5" transform="rotate(-30 28 48)" fill="var(--citron)"/>
                    <ellipse cx="46" cy="40" rx="6.5" ry="3.6" transform="rotate(-30 46 40)" fill="var(--citron)"/>
                    <ellipse cx="64" cy="32" rx="7" ry="3.8" transform="rotate(-30 64 32)" fill="var(--citron)"/>
                    <ellipse cx="82" cy="24" rx="6.5" ry="3.6" transform="rotate(-30 82 24)" fill="var(--citron)"/>
                </svg>
            </div>
            <div style="position:absolute;right:-40px;bottom:-20px;opacity:0.15;transform:scaleX(-1);">
                <svg width="360" height="220" viewBox="0 0 120 72" fill="none" aria-hidden="true">
                    <path d="M10 60 C 30 50, 60 35, 110 12" stroke="var(--citron)" stroke-width="1.5" stroke-linecap="round"/>
                    <ellipse cx="28" cy="48" rx="6" ry="3.5" transform="rotate(-30 28 48)" fill="var(--citron)"/>
                    <ellipse cx="46" cy="40" rx="6.5" ry="3.6" transform="rotate(-30 46 40)" fill="var(--citron)"/>
                    <ellipse cx="64" cy="32" rx="7" ry="3.8" transform="rotate(-30 64 32)" fill="var(--citron)"/>
                    <ellipse cx="82" cy="24" rx="6.5" ry="3.6" transform="rotate(-30 82 24)" fill="var(--citron)"/>
                </svg>
            </div>

            <div style="position:relative;z-index:1;max-width:900px;margin:0 auto;">
                <div style="font-family:'JetBrains Mono',monospace;font-size:11px;letter-spacing:0.18em;
                    text-transform:uppercase;color:var(--citron);margin-bottom:24px;">—— 06 / Last thing</div>
                <h2 class="serif" style="font-size:clamp(56px,8vw,124px);line-height:0.94;
                    margin:0;letter-spacing:-0.035em;font-weight:400;">
                    Plant the<br>
                    <em style="font-style:italic;color:var(--citron);">olive</em>.
                </h2>
                <p style="margin-top:32px;font-size:18px;line-height:1.55;
                    color:rgba(250,255,224,0.78);max-width:580px;margin-left:auto;margin-right:auto;">
                    Take one course. See if your tutor is actually any good. If they're
                    not — full refund, no awkwardness. We'd rather you tried us than
                    "looked into it later".
                </p>
                <div style="margin-top:44px;display:flex;gap:14px;justify-content:center;flex-wrap:wrap;">
                    <a href="/classes" class="btn-primary"
                        style="padding:18px 28px;border-radius:999px;font-size:15px;font-weight:600;
                        display:inline-flex;align-items:center;gap:10px;">
                        Browse courses
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M7 17L17 7"/><path d="M8 7h9v9"/></svg>
                    </a>
                    <a href="/contact" style="padding:18px 28px;border-radius:999px;
                        background:transparent;color:var(--cream);
                        border:1px solid rgba(250,255,224,0.35);
                        font-size:15px;font-weight:600;text-decoration:none;
                        display:inline-flex;align-items:center;gap:10px;">
                        Say hi first
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>


</div>
@endsection

@push('styles_top')
<style>
    /* principle rows collapse on tablet */
    @media (max-width: 900px) {
        .principle-row { grid-template-columns: 60px 1fr !important; gap: 20px !important; }
        .principle-row p { grid-column: 2; }
    }
    @media (max-width: 640px) {
        .principle-row { grid-template-columns: 1fr !important; gap: 12px !important; }
        .principle-row p { grid-column: 1; }
    }
</style>
@endpush
