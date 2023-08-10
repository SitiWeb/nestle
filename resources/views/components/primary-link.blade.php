@php
if (!isset($bgColor)) {
    $bgColor = 'black';
}

switch($bgColor) {
    case "red":
        $bgClass = 'bg-red-700';
        break;
    default:
        $bgClass = 'bg-black';
        break;
}
@endphp
<a href="{{ $href }}" {{ $attributes->merge(['class' => 'inline-flex items-center px-4 py-2 border border-transparent rounded font-semibold text-xs text-white uppercase justify-center tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 ' . $bgClass]) }}">
    {{ $text }}
</a>
<span style="display:none" class="bg-red-700">
</span>