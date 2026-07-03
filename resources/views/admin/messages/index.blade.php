@extends('layouts.admin')

@section('page-title', 'Messages')

@section('content')
    <x-reveal type="fade-up">
        <x-admin-page-header :count="$messages->total()" count-label="messages" subtitle="Contact form submissions from your storefront" />
    </x-reveal>

    <x-reveal type="fade-up" delay="80">
        <div class="admin-card divide-y divide-slate-50">
            @forelse($messages as $message)
                <a href="{{ route('admin.messages.show', $message) }}"
                   class="block px-6 py-5 transition-all duration-300 hover:bg-brand-50/50 group {{ !$message->is_read ? 'bg-brand-50/30 border-l-4 border-brand-500' : 'hover:pl-8' }}">
                    <div class="flex justify-between items-start gap-4">
                        <div class="min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                @if(!$message->is_read)
                                    <span class="w-2 h-2 rounded-full bg-brand-500 animate-pulse-soft"></span>
                                @endif
                                <p class="font-semibold text-slate-900 group-hover:text-brand-700 transition-colors truncate">{{ $message->subject }}</p>
                            </div>
                            <p class="text-sm text-slate-500">{{ $message->name }} · {{ $message->email }}</p>
                            <p class="text-sm text-slate-400 mt-1 line-clamp-1">{{ \Illuminate\Support\Str::limit($message->message, 80) }}</p>
                        </div>
                        <span class="shrink-0 text-xs text-slate-400 group-hover:text-brand-600 transition-colors">{{ $message->created_at->diffForHumans() }}</span>
                    </div>
                </a>
            @empty
                <div class="p-16 text-center text-slate-500">
                    <x-icon name="mail" class="w-14 h-14 mx-auto mb-3 text-slate-300" />
                    <p>No messages yet. They'll appear when customers use the contact form.</p>
                </div>
            @endforelse
        </div>
    </x-reveal>

    <div class="mt-6">{{ $messages->links() }}</div>
@endsection
