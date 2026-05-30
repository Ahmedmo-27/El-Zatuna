<footer style="background:var(--ink);color:var(--cream);padding:80px 0 0;margin-top:80px;">
    <div class="ez-container">

        {{-- Main grid --}}
        <div style="display:grid;grid-template-columns:1.4fr 1fr 1fr 1fr;gap:60px;
            padding-bottom:60px;border-bottom:1px solid rgba(250,255,224,0.12);"
            class="md:!grid-cols-2 sm:!grid-cols-1">

            {{-- Brand column --}}
            <div>
                <span style="font-family:'Instrument Serif','Playfair Display',serif;font-size:32px;
                    color:var(--cream);letter-spacing:-0.02em;line-height:1;font-weight:400;
                    display:inline-flex;align-items:baseline;gap:0.06em;">
                    <em style="font-style:italic;">el</em>zatuna<span style="display:inline-block;
                        width:6px;height:6px;background:var(--citron);border-radius:50%;
                        margin-left:0.05em;align-self:center;"></span>
                </span>
                <p style="margin-top:24px;color:rgba(250,255,224,0.7);font-size:15px;
                    line-height:1.6;max-width:340px;">
                    University-grade tutoring, study materials and one-on-one
                    mentorship — built around your syllabus, not someone else's.
                </p>

                {{-- Social links --}}
                <div style="margin-top:28px;display:flex;gap:10px;flex-wrap:wrap;">
                    @php
                        $socials = [
                            ['https://instagram.com/elzatuna', 'Instagram', '<path d="M16 2H8a6 6 0 0 0-6 6v8a6 6 0 0 0 6 6h8a6 6 0 0 0 6-6V8a6 6 0 0 0-6-6z"/><circle cx="12" cy="12" r="3.5"/><circle cx="17" cy="7" r="1" fill="currentColor" stroke="none"/>'],
                            ['#', 'TikTok', '<path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-2.88 2.5 2.89 2.89 0 0 1-2.89-2.89 2.89 2.89 0 0 1 2.89-2.89c.28 0 .54.04.79.1V9.01a6.33 6.33 0 0 0-.79-.05 6.34 6.34 0 0 0-6.34 6.34 6.34 6.34 0 0 0 6.34 6.34 6.34 6.34 0 0 0 6.33-6.34V8.7a8.16 8.16 0 0 0 4.77 1.52V6.76a4.85 4.85 0 0 1-1-.07z"/>'],
                            ['#', 'LinkedIn', '<rect x="2" y="2" width="20" height="20" rx="4"/><path d="M7 10v7"/><circle cx="7" cy="7" r="1.5" fill="currentColor" stroke="none"/><path d="M11 10v7"/><path d="M11 13a3 3 0 0 1 6 0v4"/>'],
                            ['#', 'YouTube', '<path d="M22.54 6.42a2.78 2.78 0 0 0-1.95-1.96C18.88 4 12 4 12 4s-6.88 0-8.59.46A2.78 2.78 0 0 0 1.46 6.42 29 29 0 0 0 1 12a29 29 0 0 0 .46 5.58 2.78 2.78 0 0 0 1.95 1.96C5.12 20 12 20 12 20s6.88 0 8.59-.46a2.78 2.78 0 0 0 1.95-1.96A29 29 0 0 0 23 12a29 29 0 0 0-.46-5.58z"/><polygon points="9.75,15.02 15.5,12 9.75,8.98 9.75,15.02" fill="currentColor" stroke="none"/>'],
                        ];
                    @endphp
                    @foreach($socials as $soc)
                    <a href="{{ $soc[0] }}" target="_blank" rel="noopener" aria-label="{{ $soc[1] }}"
                        style="width:38px;height:38px;border-radius:50%;
                        border:1px solid rgba(250,255,224,0.2);display:inline-flex;
                        align-items:center;justify-content:center;
                        color:rgba(250,255,224,0.7);text-decoration:none;
                        transition:border-color .2s ease,color .2s ease;"
                        onmouseover="this.style.borderColor='var(--citron)';this.style.color='var(--citron)'"
                        onmouseout="this.style.borderColor='rgba(250,255,224,0.2)';this.style.color='rgba(250,255,224,0.7)'">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            {!! $soc[2] !!}
                        </svg>
                    </a>
                    @endforeach
                </div>
            </div>

            {{-- Learn --}}
            <div>
                <h4 style="font-size:12px;letter-spacing:0.12em;text-transform:uppercase;
                    color:var(--citron);margin:0 0 22px;font-weight:600;
                    font-family:'JetBrains Mono',monospace;">Learn</h4>
                <ul style="list-style:none;padding:0;margin:0;display:grid;gap:14px;">
                    <li><a href="/classes" class="uline" style="font-size:15px;color:rgba(250,255,224,0.85);text-decoration:none;">All courses</a></li>
                    <li><a href="/classes?free=true" class="uline" style="font-size:15px;color:rgba(250,255,224,0.85);text-decoration:none;">Free courses</a></li>
                    <li><a href="/#plans" class="uline" style="font-size:15px;color:rgba(250,255,224,0.85);text-decoration:none;">Subscription plans</a></li>
                    @guest
                    <li><a href="/register" class="uline" style="font-size:15px;color:rgba(250,255,224,0.85);text-decoration:none;">Become a student</a></li>
                    @endguest
                </ul>
            </div>

            {{-- Teach --}}
            <div>
                <h4 style="font-size:12px;letter-spacing:0.12em;text-transform:uppercase;
                    color:var(--citron);margin:0 0 22px;font-weight:600;
                    font-family:'JetBrains Mono',monospace;">Teach</h4>
                <ul style="list-style:none;padding:0;margin:0;display:grid;gap:14px;">
                    <li><a href="/become-instructor" class="uline" style="font-size:15px;color:rgba(250,255,224,0.85);text-decoration:none;">Become an instructor</a></li>
                    <li><a href="#" class="uline" style="font-size:15px;color:rgba(250,255,224,0.85);text-decoration:none;">Instructor handbook</a></li>
                    <li><a href="#" class="uline" style="font-size:15px;color:rgba(250,255,224,0.85);text-decoration:none;">Payouts</a></li>
                    <li><a href="#" class="uline" style="font-size:15px;color:rgba(250,255,224,0.85);text-decoration:none;">Community</a></li>
                </ul>
            </div>

            {{-- Company + Newsletter --}}
            <div>
                <h4 style="font-size:12px;letter-spacing:0.12em;text-transform:uppercase;
                    color:var(--citron);margin:0 0 22px;font-weight:600;
                    font-family:'JetBrains Mono',monospace;">Company</h4>
                <ul style="list-style:none;padding:0;margin:0 0 32px;display:grid;gap:14px;">
                    <li><a href="/about" class="uline" style="font-size:15px;color:rgba(250,255,224,0.85);text-decoration:none;">About</a></li>
                    <li><a href="/contact" class="uline" style="font-size:15px;color:rgba(250,255,224,0.85);text-decoration:none;">Contact</a></li>
                    <li><a href="/faq" class="uline" style="font-size:15px;color:rgba(250,255,224,0.85);text-decoration:none;">FAQ</a></li>
                    <li><a href="/privacy" class="uline" style="font-size:15px;color:rgba(250,255,224,0.85);text-decoration:none;">Privacy</a></li>
                    <li><a href="/terms" class="uline" style="font-size:15px;color:rgba(250,255,224,0.85);text-decoration:none;">Terms</a></li>
                </ul>

                {{-- Newsletter --}}
                <div style="border-top:1px solid rgba(250,255,224,0.12);padding-top:24px;">
                    <div style="font-family:'JetBrains Mono',monospace;font-size:11px;
                        letter-spacing:0.12em;text-transform:uppercase;color:rgba(250,255,224,0.6);
                        margin-bottom:12px;">Newsletter</div>
                    <p style="font-size:13px;color:rgba(250,255,224,0.6);margin:0 0 14px;line-height:1.5;">
                        You read this far — might as well sign up.
                    </p>
                    <div class="js-newsletter-form" style="display:flex;flex-direction:column;gap:10px;">
                        <input type="text" name="first_name" placeholder="First name"
                            style="background:transparent;border:none;border-bottom:1px solid rgba(250,255,224,0.3);
                            outline:none;padding:8px 0;font-size:14px;color:var(--cream);
                            transition:border-color .2s ease;"
                            onfocus="this.style.borderBottomColor='var(--citron)'"
                            onblur="this.style.borderBottomColor='rgba(250,255,224,0.3)'"
                            class="placeholder:text-[rgba(250,255,224,0.45)]" />
                        <div class="form-group mb-0">
                            <input type="email" name="newsletter_email" placeholder="your@email.com"
                                style="background:transparent;border:none;border-bottom:1px solid rgba(250,255,224,0.3);
                                outline:none;padding:8px 0;font-size:14px;color:var(--cream);width:100%;
                                transition:border-color .2s ease;"
                                onfocus="this.style.borderBottomColor='var(--citron)'"
                                onblur="this.style.borderBottomColor='rgba(250,255,224,0.3)'"
                                class="js-ajax-newsletter_email placeholder:text-[rgba(250,255,224,0.45)]" />
                            <div class="invalid-feedback d-block"></div>
                        </div>
                        <button type="button" class="js-submit-newsletter-btn"
                            style="background:var(--citron);color:var(--ink);font-weight:600;
                            padding:10px 20px;border-radius:999px;font-size:13px;border:none;
                            cursor:pointer;text-align:center;margin-top:6px;
                            transition:background .2s ease;"
                            onmouseover="this.style.background='#BDEA42'"
                            onmouseout="this.style.background='var(--citron)'">
                            Sign up →
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Big logotype --}}
        <div style="display:flex;justify-content:space-between;align-items:flex-end;padding-top:32px;">
            <div style="font-family:'Instrument Serif','Playfair Display',serif;
                font-size:clamp(80px,14vw,200px);line-height:0.8;letter-spacing:-0.04em;
                color:var(--citron);">
                <em style="font-style:italic;">el</em>zatuna<span style="color:var(--leaf);">.</span>
            </div>
        </div>

        {{-- Copyright --}}
        <div style="margin-top:24px;padding:24px 0 32px;display:flex;justify-content:space-between;
            flex-wrap:wrap;gap:8px;font-size:12px;color:rgba(250,255,224,0.5);
            font-family:'JetBrains Mono',monospace;border-top:1px solid rgba(250,255,224,0.08);">
            <span>© {{ date('Y') }} EL ZATUNA EDUCATION LTD.</span>
            <span>MADE WITH OLIVES — CAIRO / ALEXANDRIA</span>
        </div>
    </div>
</footer>
