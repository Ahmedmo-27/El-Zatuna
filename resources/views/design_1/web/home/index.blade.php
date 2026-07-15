@extends('design_1.web.layouts.app')

@section('content')
<main class="bg-[#FAFFE0] text-[#072923] min-h-screen">

{{-- ══════════════════════════════════════════════════════════════
     HERO
══════════════════════════════════════════════════════════════ --}}
<section style="padding-top:64px;padding-bottom:96px;">
    <div class="ez-container">

        {{-- Eyebrow --}}
        <div class="hidden md:flex" style="justify-content:space-between;align-items:center;
            font-family:'JetBrains Mono',monospace;font-size:11px;letter-spacing:0.14em;
            text-transform:uppercase;color:var(--muted);
            padding-bottom:32px;border-bottom:1px solid var(--line);margin-bottom:64px;">
            <span>—— ISSUE Nº 04 / 2026</span>
            <span>· Cairo · Alexandria · Online ·</span>
            <span>FALL ENROLLMENT 14 / 09 →</span>
        </div>

        {{-- Hero grid: heading left | robot right --}}
        <div class="ez-hero-grid hg2 hero-g" style="align-items:center;">
            <div>
                <h1 class="serif" style="font-size:clamp(40px,7vw,96px);line-height:0.96;
                    letter-spacing:-0.035em;margin:0;font-weight:400;">
                    <span style="position:absolute;width:1px;height:1px;padding:0;margin:-1px;overflow:hidden;clip:rect(0,0,0,0);white-space:nowrap;border:0;">El-Zatuna — course-specific tutoring for university students in Egypt</span>
                    Got it.<br>
                    You're <em style="font-style:italic;">already</em><br>
                    <span style="display:inline-block;position:relative;white-space:nowrap;">
                        <span id="typed-text" style="color:var(--ink);"></span>
                        <span style="display:inline-block;width:3px;height:0.9em;
                            background:var(--citron);vertical-align:-0.05em;margin-left:4px;
                            animation:blink 1s step-end infinite;"></span>
                    </span>
                </h1>

                <p style="margin-top:36px;font-size:17px;line-height:1.55;color:var(--muted);max-width:480px;">
                    We're university students who built the tutoring platform we
                    wished existed — <em>your</em> university, <em>your</em> course,
                    someone who passed it last semester.
                </p>

                <div style="margin-top:48px;display:flex;gap:14px;flex-wrap:wrap;">
                    <a href="/classes" class="btn-dark" style="padding:18px 28px;border-radius:999px;font-size:15px;font-weight:600;gap:12px;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 5a2 2 0 0 1 2-2h11v17H6a2 2 0 0 0-2 2V5z"/><path d="M9 8h5"/><path d="M9 12h5"/></svg>
                        Browse {{ $professionalCoursesCount ?? '0' }} courses
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M7 17L17 7"/><path d="M8 7h9v9"/></svg>
                    </a>
                    <a href="/contact?type=request_course" class="btn-ghost" style="padding:18px 28px;border-radius:999px;font-size:15px;font-weight:600;">
                        Request a course
                    </a>
                </div>
            </div>

            {{-- ── Robot 3D (untouched) ── --}}
            <div class="hero-right-col mt-10 lg:mt-0">
                <div id="hero-3d-container" class="relative h-[280px] sm:h-[340px] md:h-[500px] w-full max-w-[560px] mx-auto overflow-visible border-2 sm:border-4 border-[#C8CD06] rounded-[32px] sm:rounded-[48px] bg-[#072923]/5 shadow-2xl">
                    <model-viewer
                        src="/3dmodels/robotzatuna.glb"
                        alt="El-Zatuna 3D robot mascot"
                        camera-controls
                        auto-rotate
                        rotation-per-second="20deg"
                        camera-orbit="65deg 75deg 2.8m"
                        min-camera-orbit="auto auto 2.8m"
                        max-camera-orbit="auto auto 2.2m"
                        exposure="1"
                        shadow-intensity="1"
                        class="w-full h-full"
                    ></model-viewer>
                </div>
            </div>
        </div>

        {{-- Image strip --}}
        <div class="ez-image-strip" style="margin-top:80px;display:grid;
            grid-template-columns:1.4fr 1fr 1fr;gap:16px;height:280px;">

            {{-- Dark card --}}
            <div style="background:var(--ink);border-radius:24px;position:relative;overflow:hidden;
                color:var(--cream);padding:28px;display:flex;flex-direction:column;justify-content:space-between;">
                <div aria-hidden="true" style="position:absolute;inset:0;
                    background-image:repeating-linear-gradient(135deg,transparent 0 14px,rgba(250,255,224,0.06) 14px 15px);"></div>
                <div style="position:relative;z-index:1;font-family:'JetBrains Mono',monospace;
                    font-size:11px;letter-spacing:0.16em;text-transform:uppercase;opacity:0.7;">
                    {{ number_format($professionalCoursesCount ?? 0) }} courses · 12 universities
                </div>
                <div style="position:relative;z-index:1;">
                    <div class="serif" style="font-size:42px;line-height:1.05;letter-spacing:-0.02em;">
                        Real <em style="font-style:italic;color:var(--citron);">tutors</em>.<br>
                        Real syllabi.
                    </div>
                </div>
            </div>

            {{-- Leaf card --}}
            <div style="background:var(--leaf);border-radius:24px;padding:28px;color:var(--cream);
                display:flex;flex-direction:column;justify-content:space-between;">
                <div style="font-family:'JetBrains Mono',monospace;font-size:11px;
                    letter-spacing:0.16em;text-transform:uppercase;opacity:0.7;">
                    257 students enrolled
                </div>
                <div>
                    <div style="display:flex;margin-bottom:14px;">
                        @foreach(['#072923','#FAFFE0','#C8CD06','#0d3a31'] as $ci => $col)
                        <div style="width:44px;height:44px;border-radius:50%;background:{{ $col }};
                            margin-left:{{ $ci ? '-12px' : '0' }};border:2px solid var(--leaf);"></div>
                        @endforeach
                    </div>
                    <div class="serif" style="font-size:28px;line-height:1.1;letter-spacing:-0.01em;">
                        Join the cohort.
                    </div>
                </div>
            </div>

            {{-- Citron card --}}
            <div style="background:var(--citron);border-radius:24px;padding:28px;
                display:flex;flex-direction:column;justify-content:space-between;
                position:relative;overflow:hidden;">
                <svg width="140" height="84" viewBox="0 0 120 72" fill="none" aria-hidden="true"
                    style="position:absolute;right:-10px;bottom:-10px;opacity:0.2;">
                    <path d="M10 60 C 30 50, 60 35, 110 12" stroke="var(--ink)" stroke-width="1.5" stroke-linecap="round"/>
                    <ellipse cx="28" cy="48" rx="6" ry="3.5" transform="rotate(-30 28 48)" fill="var(--ink)"/>
                    <ellipse cx="46" cy="40" rx="6.5" ry="3.6" transform="rotate(-30 46 40)" fill="var(--ink)"/>
                    <ellipse cx="64" cy="32" rx="7" ry="3.8" transform="rotate(-30 64 32)" fill="var(--ink)"/>
                    <ellipse cx="82" cy="24" rx="6.5" ry="3.6" transform="rotate(-30 82 24)" fill="var(--ink)"/>
                    <ellipse cx="98" cy="17" rx="5.5" ry="3.2" transform="rotate(-30 98 17)" fill="var(--ink)"/>
                </svg>
                <div>
                    <div style="font-family:'JetBrains Mono',monospace;font-size:11px;
                        letter-spacing:0.16em;text-transform:uppercase;opacity:0.7;">
                        what zatuna means
                    </div>
                    <div class="serif" style="font-size:28px;line-height:1.1;letter-spacing:-0.01em;margin-top:8px;">
                        The olive — patient,<br>generous, ancient.
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


{{-- ══════════════════════════════════════════════════════════════
     PILLARS
══════════════════════════════════════════════════════════════ --}}
<section style="padding:120px 0 80px;">
    <div class="ez-container">
        <div class="hg2" style="display:grid;grid-template-columns:0.9fr 1.4fr;gap:80px;
            margin-bottom:80px;align-items:end;">
            <div>
                <div style="font-family:'JetBrains Mono',monospace;font-size:11px;
                    letter-spacing:0.16em;text-transform:uppercase;color:var(--muted);margin-bottom:24px;">
                    —— 01 / The approach
                </div>
                <h2 class="serif" style="font-size:clamp(44px,6.5vw,92px);
                    line-height:0.96;margin:0;letter-spacing:-0.025em;">
                    Three things<br>we do <em style="font-style:italic;">differently.</em>
                </h2>
            </div>
            <p style="font-size:17px;line-height:1.55;color:var(--muted);margin:0;max-width:520px;align-self:end;">
                Most platforms teach a subject. We teach <em>your</em> subject — the one
                with the specific professor, the specific textbook, and the specific exam
                you have on Thursday week.
            </p>
        </div>

        <div class="hg3" style="display:grid;grid-template-columns:repeat(3,1fr);gap:0;border-top:1px solid var(--line);">
            @foreach([
                ['01','layers','Course-by-course.','Pick your university, your faculty, your specific course code. The tutoring is built around that exact syllabus — not a generic curriculum.'],
                ['02','users','Tutors who just did it.','Every tutor passed the course you\'re taking, in the last 1–3 years. They remember the tricky bits because they were stuck on them too.'],
                ['03','clock','Pace that fits you.','Stream lessons on demand, book a 1:1 when you\'re stuck, drop into a live revision session before midterms. No fixed term, no late fines.'],
            ] as $pi => $pillar)
            <div class="pillar-col" style="padding:40px {{ $pi ? '32px' : '0' }} 40px {{ $pi ? '40px' : '0' }};
                border-right:{{ $pi < 2 ? '1px solid var(--line)' : 'none' }};">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:28px;">
                    <span style="font-family:'JetBrains Mono',monospace;font-size:13px;
                        letter-spacing:0.12em;color:var(--muted);">{{ $pillar[0] }}</span>
                    <div style="width:48px;height:48px;border-radius:50%;background:var(--citron);
                        display:flex;align-items:center;justify-content:center;color:var(--ink);">
                        @if($pillar[1] === 'layers')
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3l9 5-9 5-9-5 9-5z"/><path d="M3 13l9 5 9-5"/><path d="M3 18l9 5 9-5"/></svg>
                        @elseif($pillar[1] === 'users')
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="8" r="4"/><path d="M2 21c0-4 3-6 7-6s7 2 7 6"/><path d="M16 11a3.5 3.5 0 0 0 0-7"/><path d="M22 21c0-3-2-5-5-5.5"/></svg>
                        @else
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>
                        @endif
                    </div>
                </div>
                <h3 class="serif" style="font-size:34px;line-height:1.05;letter-spacing:-0.015em;
                    margin:0 0 16px;font-weight:400;">{{ $pillar[2] }}</h3>
                <p style="font-size:15px;line-height:1.6;color:var(--muted);margin:0;">{{ $pillar[3] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>


{{-- ══════════════════════════════════════════════════════════════
     MARQUEE — big dark band
══════════════════════════════════════════════════════════════ --}}
<section style="background:var(--ink);color:var(--cream);padding:48px 0;overflow:hidden;">
    <div style="display:flex;white-space:nowrap;">
        <div class="marquee-track" style="display:flex;align-items:center;gap:64px;padding-right:64px;">
            @foreach(range(0,3) as $r)
                <span class="serif" style="font-size:clamp(56px,8vw,96px);line-height:1;letter-spacing:-0.02em;">Learn.</span>
                <span class="serif" style="font-size:clamp(56px,8vw,96px);line-height:1;color:var(--citron);font-style:italic;">Grow.</span>
                <span class="serif" style="font-size:clamp(56px,8vw,96px);line-height:1;">Lead.</span>
                <span style="font-size:clamp(40px,6vw,64px);color:var(--citron);">✦</span>
            @endforeach
            @foreach(range(0,3) as $r)
                <span class="serif" style="font-size:clamp(56px,8vw,96px);line-height:1;letter-spacing:-0.02em;">Learn.</span>
                <span class="serif" style="font-size:clamp(56px,8vw,96px);line-height:1;color:var(--citron);font-style:italic;">Grow.</span>
                <span class="serif" style="font-size:clamp(56px,8vw,96px);line-height:1;">Lead.</span>
                <span style="font-size:clamp(40px,6vw,64px);color:var(--citron);">✦</span>
            @endforeach
        </div>
    </div>
</section>


{{-- ══════════════════════════════════════════════════════════════
     COURSES — featured this term (auth only)
══════════════════════════════════════════════════════════════ --}}
@auth
<section id="courses" style="padding:120px 0;">
    <div class="ez-container">
        <div style="display:flex;justify-content:space-between;align-items:flex-end;
            margin-bottom:56px;gap:40px;flex-wrap:wrap;">
            <div>
                <div style="font-family:'JetBrains Mono',monospace;font-size:11px;
                    letter-spacing:0.16em;text-transform:uppercase;color:var(--muted);margin-bottom:24px;">
                    —— 02 / Featured this term
                </div>
                <h2 class="serif" style="font-size:clamp(44px,6.5vw,92px);
                    line-height:0.96;margin:0;letter-spacing:-0.025em;">
                    The courses students<br>actually <em style="font-style:italic;">need</em>.
                </h2>
            </div>
        </div>

        <div class="ez-three-col">
            @forelse(($upcomingCourses ?? collect())->take(6) as $course)
            @php
                $accent = $loop->even ? '#BDEA42' : '#C8CD06';
                $idx = str_pad($loop->iteration, 2, '0', STR_PAD_LEFT);
            @endphp
            <a href="{{ $course->getUrl() }}" class="card-hover" style="display:block;text-decoration:none;">
                <div style="border-radius:20px;overflow:hidden;background:var(--ink);color:var(--cream);position:relative;">
                    {{-- Image area --}}
                    <div style="height:220px;position:relative;overflow:hidden;background:{{ $accent }};">
                        @if($course->getImageCover())
                            <img src="{{ $course->getImageCover() }}" alt="{{ $course->title }}"
                                loading="lazy" decoding="async"
                                style="width:100%;height:100%;object-fit:cover;"/>
                        @else
                            <div aria-hidden="true" style="position:absolute;inset:0;
                                background:linear-gradient(135deg,{{ $accent }} 0%,var(--leaf) 100%);"></div>
                        @endif
                        <div aria-hidden="true" style="position:absolute;inset:0;
                            background-image:repeating-linear-gradient(135deg,transparent 0 18px,rgba(7,41,35,0.06) 18px 19px);"></div>
                        <div style="position:absolute;top:16px;left:16px;font-family:'JetBrains Mono',monospace;
                            font-size:11px;letter-spacing:0.14em;color:var(--ink);font-weight:600;
                            background:rgba(250,255,224,0.85);padding:4px 8px;border-radius:999px;">
                            {{ strtoupper(substr($course->slug ?? 'COURSE', 0, 8)) }}
                        </div>
                        @if(!empty($course->rate) && $course->rate > 0)
                        <div style="position:absolute;bottom:16px;right:16px;background:var(--ink);
                            color:var(--cream);border-radius:999px;padding:6px 12px;
                            font-family:'JetBrains Mono',monospace;font-size:10px;letter-spacing:0.12em;
                            text-transform:uppercase;display:flex;align-items:center;gap:6px;">
                            <svg width="11" height="11" viewBox="0 0 24 24" fill="currentColor" stroke="none" aria-hidden="true"><path d="M12 3l2.7 5.7L21 9.7l-4.5 4.3L17.6 21 12 17.8 6.4 21l1.1-7L3 9.7l6.3-1z"/></svg>
                            {{ number_format($course->rate, 1) }}
                        </div>
                        @endif
                        <div style="position:absolute;bottom:16px;left:16px;color:var(--ink);
                            font-size:12px;font-weight:600;font-family:'JetBrains Mono',monospace;">
                            [ {{ $idx }} ]
                        </div>
                    </div>
                    {{-- Body --}}
                    <div style="padding:24px;">
                        <h3 class="serif" style="font-size:22px;line-height:1.2;margin:0;
                            letter-spacing:-0.015em;font-weight:400;color:var(--cream);">
                            {{ $course->title }}
                        </h3>
                        <div style="margin-top:14px;font-size:13px;color:rgba(250,255,224,0.65);
                            display:flex;align-items:center;gap:10px;">
                            <div style="width:22px;height:22px;border-radius:50%;background:var(--citron);flex-shrink:0;"></div>
                            {{ $course->teacher->full_name ?? 'Instructor' }}
                        </div>
                        @if(!empty($course->university) || !empty($course->teacher->organ_name ?? null))
                        <div style="margin-top:6px;font-size:12px;color:rgba(250,255,224,0.45);
                            font-family:'JetBrains Mono',monospace;letter-spacing:0.04em;">
                            {{ $course->university?->title ?? ($course->teacher->organ_name ?? '') }}
                        </div>
                        @endif
                        <div style="margin-top:20px;padding-top:18px;border-top:1px solid rgba(250,255,224,0.12);
                            display:flex;justify-content:space-between;font-family:'JetBrains Mono',monospace;
                            font-size:11px;letter-spacing:0.08em;color:rgba(250,255,224,0.7);
                            text-transform:uppercase;">
                            <span>{{ $course->duration ?? '—' }}</span>
                            <span>{{ number_format($course->students_count ?? 0) }} enrolled</span>
                            <span style="color:var(--citron);">view →</span>
                        </div>
                    </div>
                </div>
            </a>
            @empty
            <div style="grid-column:span 3;text-align:center;padding:80px 0;color:var(--muted);">
                <p style="font-size:18px;">No courses available yet. Check back soon.</p>
            </div>
            @endforelse
        </div>

        <div style="margin-top:56px;display:flex;justify-content:center;">
            <a href="/classes" class="btn-dark" style="padding:16px 28px;border-radius:999px;
                font-size:14px;font-weight:600;gap:10px;">
                Browse all {{ $professionalCoursesCount ?? '' }} courses
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"/><path d="M13 6l6 6-6 6"/></svg>
            </a>
        </div>
    </div>
</section>
@endauth


{{-- ══════════════════════════════════════════════════════════════
     SPOTLIGHT — discounted courses
══════════════════════════════════════════════════════════════ --}}
@if(($discountedCourses ?? collect())->isNotEmpty())
@php
    $spotlightCourses = ($discountedCourses ?? collect())->filter(function($c) {
        $td = $c->bestTicket(true);
        return !empty($td['percent']) && !empty($td['bestTicket']) && $td['bestTicket'] < $c->price;
    })->take(3);
    $maxDiscount = $spotlightCourses->map(function($c) {
        return $c->bestTicket(true)['percent'] ?? 0;
    })->max() ?? 0;
@endphp
@if($spotlightCourses->isNotEmpty())
<section style="padding:40px 0 120px;">
    <div class="ez-container">
        <div class="ez-pad-lg" style="background:var(--ink);color:var(--cream);border-radius:32px;
            padding:72px 64px;position:relative;overflow:hidden;">
            <svg width="420" height="252" viewBox="0 0 120 72" fill="none" aria-hidden="true"
                style="position:absolute;top:40px;right:40px;opacity:0.12;">
                <path d="M10 60 C 30 50, 60 35, 110 12" stroke="var(--citron)" stroke-width="1.5" stroke-linecap="round"/>
                <ellipse cx="28" cy="48" rx="6" ry="3.5" transform="rotate(-30 28 48)" fill="var(--citron)"/>
                <ellipse cx="46" cy="40" rx="6.5" ry="3.6" transform="rotate(-30 46 40)" fill="var(--citron)"/>
                <ellipse cx="64" cy="32" rx="7" ry="3.8" transform="rotate(-30 64 32)" fill="var(--citron)"/>
                <ellipse cx="82" cy="24" rx="6.5" ry="3.6" transform="rotate(-30 82 24)" fill="var(--citron)"/>
                <ellipse cx="98" cy="17" rx="5.5" ry="3.2" transform="rotate(-30 98 17)" fill="var(--citron)"/>
            </svg>

            <div class="hg2" style="display:grid;grid-template-columns:1.4fr 1fr;gap:80px;align-items:center;
                position:relative;z-index:1;">
                <div>
                    <div style="font-family:'JetBrains Mono',monospace;font-size:11px;
                        letter-spacing:0.16em;text-transform:uppercase;color:var(--citron);margin-bottom:24px;">
                        —— 03 / This week's spotlight
                    </div>
                    <h2 class="serif" style="font-size:clamp(44px,7vw,100px);line-height:0.96;
                        margin:0;letter-spacing:-0.025em;color:var(--cream);">
                        Save <span style="color:var(--citron);">{{ $maxDiscount }}%</span><br>
                        on your<br>
                        top <em style="font-style:italic;">courses</em>.
                    </h2>
                    <p style="margin-top:32px;font-size:17px;line-height:1.55;
                        color:rgba(250,255,224,0.75);max-width:520px;">
                        Exclusive discounts on our most popular university courses.
                        Limited time — enrol before the offer ends.
                    </p>
                    <div style="margin-top:40px;display:flex;gap:14px;flex-wrap:wrap;align-items:center;">
                        <a href="/classes" class="btn-primary" style="padding:18px 28px;
                            border-radius:999px;font-size:15px;font-weight:600;gap:10px;">
                            View discounted courses
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M7 17L17 7"/><path d="M8 7h9v9"/></svg>
                        </a>
                    </div>
                </div>

                <div style="border:1px solid rgba(200,205,6,0.3);border-radius:24px;
                    padding:28px;background:rgba(200,205,6,0.05);">
                    <div style="font-family:'JetBrains Mono',monospace;font-size:10px;
                        letter-spacing:0.16em;text-transform:uppercase;color:var(--citron);margin-bottom:20px;">
                        Discounted now
                    </div>
                    @foreach($spotlightCourses as $dc)
                    @php
                        $dcTicket = $dc->bestTicket(true);
                        $dcOriginal = $dc->price;
                        $dcBest = $dcTicket['bestTicket'] ?? $dcOriginal;
                    @endphp
                    <div style="display:flex;justify-content:space-between;align-items:baseline;
                        padding:16px 0;border-bottom:1px solid rgba(250,255,224,0.12);">
                        <div>
                            <div style="font-family:'JetBrains Mono',monospace;font-size:10px;
                                letter-spacing:0.12em;color:var(--citron);">
                                {{ strtoupper(substr($dc->slug ?? 'COURSE', 0, 10)) }}
                            </div>
                            <div class="serif" style="font-size:20px;margin-top:4px;color:var(--cream);">
                                {{ Str::limit($dc->title, 28) }}
                            </div>
                        </div>
                        <div>
                            <div style="font-size:12px;color:rgba(250,255,224,0.5);text-decoration:line-through;">
                                {{ handlePrice($dcOriginal, true, true, false, null, true) }}
                            </div>
                            <div class="serif" style="font-size:22px;color:var(--citron);">
                                {{ handlePrice($dcBest, true, true, false, null, true) }}
                            </div>
                        </div>
                    </div>
                    @endforeach
                    <div style="margin-top:16px;display:flex;justify-content:flex-end;">
                        <div style="background:var(--citron);color:var(--ink);padding:8px 14px;
                            border-radius:999px;font-family:'JetBrains Mono',monospace;
                            font-size:11px;font-weight:700;letter-spacing:0.08em;">
                            SAVE {{ $maxDiscount }}%
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endif
@endif


{{-- ══════════════════════════════════════════════════════════════
     INSTRUCTORS
══════════════════════════════════════════════════════════════ --}}
@if(($instructors ?? collect())->isNotEmpty())
<section style="padding:40px 0 120px;">
    <div class="ez-container">
        <div style="display:flex;justify-content:space-between;align-items:flex-end;
            margin-bottom:56px;gap:40px;flex-wrap:wrap;">
            <div>
                <div style="font-family:'JetBrains Mono',monospace;font-size:11px;
                    letter-spacing:0.16em;text-transform:uppercase;color:var(--muted);margin-bottom:24px;">
                    —— 04 / The roster
                </div>
                <h2 class="serif" style="font-size:clamp(44px,6.5vw,92px);
                    line-height:0.96;margin:0;letter-spacing:-0.025em;">
                    Tutors who <em style="font-style:italic;">remember</em><br>being stuck.
                </h2>
            </div>
            <a href="/instructors" class="uline" style="font-family:'JetBrains Mono',monospace;
                font-size:13px;letter-spacing:0.12em;text-transform:uppercase;color:var(--ink);
                text-decoration:none;border-bottom:1px solid var(--ink);padding-bottom:2px;">
                See all {{ $instructorsCount ?? '' }} →
            </a>
        </div>

        <div class="ez-four-col">
            @foreach(($instructors ?? collect())->take(4) as $instructor)
            @php $colors = ['#C8CD06','#BDEA42','#C8CD06','#BDEA42']; @endphp
            <a href="/users/{{ $instructor->username ?? $instructor->id }}/profile"
                class="card-hover" style="display:flex;flex-direction:column;background:var(--cream);
                border:1px solid var(--line);border-radius:20px;padding:24px;min-height:340px;
                text-decoration:none;color:var(--ink);">
                <div style="width:100%;aspect-ratio:1;background:{{ $colors[$loop->index % 4] }};
                    border-radius:16px;margin-bottom:20px;position:relative;overflow:hidden;">
                    @if($instructor->getAvatar(200) ?? null)
                        <img src="{{ $instructor->getAvatar(200) }}" alt="{{ $instructor->full_name }}"
                            loading="lazy" decoding="async"
                            style="width:100%;height:100%;object-fit:cover;"/>
                    @else
                        <div aria-hidden="true" style="position:absolute;inset:0;
                            background-image:repeating-linear-gradient(135deg,transparent 0 12px,rgba(7,41,35,0.08) 12px 13px);"></div>
                    @endif
                    <div style="position:absolute;bottom:12px;right:12px;font-family:'JetBrains Mono',monospace;
                        font-size:10px;letter-spacing:0.12em;color:var(--ink);background:var(--cream);
                        padding:4px 8px;border-radius:999px;">
                        Nº {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
                    </div>
                </div>
                <h3 class="serif" style="font-size:24px;line-height:1.1;margin:0;
                    letter-spacing:-0.015em;font-weight:400;">{{ $instructor->full_name }}</h3>
                <div style="margin-top:8px;font-size:13px;color:var(--muted);line-height:1.4;">
                    {{ $instructor->headline ?? 'Instructor' }}
                </div>
                <div style="margin-top:auto;padding-top:18px;border-top:1px solid var(--line);
                    display:flex;justify-content:space-between;font-family:'JetBrains Mono',monospace;
                    font-size:11px;letter-spacing:0.08em;text-transform:uppercase;">
                    <span>{{ $instructor->courses_count ?? '' }} courses</span>
                    <span style="color:var(--ink);">profile →</span>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif


{{-- ══════════════════════════════════════════════════════════════
     PLANS (auth-protected)
══════════════════════════════════════════════════════════════ --}}
@auth
@if(($subscriptionPlans ?? collect())->isNotEmpty())
<section id="plans" style="padding:40px 0 120px;">
    <div class="ez-container">
        <div class="hg2" style="display:grid;grid-template-columns:1fr 1.2fr;gap:80px;align-items:end;margin-bottom:56px;">
            <div>
                <div style="font-family:'JetBrains Mono',monospace;font-size:11px;
                    letter-spacing:0.16em;text-transform:uppercase;color:var(--muted);margin-bottom:24px;">
                    —— 05 / Plans
                </div>
                <h2 class="serif" style="font-size:clamp(44px,6.5vw,92px);
                    line-height:0.96;margin:0;letter-spacing:-0.025em;">
                    Pay for the<br>
                    <em style="font-style:italic;">semester</em>, not the<br>
                    shelf life.
                </h2>
            </div>
            <p style="font-size:16px;line-height:1.55;color:var(--muted);margin:0;max-width:460px;align-self:end;">
                Cancel between terms. No auto-rolls. The cheapest tier is genuinely
                useful — we'd rather you upgrade because you want to, not because
                we hobbled the free version.
            </p>
        </div>

        <div class="ez-three-col">
            @foreach(($subscriptionPlans ?? collect())->take(3) as $i => $plan)
            @php $popular = ($i === 1); @endphp
            <div style="background:{{ $popular ? 'var(--ink)' : 'var(--cream)' }};
                color:{{ $popular ? 'var(--cream)' : 'var(--ink)' }};
                border:1px solid {{ $popular ? 'var(--ink)' : 'var(--line)' }};
                border-radius:24px;padding:32px;position:relative;
                display:flex;flex-direction:column;">
                @if($popular)
                <div style="position:absolute;top:-12px;right:24px;background:var(--citron);
                    color:var(--ink);padding:6px 14px;border-radius:999px;
                    font-family:'JetBrains Mono',monospace;font-size:11px;
                    letter-spacing:0.1em;font-weight:700;">MOST PICKED</div>
                @endif
                <div style="font-family:'JetBrains Mono',monospace;font-size:11px;
                    letter-spacing:0.12em;text-transform:uppercase;
                    color:{{ $popular ? 'var(--citron)' : 'var(--muted)' }};">
                    {{ $plan->title }}
                </div>
                <div style="margin-top:16px;">
                    <div class="serif" style="font-size:72px;line-height:0.9;letter-spacing:-0.03em;">
                        {{ handlePrice($plan->price, true, true, false, null, true) }}
                    </div>
                    @if(!empty($plan->subtitle))
                    <div style="font-size:13px;color:{{ $popular ? 'rgba(250,255,224,0.6)' : 'var(--muted)' }};margin-top:6px;">
                        / {{ $plan->subtitle }}
                    </div>
                    @endif
                </div>
                @if(!empty($plan->description))
                <div style="margin-top:8px;font-size:15px;
                    color:{{ $popular ? 'rgba(250,255,224,0.75)' : 'var(--muted)' }};">
                    {{ $plan->description }}
                </div>
                @endif
                <ul style="list-style:none;padding:0;margin:32px 0;display:grid;gap:14px;flex:1;">
                    <li style="display:flex;gap:12px;align-items:flex-start;font-size:15px;line-height:1.4;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--citron)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;margin-top:1px;" aria-hidden="true"><path d="M5 12.5l4.2 4.2L19 7"/></svg>
                        {{ $plan->days }} days access
                    </li>
                    <li style="display:flex;gap:12px;align-items:flex-start;font-size:15px;line-height:1.4;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--citron)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;margin-top:1px;" aria-hidden="true"><path d="M5 12.5l4.2 4.2L19 7"/></svg>
                        @if(!empty($plan->infinite_use)) Unlimited uses @else {{ $plan->usable_count }} uses @endif
                    </li>
                </ul>
                <form method="post" action="/cart/store">
                    @csrf
                    <input type="hidden" name="item_name" value="subscribe_id">
                    <input type="hidden" name="item_id" value="{{ $plan->id }}">
                    <button type="submit"
                        class="{{ $popular ? 'btn-primary' : 'btn-ghost' }}"
                        style="width:100%;padding:14px 22px;border-radius:999px;font-size:14px;
                        font-weight:600;justify-content:center;gap:8px;">
                        Choose {{ $plan->title }}
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"/><path d="M13 6l6 6-6 6"/></svg>
                    </button>
                </form>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif
@endauth


{{-- ══════════════════════════════════════════════════════════════
     BECOME INSTRUCTOR CTA
══════════════════════════════════════════════════════════════ --}}
<section style="padding:40px 0 120px;">
    <div class="ez-container">
        <div class="ez-pad-lg" style="background:var(--citron);border-radius:32px;padding:72px 48px;
            position:relative;overflow:hidden;">
            <svg width="560" height="336" viewBox="0 0 120 72" fill="none" aria-hidden="true"
                style="position:absolute;right:-60px;bottom:-80px;opacity:0.3;">
                <path d="M10 60 C 30 50, 60 35, 110 12" stroke="var(--ink)" stroke-width="1.5" stroke-linecap="round"/>
                <ellipse cx="28" cy="48" rx="6" ry="3.5" transform="rotate(-30 28 48)" fill="var(--ink)"/>
                <ellipse cx="46" cy="40" rx="6.5" ry="3.6" transform="rotate(-30 46 40)" fill="var(--ink)"/>
                <ellipse cx="64" cy="32" rx="7" ry="3.8" transform="rotate(-30 64 32)" fill="var(--ink)"/>
                <ellipse cx="82" cy="24" rx="6.5" ry="3.6" transform="rotate(-30 82 24)" fill="var(--ink)"/>
                <ellipse cx="98" cy="17" rx="5.5" ry="3.2" transform="rotate(-30 98 17)" fill="var(--ink)"/>
            </svg>
            <div class="hg2" style="display:grid;grid-template-columns:1.3fr 1fr;gap:80px;
                align-items:center;position:relative;z-index:1;">
                <div>
                    <div style="font-family:'JetBrains Mono',monospace;font-size:11px;
                        letter-spacing:0.16em;text-transform:uppercase;color:var(--ink);
                        opacity:0.7;margin-bottom:24px;">—— 06 / Teach with us</div>
                    <h2 class="serif" style="font-size:clamp(44px,7vw,100px);
                        line-height:0.94;margin:0;letter-spacing:-0.025em;">
                        You passed it.<br>
                        Now help <em style="font-style:italic;">them</em><br>
                        pass it too.
                    </h2>
                    <p style="margin-top:32px;font-size:17px;line-height:1.55;
                        color:rgba(7,41,35,0.75);max-width:540px;">
                        Built a great revision sheet for your CS final?
                        Recorded a study reel that got your friends through Calc II?
                        Turn it into income — average tutor earns $1,200 per term.
                    </p>
                    <div style="margin-top:40px;display:flex;gap:14px;flex-wrap:wrap;">
                        <a href="/become-instructor" class="btn-dark"
                            style="padding:18px 28px;border-radius:999px;font-size:15px;font-weight:600;gap:10px;">
                            Apply to teach
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M7 17L17 7"/><path d="M8 7h9v9"/></svg>
                        </a>
                    </div>
                </div>

                <div style="background:var(--ink);color:var(--cream);border-radius:24px;padding:32px;">
                    <div style="font-family:'JetBrains Mono',monospace;font-size:11px;
                        letter-spacing:0.14em;text-transform:uppercase;color:var(--citron);">
                        Tutor of the month
                    </div>
                    @php $spotlightTutor = ($instructors ?? collect())->first(); @endphp
                    <div style="margin-top:20px;display:flex;align-items:center;gap:16px;">
                        <div style="width:64px;height:64px;border-radius:50%;background:var(--leaf);overflow:hidden;flex-shrink:0;">
                            @if($spotlightTutor && $spotlightTutor->getAvatar())
                                <img src="{{ $spotlightTutor->getAvatar(64) }}" alt="{{ $spotlightTutor->full_name }}"
                                    loading="lazy" decoding="async"
                                    style="width:100%;height:100%;object-fit:cover;"/>
                            @endif
                        </div>
                        <div>
                            <div class="serif" style="font-size:22px;line-height:1.1;color:var(--cream);">
                                {{ $spotlightTutor->full_name ?? 'Our top tutor' }}
                            </div>
                            <div style="font-size:13px;color:rgba(250,255,224,0.65);">
                                {{ $spotlightTutor->headline ?? 'Instructor' }}
                            </div>
                        </div>
                    </div>
                    <p class="serif" style="margin-top:28px;font-size:20px;line-height:1.4;
                        letter-spacing:-0.005em;color:var(--cream);">
                        "I turned my notes and revision sessions into a full course.
                        Zatuna made it simple — and <span style="color:var(--citron);">profitable</span>."
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>


{{-- ══════════════════════════════════════════════════════════════
     FAQ
══════════════════════════════════════════════════════════════ --}}
<section style="padding:40px 0 80px;">
    <div class="ez-container">
        <div class="hg2" style="display:grid;grid-template-columns:0.9fr 1.4fr;gap:80px;align-items:flex-start;">
            <div class="faq-aside" style="position:sticky;top:100px;min-width:0;overflow:hidden;">
                <div style="font-family:'JetBrains Mono',monospace;font-size:11px;
                    letter-spacing:0.16em;text-transform:uppercase;color:var(--muted);margin-bottom:24px;">
                    —— 07 / FAQ
                </div>
                <h2 class="serif" style="font-size:clamp(40px,5.5vw,76px);
                    line-height:0.98;margin:0;letter-spacing:-0.025em;">
                    Questions<br>before<br>you <em style="font-style:italic;">start.</em>
                </h2>
                <p style="margin-top:28px;font-size:15px;color:var(--muted);max-width:360px;line-height:1.5;">
                    Still wondering? Send us a note — we read every one.
                </p>
                <a href="/contact" class="btn-dark" style="margin-top:20px;padding:14px 22px;
                    border-radius:999px;font-size:14px;font-weight:600;gap:10px;">
                    Get in touch
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M7 17L17 7"/><path d="M8 7h9v9"/></svg>
                </a>
            </div>

            <div style="border-top:1px solid var(--ink);" id="faq-list">
                @foreach([
                    ['Do you cover my specific university and course?','We currently cover 12 universities across Egypt, with deep coverage for Cairo Uni, AUC, Ain Shams, and Alexandria. If your course isn\'t listed, request it and we\'ll pair you with a vetted tutor within two weeks.'],
                    ['How is this different from YouTube or Khan Academy?','Generic content is great for fundamentals. We\'re built for the specific exam you have on Thursday week — same textbook, same prof\'s past papers, same notation. Our tutors took your course in the last 1–3 years and remember the trick questions.'],
                    ['Can I cancel between semesters?','Yes. All plans are month-to-month with no auto-rolls between terms. Pause for the summer, come back in September. No questions asked.'],
                    ['What if my tutor isn\'t a good match?','You can swap tutors anytime in your first two sessions, no charge. After that, swaps are free between semesters. We\'d rather rematch than lose you.'],
                    ['Do you have content in Arabic?','About 60% of our content is delivered in Arabic-English mix, matching how most Egyptian universities actually teach. Pure-Arabic and pure-English tracks are available on most popular courses.'],
                ] as $fi => $faq)
                <div class="faq-item" style="border-bottom:1px solid var(--ink);">
                    <button class="faq-trigger" onclick="toggleFaq(this)"
                        style="width:100%;background:transparent;border:0;padding:28px 0;
                        text-align:left;display:flex;justify-content:space-between;align-items:center;
                        gap:32px;cursor:pointer;">
                        <span class="serif" style="font-size:clamp(18px,2.5vw,26px);line-height:1.15;
                            letter-spacing:-0.015em;color:var(--ink);">
                            <span style="font-family:'JetBrains Mono',monospace;font-size:13px;
                                color:var(--muted);margin-right:14px;vertical-align:super;">
                                {{ str_pad($fi+1, 2, '0', STR_PAD_LEFT) }}
                            </span>
                            {{ $faq[0] }}
                        </span>
                        <span class="faq-toggle-icon" style="width:36px;height:36px;border-radius:50%;
                            border:1px solid var(--ink);display:flex;align-items:center;
                            justify-content:center;flex-shrink:0;color:var(--ink);">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 5v14"/><path d="M5 12h14"/></svg>
                        </span>
                    </button>
                    <div class="faq-answer" style="padding-right:80px;">
                        <p style="padding-bottom:32px;font-size:16px;line-height:1.6;color:var(--muted);margin:0;">
                            {{ $faq[1] }}
                        </p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

</main>

@push('styles_top')
<style>
    @keyframes blink { from,to{opacity:0} 50%{opacity:1} }

    /* ── Responsive grid helpers (home page) ─────────────────── */
    /* .hg2 = 2-col grid that collapses to 1 on tablet             */
    /* .hg3 = 3-col grid that collapses to 1 on mobile             */
    @media (max-width: 1100px) {
        .hg2 { grid-template-columns: 1fr !important; gap: 48px !important; }
        .hg3 { grid-template-columns: 1fr !important; }
        .hg3 > .pillar-col {
            border-right: none !important;
            border-bottom: 1px solid var(--line);
            padding: 32px 0 !important;
        }
        .hg3 > .pillar-col:last-child { border-bottom: none; }
        .hg2.hero-g { align-items: start !important; }

        /* Sticky FAQ heading must release once the grid stacks, otherwise it
           stays pinned and overlaps the questions below it. */
        .faq-aside { position: static !important; overflow: visible !important; }

        /* Image strip: drop the fixed 280px height and go 2-up so the serif
           card text stops overflowing. */
        .ez-image-strip { grid-template-columns: 1fr 1fr !important; height: auto !important; }
        .ez-image-strip > div { min-height: 200px; }
    }
    @media (max-width: 768px) {
        .ez-four-col { grid-template-columns: 1fr 1fr !important; }
        .ez-three-col { grid-template-columns: 1fr !important; }
    }
    @media (max-width: 640px) {
        .ez-image-strip { grid-template-columns: 1fr !important; }
        .ez-image-strip > div { min-height: 170px; }
    }
    @media (max-width: 480px) {
        .ez-four-col { grid-template-columns: 1fr !important; }
    }
</style>
@endpush

@push('scripts_bottom')
<script type="module" src="https://unpkg.com/@google/model-viewer/dist/model-viewer.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    /* ── Typewriter ── */
    var el = document.getElementById('typed-text');
    if (el) {
        var phrases = ['ahead of it.', 'tutored.', 'on track.', 'mastering it.'];
        var pIdx = 0, cIdx = 0, del = false;
        function tick() {
            var cur = phrases[pIdx];
            if (!del && cIdx === cur.length) {
                del = true; return setTimeout(tick, 2200);
            } else if (del && cIdx === 0) {
                del = false; pIdx = (pIdx + 1) % phrases.length; return setTimeout(tick, 500);
            }
            cIdx += del ? -1 : 1;
            el.textContent = cur.slice(0, cIdx);
            setTimeout(tick, del ? 38 : 75);
        }
        tick();
    }

    /* ── FAQ accordion ── */
    window.toggleFaq = function (btn) {
        var item = btn.closest('.faq-item');
        var wasOpen = item.classList.contains('open');
        document.querySelectorAll('.faq-item.open').forEach(function (i) { i.classList.remove('open'); });
        if (!wasOpen) item.classList.add('open');
    };

    /* ── Scroll-reveal (plain Intersection Observer) ── */
    if ('IntersectionObserver' in window) {
        var obs = new IntersectionObserver(function (entries) {
            entries.forEach(function (e) {
                if (e.isIntersecting) { e.target.classList.add('fade-up'); obs.unobserve(e.target); }
            });
        }, { threshold: 0.1 });
        document.querySelectorAll('section').forEach(function (s) { obs.observe(s); });
    }
});
</script>
@endpush
@endsection
