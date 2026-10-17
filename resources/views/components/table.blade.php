@props(['columns' => []])

<div {{ $attributes->class('overflow-x-auto rounded-xl border') }}>
    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
        <thead class="bg-gray-50 dark:bg-zinc-900">
            <tr>
                @isset($head)
                    {{ $head }}
                @else
                    @foreach ($columns as $column)
                        <x-ui::table.heading>{{ $column }}</x-ui::table.heading>
                    @endforeach
                @endisset
            </tr>
        </thead>
        <tbody class="bg-white dark:bg-zinc-800 divide-y divide-gray-200 dark:divide-gray-700">
            {{ $slot }}
        </tbody>
    </table>
    {{ $empty ?? '' }}
</div>
