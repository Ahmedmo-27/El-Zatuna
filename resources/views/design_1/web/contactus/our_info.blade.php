<aside style="background:var(--ink);color:var(--cream);border-radius:24px;
    padding:32px;position:sticky;top:100px;">

    <div style="font-family:'JetBrains Mono',monospace;font-size:11px;letter-spacing:0.14em;
        text-transform:uppercase;color:var(--citron);">
        — Direct lines
    </div>
    <h3 class="serif" style="font-size:34px;line-height:1.05;letter-spacing:-0.015em;
        margin:18px 0 0;font-weight:400;color:var(--cream);">
        Or skip the<br>form entirely.
    </h3>

    <div style="margin-top:36px;">

        {{-- Phone --}}
        <div style="padding:24px 0;border-top:1px solid rgba(250,255,224,0.12);
            display:flex;gap:16px;align-items:flex-start;">
            <div style="width:42px;height:42px;border-radius:12px;background:var(--citron);
                color:var(--ink);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 4h3l2 5-2.5 1.5a11 11 0 0 0 6 6L15 14l5 2v3a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2z"/></svg>
            </div>
            <div style="flex:1;">
                <div style="font-family:'JetBrains Mono',monospace;font-size:10px;
                    letter-spacing:0.14em;text-transform:uppercase;color:rgba(250,255,224,0.6);">
                    Call us
                </div>
                @if(!empty($contactSettings['phones']))
                    @foreach(explode(',', $contactSettings['phones']) as $phone)
                    <div style="margin-top:8px;">
                        <a href="tel:{{ trim($phone) }}" class="serif"
                            style="font-size:19px;letter-spacing:-0.005em;line-height:1.25;
                            color:var(--cream);text-decoration:none;">{{ trim($phone) }}</a>
                        <div style="font-size:12px;margin-top:4px;color:rgba(250,255,224,0.55);">
                            Sun–Thu, 10am–8pm Cairo
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="serif" style="font-size:19px;margin-top:8px;color:var(--cream);">
                        {{ trans('site.not_defined') }}
                    </div>
                @endif
            </div>
        </div>

        {{-- Email --}}
        <div style="padding:24px 0;border-top:1px solid rgba(250,255,224,0.12);
            display:flex;gap:16px;align-items:flex-start;">
            <div style="width:42px;height:42px;border-radius:12px;background:var(--citron);
                color:var(--ink);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 7l9 7 9-7"/></svg>
            </div>
            <div style="flex:1;">
                <div style="font-family:'JetBrains Mono',monospace;font-size:10px;
                    letter-spacing:0.14em;text-transform:uppercase;color:rgba(250,255,224,0.6);">
                    Email us
                </div>
                @php
                    $contactEmails = [];
                    if (!empty($contactSettings['emails'])) {
                        $contactEmails = explode(',', $contactSettings['emails']);
                    }
                    $contactEmails[] = 'support@elzatuna.com';
                    $contactEmails = array_values(array_unique(array_filter(array_map('trim', $contactEmails))));
                @endphp
                @foreach($contactEmails as $email)
                <div style="margin-top:8px;">
                    <a href="mailto:{{ $email }}" class="serif"
                        style="font-size:17px;letter-spacing:-0.005em;line-height:1.25;
                        color:var(--cream);text-decoration:none;word-break:break-all;">{{ $email }}</a>
                    @if($loop->first)
                    <div style="font-size:12px;margin-top:4px;color:rgba(250,255,224,0.55);">
                        General · billing · feedback
                    </div>
                    @endif
                </div>
                @endforeach
                <div style="margin-top:8px;">
                    <a href="mailto:tutors@elzatuna.com" class="serif"
                        style="font-size:17px;letter-spacing:-0.005em;line-height:1.25;
                        color:var(--cream);text-decoration:none;">tutors@elzatuna.com</a>
                    <div style="font-size:12px;margin-top:4px;color:rgba(250,255,224,0.55);">
                        To become a tutor
                    </div>
                </div>
            </div>
        </div>

        {{-- Address --}}
        @if(!empty($contactSettings['address']))
        <div style="padding:24px 0;border-top:1px solid rgba(250,255,224,0.12);
            display:flex;gap:16px;align-items:flex-start;">
            <div style="width:42px;height:42px;border-radius:12px;background:var(--citron);
                color:var(--ink);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 22s7-6.5 7-12a7 7 0 1 0-14 0c0 5.5 7 12 7 12z"/><circle cx="12" cy="10" r="2.5"/></svg>
            </div>
            <div style="flex:1;">
                <div style="font-family:'JetBrains Mono',monospace;font-size:10px;
                    letter-spacing:0.14em;text-transform:uppercase;color:rgba(250,255,224,0.6);">
                    Visit
                </div>
                <div class="serif" style="font-size:17px;margin-top:8px;line-height:1.3;color:var(--cream);">
                    {{ $contactSettings['address'] }}
                </div>
                <div style="font-size:12px;margin-top:4px;color:rgba(250,255,224,0.55);">
                    By appointment
                </div>
            </div>
        </div>
        @endif

        {{-- Live chat indicator --}}
        <div style="margin-top:28px;padding:20px;background:rgba(200,205,6,0.1);
            border:1px solid rgba(200,205,6,0.25);border-radius:14px;">
            <div style="display:flex;align-items:center;gap:10px;font-family:'JetBrains Mono',monospace;
                font-size:10px;letter-spacing:0.14em;text-transform:uppercase;color:var(--citron);">
                <span style="width:7px;height:7px;border-radius:50%;background:var(--citron);
                    animation:pulse 2s ease-in-out infinite;"></span>
                <style>@keyframes pulse{0%,100%{opacity:1}50%{opacity:0.4}}</style>
                Online now
            </div>
            <p style="margin:12px 0 0;font-size:13px;line-height:1.5;color:rgba(250,255,224,0.85);">
                Replying to messages in ~48h. No bots, no canned replies.
            </p>
        </div>
    </div>
</aside>
