@props(['columns' => []])

<div class="overflow-x-auto rounded-xl border">
    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
        <thead class="bg-gray-50 dark:bg-zinc-900">
            <tr>
                @foreach ($columns as $column)
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium tracking-wider">
                        {{ $column }}
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody class="bg-white dark:bg-zinc-800 divide-y divide-gray-200 dark:divide-gray-700">
            {{ $slot }}
        </tbody>
    </table>
</div>
