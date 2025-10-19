@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-gray-300 focus:border-camture-amaranth focus:ring-camture-amaranth rounded-md shadow-sm']) !!}>