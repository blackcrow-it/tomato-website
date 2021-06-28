<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="https://laravel.com/img/notification-logo.png" class="logo" alt="Laravel Logo">
@else
<img src="{{ $url }}/images/logo-email.png" class="logo" width="100%" alt="{{ $slot }} Logo">
@endif
</a>
</td>
</tr>
