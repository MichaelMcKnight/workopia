<x-layout>
    <div class="grid grid-cols-1 gap-4 mb-6 md:grid-cols-3">
        @forelse($jobs as $job)
            <x-job-card :job="$job" />
        @empty
            <p>No jobs available at this time.</p>
        @endforelse
    </div>
    {{-- Paginatio Links --}}
    {{$jobs->links()}}
</x-layout>
