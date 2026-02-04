@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (file_exists(public_path('elzatuna_logo.png')))
<img src="{{ asset('elzatuna_logo.png') }}" class="logo" alt="El Zatuna Logo" style="height: 80px; width: auto; max-width: 280px; display: block;">
@else
<span style="color: #FAFFE0; font-size: 28px; font-weight: bold; font-family: 'Segoe UI', Arial, sans-serif; letter-spacing: 1.5px;">
    {{ config('app.name') }}
</span>
@endif
</a>
</td>
</tr>
