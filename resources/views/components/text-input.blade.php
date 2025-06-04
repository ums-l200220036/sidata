@props(['disabled' => false])

<input @disabled($disabled)
    {{ $attributes->merge([
        'class' =>
            'border-gray-300 focus:border-[#FE482B] focus:ring-[#FE482B] rounded-md shadow-sm]'
    ]) }}>
