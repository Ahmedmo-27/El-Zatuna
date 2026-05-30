@extends('design_1.web.layouts.app')

@push('styles_top')
<style>
    @media (max-width: 1024px) {
        .cg2 { grid-template-columns: 1fr !important; gap: 40px !important; }
    }
    @media (max-width: 768px) {
        .cg2 { grid-template-columns: 1fr !important; gap: 32px !important; }
    }
    /* Contact mode tabs */
    .cmode-btn {
        padding: 36px 32px;
        background: transparent;
        color: var(--ink);
        border: 0;
        text-align: left;
        cursor: pointer;
        transition: background .3s ease, color .3s ease;
        display: grid;
        grid-template-columns: auto 1fr auto;
        align-items: center;
        gap: 24px;
        width: 100%;
    }
    .cmode-btn.active {
        background: var(--ink);
        color: var(--cream);
    }
    .cmode-btn .cmode-num  { font-family: 'JetBrains Mono', monospace; font-size: 13px; letter-spacing: 0.14em; color: var(--muted); }
    .cmode-btn.active .cmode-num { color: var(--citron); }
    .cmode-btn .cmode-sub  { font-size: 14px; margin-top: 4px; color: var(--muted); }
    .cmode-btn.active .cmode-sub { color: rgba(250,255,224,0.65); }
    .cmode-icon {
        width: 48px; height: 48px; border-radius: 50%;
        border: 1px solid var(--ink); background: transparent;
        display: flex; align-items: center; justify-content: center;
        color: var(--ink); transition: all .3s ease;
    }
    .cmode-btn.active .cmode-icon {
        background: var(--citron); border-color: var(--citron); color: var(--ink);
    }

    /* Form panels */
    .cform-panel { display: none; }
    .cform-panel.active { display: block; }

    /* ez-pill buttons */
    .ez-pill {
        padding: 8px 16px; border-radius: 999px;
        border: 1px solid var(--ink);
        background: transparent; color: var(--ink);
        font-size: 13px; font-weight: 500;
        cursor: pointer; transition: all .2s ease;
    }
    .ez-pill.active, .ez-pill:focus {
        background: var(--ink); color: var(--cream);
    }
</style>
@endpush

@section('content')
<div class="bg-[#FAFFE0] text-[#072923]">

{{-- ══════════════════════════════════════════════════════
     CONTACT HERO
══════════════════════════════════════════════════════ --}}
<section style="padding-top:64px;padding-bottom:48px;">
    <div class="ez-container">

        {{-- Eyebrow --}}
        <div class="hidden md:flex" style="justify-content:space-between;align-items:center;
            font-family:'JetBrains Mono',monospace;font-size:11px;letter-spacing:0.14em;
            text-transform:uppercase;color:var(--muted);
            padding-bottom:32px;border-bottom:1px solid var(--line);margin-bottom:64px;">
            <span>—— GET IN TOUCH</span>
            <span>· Avg reply 48h ·</span>
            <span>FILE Nº 04 · 09</span>
        </div>

        <div style="display:grid;grid-template-columns:1.4fr 1fr;gap:80px;align-items:end;"
            class="cg2">
            <div>
                <h1 class="serif" style="font-size:clamp(56px,9.5vw,140px);line-height:0.94;
                    margin:0;letter-spacing:-0.035em;font-weight:400;">
                    Let's <em style="font-style:italic;">talk.</em><br>
                    We <span style="position:relative;display:inline-block;">
                        read<span style="position:absolute;left:0;right:0;bottom:0.04em;
                            height:0.32em;background:var(--citron);z-index:-1;border-radius:4px;"></span>
                    </span> every one.
                </h1>
            </div>
            <div style="padding-bottom:8px;">
                <div style="font-family:'JetBrains Mono',monospace;font-size:11px;
                    letter-spacing:0.14em;text-transform:uppercase;color:var(--muted);margin-bottom:16px;">
                    —— What's this about?
                </div>
                <p class="serif" style="font-size:21px;line-height:1.4;margin:0;letter-spacing:-0.005em;">
                    Pick one of the two below — it routes your message to the
                    right person, and the form changes to ask the right things.
                </p>
            </div>
        </div>

        {{-- Mode switcher --}}
        <div id="contact-mode-switcher" style="margin-top:80px;display:grid;grid-template-columns:1fr 1fr;gap:0;
            border-top:1px solid var(--line);border-bottom:1px solid var(--line);">
            <button type="button" class="cmode-btn active" data-mode="message"
                onclick="switchContactMode('message')"
                style="border-right:1px solid var(--line);">
                <span class="cmode-num">01</span>
                <span>
                    <div class="serif" style="font-size:clamp(20px,3vw,30px);line-height:1.1;letter-spacing:-0.015em;">
                        Send a message
                    </div>
                    <div class="cmode-sub">A question, feedback, or a hello.</div>
                </span>
                <span class="cmode-icon">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 12a8 8 0 0 1-12.5 6.6L4 20l1.4-4.5A8 8 0 1 1 21 12z"/></svg>
                </span>
            </button>
            <button type="button" class="cmode-btn" data-mode="request_course"
                onclick="switchContactMode('request_course')">
                <span class="cmode-num">02</span>
                <span>
                    <div class="serif" style="font-size:clamp(20px,3vw,30px);line-height:1.1;letter-spacing:-0.015em;">
                        Request a course
                    </div>
                    <div class="cmode-sub">Tell us what your syllabus needs next.</div>
                </span>
                <span class="cmode-icon">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 5v14"/><path d="M5 12h14"/></svg>
                </span>
            </button>
        </div>
    </div>
</section>


{{-- ══════════════════════════════════════════════════════
     MAIN FORM AREA
══════════════════════════════════════════════════════ --}}
<section style="padding-top:72px;padding-bottom:80px;">
    <div class="ez-container">
        <div style="display:grid;grid-template-columns:1.6fr 1fr;gap:64px;align-items:start;"
            class="cg2">

            {{-- Form column --}}
            <div>
                <div id="form-label" style="font-family:'JetBrains Mono',monospace;font-size:11px;
                    letter-spacing:0.14em;text-transform:uppercase;color:var(--muted);margin-bottom:16px;">
                    —— Form A · Message
                </div>
                <h2 id="form-heading" class="serif" style="font-size:clamp(36px,5vw,60px);
                    line-height:1.02;margin:0 0 56px;letter-spacing:-0.02em;font-weight:400;">
                    Tell us what's<br>on your <em style="font-style:italic;">mind.</em>
                </h2>

                @include('design_1.web.contactus.form')
            </div>

            {{-- Info sidebar --}}
            @include('design_1.web.contactus.our_info')

        </div>
    </div>
</section>


{{-- ══════════════════════════════════════════════════════
     BEFORE YOU WRITE — quick links
══════════════════════════════════════════════════════ --}}
<section style="padding:40px 0 80px;">
    <div class="ez-container">
        <div style="border-top:1px solid var(--line);padding-top:56px;
            display:grid;grid-template-columns:0.9fr 1.6fr;gap:64px;align-items:flex-start;"
            class="cg2">
            <div>
                <div style="font-family:'JetBrains Mono',monospace;font-size:11px;
                    letter-spacing:0.16em;text-transform:uppercase;color:var(--muted);margin-bottom:24px;">
                    —— Before you write
                </div>
                <h2 class="serif" style="font-size:clamp(32px,4.5vw,52px);line-height:1.02;
                    margin:0;letter-spacing:-0.02em;font-weight:400;">
                    Some answers,<br>already <em style="font-style:italic;">written.</em>
                </h2>
                <p style="margin-top:24px;font-size:15px;line-height:1.55;
                    color:var(--muted);max-width:320px;">
                    Faster than waiting on us — and usually enough to unblock you.
                </p>
            </div>
            <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:16px;"
                class="cg2">
                @foreach([
                    ['Reset my password', 'Forgotten login? Two-click reset.', '/forgot-password'],
                    ['How refunds work', 'Within 14 days of any purchase, full.', '/faq'],
                    ['Cancel my plan', 'Cancel anytime in your dashboard.', '/panel/user-panel/financial/subscribe'],
                    ['Become a tutor', 'Apply, 20-min interview, start teaching.', '/become-instructor'],
                ] as $card)
                <a href="{{ $card[2] }}" class="card-hover" style="display:block;background:var(--cream);
                    border:1px solid var(--line);border-radius:18px;padding:24px;
                    position:relative;text-decoration:none;color:var(--ink);">
                    <div style="position:absolute;top:20px;right:20px;width:36px;height:36px;
                        border-radius:50%;border:1px solid var(--ink);display:flex;
                        align-items:center;justify-content:center;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M7 17L17 7"/><path d="M8 7h9v9"/></svg>
                    </div>
                    <h4 class="serif" style="font-size:22px;margin:0 50px 8px 0;
                        letter-spacing:-0.015em;line-height:1.15;font-weight:400;">{{ $card[0] }}</h4>
                    <p style="margin:0;font-size:14px;color:var(--muted);line-height:1.5;">{{ $card[1] }}</p>
                </a>
                @endforeach
            </div>
        </div>
    </div>
</section>


{{-- ══════════════════════════════════════════════════════
     CLOSING STRIP
══════════════════════════════════════════════════════ --}}
<section style="padding:40px 0 80px;">
    <div class="ez-container">
        <div style="background:var(--citron);border-radius:28px;padding:56px 48px;
            position:relative;overflow:hidden;">
            <svg width="400" height="240" viewBox="0 0 120 72" fill="none" aria-hidden="true"
                style="position:absolute;right:-40px;top:-20px;opacity:0.25;">
                <path d="M10 60 C 30 50, 60 35, 110 12" stroke="var(--ink)" stroke-width="1.5" stroke-linecap="round"/>
                <ellipse cx="28" cy="48" rx="6" ry="3.5" transform="rotate(-30 28 48)" fill="var(--ink)"/>
                <ellipse cx="46" cy="40" rx="6.5" ry="3.6" transform="rotate(-30 46 40)" fill="var(--ink)"/>
                <ellipse cx="64" cy="32" rx="7" ry="3.8" transform="rotate(-30 64 32)" fill="var(--ink)"/>
                <ellipse cx="82" cy="24" rx="6.5" ry="3.6" transform="rotate(-30 82 24)" fill="var(--ink)"/>
                <ellipse cx="98" cy="17" rx="5.5" ry="3.2" transform="rotate(-30 98 17)" fill="var(--ink)"/>
            </svg>
            <div style="display:flex;justify-content:space-between;align-items:center;
                gap:40px;position:relative;z-index:1;flex-wrap:wrap;">
                <div>
                    <div style="font-family:'JetBrains Mono',monospace;font-size:11px;
                        letter-spacing:0.14em;text-transform:uppercase;
                        color:rgba(7,41,35,0.7);margin-bottom:16px;">—— P.S.</div>
                    <h2 class="serif" style="font-size:clamp(36px,5vw,60px);line-height:1;
                        margin:0;letter-spacing:-0.02em;">
                        We <em style="font-style:italic;">actually</em> read these.
                    </h2>
                    <p style="margin-top:20px;font-size:16px;line-height:1.5;
                        color:rgba(7,41,35,0.75);max-width:540px;">
                        Two co-founders triage the inbox every morning over coffee.
                        Your message lands on a real desk — promise.
                    </p>
                </div>
                <div style="display:flex;gap:12px;align-items:center;">
                    <div style="display:flex;">
                        <div style="width:64px;height:64px;border-radius:50%;background:var(--ink);
                            border:3px solid var(--citron);"></div>
                        <div style="width:64px;height:64px;border-radius:50%;background:var(--leaf);
                            border:3px solid var(--citron);margin-left:-18px;"></div>
                    </div>
                    <div>
                        <div class="serif" style="font-size:22px;line-height:1.1;">The founders</div>
                        <div style="font-size:13px;color:rgba(7,41,35,0.65);">elzatuna team</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

</div>
@endsection

@push('scripts_bottom')
<script>
var _contactMode = '{{ old('contact_type', $selectedContactType ?? 'message') }}';

function switchContactMode(mode) {
    _contactMode = mode;

    /* Update tab buttons */
    document.querySelectorAll('.cmode-btn').forEach(function(btn) {
        btn.classList.toggle('active', btn.dataset.mode === mode);
    });

    /* Update heading */
    var lbl = document.getElementById('form-label');
    var hdg = document.getElementById('form-heading');
    if (mode === 'message') {
        lbl && (lbl.textContent = '—— Form A · Message');
        hdg && (hdg.innerHTML = 'Tell us what\'s<br>on your <em style="font-style:italic;">mind.</em>');
    } else {
        lbl && (lbl.textContent = '—— Form B · Course request');
        hdg && (hdg.innerHTML = 'Tell us what to<br><em style="font-style:italic;">build</em> next.');
    }

    /* Show/hide form panels */
    document.querySelectorAll('.cform-panel').forEach(function(p) {
        p.classList.toggle('active', p.dataset.panel === mode);
    });

    /* Update hidden input + submit label */
    var typeInput = document.getElementById('contactTypeInput');
    var submitBtn = document.getElementById('contactSubmitButton');
    if (typeInput) typeInput.value = mode;
    if (submitBtn) submitBtn.textContent = (mode === 'request_course') ? 'Submit request →' : 'Send message →';
}

document.addEventListener('DOMContentLoaded', function () {
    switchContactMode(_contactMode);
});
</script>
@endpush
