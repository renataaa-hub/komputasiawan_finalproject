@props(['type' => 'draft'])

@php
    $user = Auth::user();
    $current = $type === 'draft' ? $user->getCurrentDrafts() : $user->getCurrentPublications();
    $limit = $type === 'draft' ? $user->getDraftLimit() : $user->getPublicationLimit();
    $isUnlimited = $limit === '∞';
    $percentage = $isUnlimited ? 0 : ($current / (int)$limit) * 100;
    $isNearLimit = $percentage >= 80;
    $isAtLimit = !$isUnlimited && $current >= (int)$limit;
@endphp

@if($isAtLimit)
<div class="bg-red-50 border border-red-200 rounded-lg p-4">
    <div class="flex items-start gap-3">
        <svg class="w-5 h-5 text-red-600 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
        </svg>
        <div class="flex-1">
            <p class="text-red-800 font-bold">Batas {{ $type === 'draft' ? 'Draft' : 'Publikasi' }} Tercapai!</p>
            <p class="text-red-700 text-sm mt-1">Anda sudah mencapai batas maksimal ({{ $current }}/{{ $limit }})</p>
            <a href="{{ route('subscription') }}" class="inline-block mt-2 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 text-sm font-medium">
                Upgrade Sekarang
            </a>
        </div>
    </div>
</div>
@elseif($isNearLimit)
<div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
    <div class="flex items-start gap-3">
        <svg class="w-5 h-5 text-yellow-600 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
        </svg>
        <div class="flex-1">
            <p class="text-yellow-800 font-medium">Mendekati Batas!</p>
            <p class="text-yellow-700 text-sm mt-1">{{ $type === 'draft' ? 'Draft' : 'Publikasi' }}: {{ $current }}/{{ $limit }}</p>
            <a href="{{ route('subscription') }}" class="text-yellow-800 hover:underline text-sm font-medium">
                Upgrade untuk unlimited →
            </a>
        </div>
    </div>
</div>
@endif