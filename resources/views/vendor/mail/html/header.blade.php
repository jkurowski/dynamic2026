<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="https://www.developro.pl/images/logo-biale.png" class="logo" alt="DeveloPro" style="width:220px;height:63px">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
