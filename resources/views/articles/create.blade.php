<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('글쓰기') }}
        </h2>
    </x-slot>

    <div class="container p-5">
        <form action="{{ route('articles.store') }}" method="POST" class="mt-5">
            @csrf
            <input type="text" name="body" class="block w-full mb-2 rounded" value="{{ old('body') }}">
            @error('body')
                <p class="text-xs text-red-500 mb-3">{{ $message }}</p>
            @enderror
            <button class="py-1 px-3 bg-black text-white rounded text-xs">저장하기</button>
        </form>
    </div>
</x-app-layout>