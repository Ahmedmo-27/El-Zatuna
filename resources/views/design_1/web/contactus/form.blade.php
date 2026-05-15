@php
    $defaultContactType = old('contact_type', $selectedContactType ?? 'message');
@endphp

<form action="/contact/store" method="post" id="ez-contact-form">
    {{ csrf_field() }}
    <input type="hidden" name="contact_type" id="contactTypeInput" value="{{ $defaultContactType }}">

    {{-- ── Panel A: Send Message ─────────────────────── --}}
    <div class="cform-panel {{ $defaultContactType === 'message' ? 'active' : '' }}" data-panel="message">
        <div style="display:grid;grid-template-columns:repeat(2,1fr);column-gap:32px;row-gap:28px;">

            {{-- A1 Name --}}
            <label style="display:block;grid-column:span 1;">
                <div style="display:flex;justify-content:space-between;align-items:baseline;margin-bottom:4px;">
                    <span style="font-family:'JetBrains Mono',monospace;font-size:11px;
                        letter-spacing:0.12em;text-transform:uppercase;color:var(--muted);">
                        <span style="margin-right:8px;">A1</span>{{ trans('site.your_name') }}
                    </span>
                </div>
                <input type="text" name="name" value="{{ old('name') }}"
                    placeholder="{{ auth()->user()->full_name ?? 'Layla Ahmed' }}"
                    class="ez-input @error('name') border-red-400 @enderror"/>
                @error('name')<div style="font-size:12px;color:#e05252;margin-top:4px;">{{ $message }}</div>@enderror
            </label>

            {{-- A2 Email --}}
            <label style="display:block;grid-column:span 1;">
                <div style="display:flex;justify-content:space-between;align-items:baseline;margin-bottom:4px;">
                    <span style="font-family:'JetBrains Mono',monospace;font-size:11px;
                        letter-spacing:0.12em;text-transform:uppercase;color:var(--muted);">
                        <span style="margin-right:8px;">A2</span>{{ trans('public.email') }}
                    </span>
                </div>
                <input type="email" name="email" value="{{ old('email') }}"
                    placeholder="{{ auth()->user()->email ?? 'you@example.com' }}"
                    class="ez-input @error('email') border-red-400 @enderror"/>
                @error('email')<div style="font-size:12px;color:#e05252;margin-top:4px;">{{ $message }}</div>@enderror
            </label>

            {{-- A3 Phone --}}
            <label style="display:block;grid-column:span 1;">
                <div style="margin-bottom:4px;">
                    <span style="font-family:'JetBrains Mono',monospace;font-size:11px;
                        letter-spacing:0.12em;text-transform:uppercase;color:var(--muted);">
                        <span style="margin-right:8px;">A3</span>{{ trans('site.phone_number') }}
                        <span style="opacity:0.6;margin-left:6px;">(optional)</span>
                    </span>
                </div>
                <input type="tel" name="phone" value="{{ old('phone') }}"
                    placeholder="+20 1__ ___ ____"
                    class="ez-input @error('phone') border-red-400 @enderror"/>
                @error('phone')<div style="font-size:12px;color:#e05252;margin-top:4px;">{{ $message }}</div>@enderror
            </label>

            {{-- A4 Topic --}}
            <label style="display:block;grid-column:span 1;">
                <div style="margin-bottom:4px;">
                    <span style="font-family:'JetBrains Mono',monospace;font-size:11px;
                        letter-spacing:0.12em;text-transform:uppercase;color:var(--muted);">
                        <span style="margin-right:8px;">A4</span>What's it about?
                    </span>
                </div>
                <select name="topic" class="ez-input">
                    <option value="general">General question</option>
                    <option value="billing">Billing &amp; subscriptions</option>
                    <option value="tutor">A specific tutor</option>
                    <option value="partner">Partnerships / press</option>
                    <option value="other">Something else</option>
                </select>
            </label>

            {{-- A5 Message --}}
            <label style="display:block;grid-column:span 2;">
                <div style="display:flex;justify-content:space-between;align-items:baseline;margin-bottom:4px;">
                    <span style="font-family:'JetBrains Mono',monospace;font-size:11px;
                        letter-spacing:0.12em;text-transform:uppercase;color:var(--muted);">
                        <span style="margin-right:8px;">A5</span>{{ trans('site.message') }}
                    </span>
                    <span style="font-size:11px;color:var(--muted);opacity:0.7;">No character limit.</span>
                </div>
                <textarea name="message" rows="5"
                    placeholder="Hey Zatuna team — I wanted to ask about…"
                    class="ez-input @error('message') border-red-400 @enderror">{{ old('message') }}</textarea>
                @error('message')<div style="font-size:12px;color:#e05252;margin-top:4px;">{{ $message }}</div>@enderror
            </label>
        </div>

        @include('design_1.web.includes.captcha_input')

        <div style="margin-top:48px;padding-top:32px;border-top:1px solid var(--line);
            display:flex;justify-content:space-between;align-items:center;gap:24px;flex-wrap:wrap;">
            <label style="display:flex;align-items:center;gap:12px;font-size:13px;
                color:var(--muted);cursor:pointer;">
                <input type="checkbox" name="newsletter" value="1" style="accent-color:var(--ink);width:16px;height:16px;" checked>
                Keep me posted about new courses (optional)
            </label>
            <button type="submit" id="contactSubmitButton"
                class="btn-primary" style="padding:16px 28px;border-radius:999px;font-size:15px;
                font-weight:600;gap:10px;border:none;">
                Send message →
            </button>
        </div>
    </div>

    {{-- ── Panel B: Request Course ────────────────────── --}}
    <div class="cform-panel {{ $defaultContactType === 'request_course' ? 'active' : '' }}" data-panel="request_course">

        {{-- Info banner --}}
        <div style="background:var(--cream-2);border:1px solid var(--line);border-radius:16px;
            padding:20px;margin-bottom:40px;display:flex;align-items:center;gap:16px;">
            <div style="width:36px;height:36px;border-radius:50%;background:var(--citron);
                display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--ink)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 3v6"/><path d="M12 15v6"/><path d="M3 12h6"/><path d="M15 12h6"/><path d="M5.6 5.6l4.2 4.2"/><path d="M14.2 14.2l4.2 4.2"/><path d="M18.4 5.6l-4.2 4.2"/><path d="M9.8 14.2l-4.2 4.2"/></svg>
            </div>
            <p style="margin:0;font-size:15px;line-height:1.5;color:var(--ink);">
                We launch ~3 new university courses every month based on these requests.
                The more detail, the higher it scores.
            </p>
        </div>

        <div style="margin-bottom:32px;padding-bottom:16px;border-bottom:1px solid var(--line);
            font-family:'JetBrains Mono',monospace;font-size:11px;letter-spacing:0.14em;
            text-transform:uppercase;color:var(--muted);">
            Part 1 — About the course
        </div>

        <div style="display:grid;grid-template-columns:repeat(2,1fr);column-gap:32px;row-gap:28px;">

            {{-- B1 University --}}
            <label style="display:block;">
                <div style="margin-bottom:4px;">
                    <span style="font-family:'JetBrains Mono',monospace;font-size:11px;
                        letter-spacing:0.12em;text-transform:uppercase;color:var(--muted);">
                        <span style="margin-right:8px;">B1</span>University name
                    </span>
                </div>
                <input type="text" name="university_name" value="{{ old('university_name') }}"
                    placeholder="Cairo University"
                    class="ez-input @error('university_name') border-red-400 @enderror"/>
                @error('university_name')<div style="font-size:12px;color:#e05252;margin-top:4px;">{{ $message }}</div>@enderror
            </label>

            {{-- B2 College --}}
            <label style="display:block;">
                <div style="margin-bottom:4px;">
                    <span style="font-family:'JetBrains Mono',monospace;font-size:11px;
                        letter-spacing:0.12em;text-transform:uppercase;color:var(--muted);">
                        <span style="margin-right:8px;">B2</span>College / Faculty
                    </span>
                </div>
                <input type="text" name="college_name" value="{{ old('college_name') }}"
                    placeholder="Faculty of Engineering"
                    class="ez-input @error('college_name') border-red-400 @enderror"/>
                @error('college_name')<div style="font-size:12px;color:#e05252;margin-top:4px;">{{ $message }}</div>@enderror
            </label>

            {{-- B3 Field --}}
            <label style="display:block;">
                <div style="margin-bottom:4px;">
                    <span style="font-family:'JetBrains Mono',monospace;font-size:11px;
                        letter-spacing:0.12em;text-transform:uppercase;color:var(--muted);">
                        <span style="margin-right:8px;">B3</span>Field of study
                    </span>
                </div>
                <input type="text" name="study_field" value="{{ old('study_field') }}"
                    placeholder="e.g. Computer Science"
                    class="ez-input @error('study_field') border-red-400 @enderror"/>
                @error('study_field')<div style="font-size:12px;color:#e05252;margin-top:4px;">{{ $message }}</div>@enderror
            </label>

            {{-- B4 Course --}}
            <label style="display:block;">
                <div style="margin-bottom:4px;">
                    <span style="font-family:'JetBrains Mono',monospace;font-size:11px;
                        letter-spacing:0.12em;text-transform:uppercase;color:var(--muted);">
                        <span style="margin-right:8px;">B4</span>Course name &amp; code
                    </span>
                </div>
                <input type="text" name="course_name" value="{{ old('course_name') }}"
                    placeholder="CSE 305 — Operating Systems"
                    class="ez-input @error('course_name') border-red-400 @enderror"/>
                @error('course_name')<div style="font-size:12px;color:#e05252;margin-top:4px;">{{ $message }}</div>@enderror
            </label>

            {{-- B5 Study year --}}
            <div>
                <div style="margin-bottom:8px;">
                    <span style="font-family:'JetBrains Mono',monospace;font-size:11px;
                        letter-spacing:0.12em;text-transform:uppercase;color:var(--muted);">
                        <span style="margin-right:8px;">B5</span>Study year
                    </span>
                </div>
                <div style="display:flex;gap:8px;flex-wrap:wrap;">
                    @foreach(['1st','2nd','3rd','4th','5th'] as $yr => $label)
                    <button type="button" class="ez-pill {{ old('study_year') == ($yr+1) ? 'active' : '' }}"
                        onclick="setPill(this,'study_year','{{ $yr+1 }}')">
                        {{ $label }} year
                    </button>
                    @endforeach
                </div>
                <input type="hidden" name="study_year" id="study_year" value="{{ old('study_year') }}"/>
                @error('study_year')<div style="font-size:12px;color:#e05252;margin-top:4px;">{{ $message }}</div>@enderror
            </div>

            {{-- B6 Can provide materials --}}
            <div>
                <div style="margin-bottom:8px;">
                    <span style="font-family:'JetBrains Mono',monospace;font-size:11px;
                        letter-spacing:0.12em;text-transform:uppercase;color:var(--muted);">
                        <span style="margin-right:8px;">B6</span>Can you share materials?
                        <span style="opacity:0.6;margin-left:4px;">Slides, past papers, PDF</span>
                    </span>
                </div>
                <div style="display:flex;gap:8px;flex-wrap:wrap;">
                    @foreach([['yes','Yes, I can'],['partial','Some of them'],['no','No, sorry']] as $opt)
                    <button type="button" class="ez-pill {{ old('can_provide_materials') === $opt[0] ? 'active' : '' }}"
                        onclick="setPill(this,'can_provide_materials','{{ $opt[0] }}')">
                        {{ $opt[1] }}
                    </button>
                    @endforeach
                </div>
                <input type="hidden" name="can_provide_materials" id="can_provide_materials" value="{{ old('can_provide_materials') }}"/>
            </div>
        </div>

        <div style="margin:40px 0 32px;padding-bottom:16px;border-bottom:1px solid var(--line);
            font-family:'JetBrains Mono',monospace;font-size:11px;letter-spacing:0.14em;
            text-transform:uppercase;color:var(--muted);">
            Part 2 — About you
        </div>

        <div style="display:grid;grid-template-columns:repeat(2,1fr);column-gap:32px;row-gap:28px;">

            {{-- C1 Name --}}
            <label style="display:block;">
                <div style="margin-bottom:4px;">
                    <span style="font-family:'JetBrains Mono',monospace;font-size:11px;
                        letter-spacing:0.12em;text-transform:uppercase;color:var(--muted);">
                        <span style="margin-right:8px;">C1</span>Your name
                    </span>
                </div>
                <input type="text" name="name" value="{{ old('name') }}"
                    placeholder="{{ auth()->user()->full_name ?? 'Your name' }}"
                    class="ez-input"/>
            </label>

            {{-- C2 Email --}}
            <label style="display:block;">
                <div style="margin-bottom:4px;">
                    <span style="font-family:'JetBrains Mono',monospace;font-size:11px;
                        letter-spacing:0.12em;text-transform:uppercase;color:var(--muted);">
                        <span style="margin-right:8px;">C2</span>Email
                    </span>
                </div>
                <input type="email" name="email" value="{{ old('email') }}"
                    placeholder="{{ auth()->user()->email ?? 'you@example.com' }}"
                    class="ez-input"/>
            </label>

            {{-- C3 Phone --}}
            <label style="display:block;grid-column:span 2;">
                <div style="margin-bottom:4px;">
                    <span style="font-family:'JetBrains Mono',monospace;font-size:11px;
                        letter-spacing:0.12em;text-transform:uppercase;color:var(--muted);">
                        <span style="margin-right:8px;">C3</span>Phone (optional)
                    </span>
                </div>
                <input type="tel" name="phone" value="{{ old('phone') }}"
                    placeholder="+20 1__ ___ ____" class="ez-input"/>
            </label>

            {{-- C4 Extra notes --}}
            <label style="display:block;grid-column:span 2;">
                <div style="margin-bottom:4px;">
                    <span style="font-family:'JetBrains Mono',monospace;font-size:11px;
                        letter-spacing:0.12em;text-transform:uppercase;color:var(--muted);">
                        <span style="margin-right:8px;">C4</span>Anything else we should know?
                    </span>
                </div>
                <textarea name="message" rows="4"
                    placeholder="e.g. exam date, professor's name, what you struggle with…"
                    class="ez-input">{{ old('message') }}</textarea>
            </label>
        </div>

        @include('design_1.web.includes.captcha_input')

        <div style="margin-top:48px;padding-top:32px;border-top:1px solid var(--line);
            display:flex;justify-content:space-between;align-items:center;gap:24px;flex-wrap:wrap;">
            <label style="display:flex;align-items:center;gap:12px;font-size:13px;
                color:var(--muted);cursor:pointer;">
                <input type="checkbox" name="newsletter" value="1" style="accent-color:var(--ink);width:16px;height:16px;" checked>
                Keep me posted about new courses (optional)
            </label>
            <button type="submit" id="contactSubmitButton"
                class="btn-primary" style="padding:16px 28px;border-radius:999px;font-size:15px;
                font-weight:600;gap:10px;border:none;">
                Submit request →
            </button>
        </div>
    </div>
</form>

<script>
function setPill(btn, fieldId, value) {
    var group = btn.closest('div');
    group.querySelectorAll('.ez-pill').forEach(function(p) { p.classList.remove('active'); });
    btn.classList.add('active');
    var hidden = document.getElementById(fieldId);
    if (hidden) hidden.value = value;
}

@if(session()->has('toast') && (session('toast.status') ?? null) === 'success')
    document.addEventListener('DOMContentLoaded', function() {
        var form = document.getElementById('ez-contact-form');
        if (form) form.reset();
    });
@endif
</script>
