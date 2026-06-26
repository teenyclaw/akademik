@if (session('success'))
    <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-md">{{ session('success') }}</div>
@endif
@if (session('error'))
    <div class="mb-4 p-4 bg-red-100 text-red-800 rounded-md">{{ session('error') }}</div>
@endif