<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="text-center">
                <h1 class="text-2xl">{{ $user->name }}</h1>
                <div>
                    게시물 {{ $user->articles->count() }}
                    구독자 {{ $user->followers()->count() }}
                </div>
                @if(Auth::id() != $user->id)
                <div>
                    @if(Auth::user()->isFollowing($user))
                        <form method="POST" action="{{ route('unfollow', ['user' => $user->username]) }}">
                            @csrf
                            @method('delete')
                            <x-danger-button>구독해지</x-danger-button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('follow', ['user' => $user->username]) }}">
                            @csrf
                            <x-primary-button>구독하기</x-primary-button>
                        </form>
                    @endif
                </div>
                @endif
            </div>

            <div>
                @forelse($user->articles as $article)
                    <x-list-article-item :article="$article" />
                @empty
                    게시물이 없습니다.
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
