@extends('layouts.admin')

@section('page-title', $message->subject)

@section('content')
    <div class="bg-white rounded-2xl border border-slate-100 shadow-card p-6 max-w-2xl">
        <div class="mb-6 text-sm text-slate-500">
            <p><strong>From:</strong> {{ $message->name }} ({{ $message->email }})</p>
            <p><strong>Date:</strong> {{ $message->created_at->format('M d, Y H:i') }}</p>
        </div>
        <div class="prose prose-slate">{{ nl2br(e($message->message)) }}</div>
        <form action="{{ route('admin.messages.destroy', $message) }}" method="POST" class="mt-6" onsubmit="return confirm('Delete message?')">
            @csrf @method('DELETE')
            <button type="submit" class="text-red-600 text-sm font-medium hover:underline">Delete Message</button>
        </form>
    </div>
@endsection
