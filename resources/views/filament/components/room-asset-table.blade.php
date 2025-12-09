@php
    $filteredAssets = $assets->filter(fn($asset) => $asset->stock > 0);
@endphp

<div class="overflow-y-auto max-h-[70vh]">
    <table class="min-w-full text-sm text-left border border-gray-200 rounded-lg overflow-hidden">
        <thead class="bg-gray-50 text-gray-700 font-semibold">
            <tr>
                <th class="px-4 py-2 border w-12 text-center">No</th>
                <th class="px-4 py-2 border">Jenis Ruangan</th>
                <th class="px-4 py-2 border">Ruangan</th>
                <th class="px-4 py-2 border text-right">Jumlah</th>
                <th class="px-4 py-2 border">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($filteredAssets as $index => $asset)
                <tr class="border-t hover:bg-gray-50">
                    <td class="px-4 py-2 border text-center">{{ $index + 1 }}</td>
                    <td class="px-4 py-2 border">{{ $asset->room->room_type->name ?? '-' }}</td>
                    <td class="px-4 py-2 border">{{ $asset->room->name ?? '-' }}</td>
                    <td class="px-4 py-2 border text-center">{{ $asset->stock }}</td>
                    <td class="px-4 py-2 border">{{ $asset->description ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>

        <tfoot>
            <tr class="bg-gray-100 font-semibold">
                <td colspan="3" class="text-end px-4 py-2 border">Grand Total</td>
                <td class="px-4 py-2 border text-right">
                    {{ number_format($filteredAssets->sum('stock'), 0, ',', '.') }}
                </td>
                <td class="px-4 py-2 border"></td>
            </tr>
        </tfoot>
    </table>
</div>
