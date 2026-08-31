{{-- resources/views/components/logistics-form.blade.php --}}

<div class="grid grid-cols-1 gap-5">

    <div>
        <label
            for="{{ $branchName }}"
            class="block text-xs font-medium text-slate-700 sm:text-sm"
        >
            {{ $branchLabel }}
        </label>

        <select
            id="{{ $branchName }}"
            name="{{ $branchName }}"
            required
            class="mt-1.5 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-xs text-slate-800 shadow-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 sm:text-sm"
        >
            <option value="">
                Selecione a filial
            </option>

            @foreach ($branches as $state => $stateBranches)

                <optgroup label="{{ $state }}">

                    @foreach ($stateBranches as $branch)

                        <option
                            value="{{ $branch->id }}"
                            @selected(old($branchName, $selectedBranchId) == $branch->id)
                        >
                            {{ $branch->name }}
                        </option>

                    @endforeach

                </optgroup>

            @endforeach
        </select>

        @error($branchName)
            <p class="mt-1 text-xs text-red-600 sm:mt-1.5 sm:text-sm">
                {{ $message }}
            </p>
        @enderror
    </div>


    <div>
        <label
            for="invoice_number"
            class="block text-xs font-medium text-slate-700 sm:text-sm"
        >
            {{ $invoiceLabel }}
        </label>

        <input
            type="text"
            id="invoice_number"
            name="invoice_number"
            value="{{ old('invoice_number', $invoiceNumber) }}"
            maxlength="50"
            class="mt-1.5 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-xs text-slate-800 shadow-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 sm:text-sm"
        >

        @error('invoice_number')
            <p class="mt-1 text-xs text-red-600 sm:mt-1.5 sm:text-sm">
                {{ $message }}
            </p>
        @enderror
    </div>

</div>
