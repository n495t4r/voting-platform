@extends('layouts.voting')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    {{-- Include the dynamic breadcrumb component --}}
    @include('admin.components._breadcrumb')
    <div class="mb-6">
        <h1 class="text-4xl font-extrabold text-gray-900 tracking-tight">Manage Organizations</h1>
        <p class="mt-1 text-lg text-gray-600">Create and manage your organizations for elections.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Organizations List -->
        <div class="bg-white shadow-xl rounded-lg p-6">
            <h3 class="text-xl font-bold text-gray-900 mb-4">Your Organizations</h3>
            @forelse($organizations as $organization)
                <div class="border-b border-gray-200 last:border-b-0 py-4 flex items-center justify-between">
                    <div>
                        <h4 class="font-bold text-lg text-gray-800">{{ $organization->name }}</h4>
                        <p class="text-sm text-gray-600 mt-1">{{ $organization->description ?? 'No description' }}</p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('admin.elections.index', ['organization' => $organization->slug]) }}" class="inline-flex items-center px-3 py-1 bg-indigo-100 text-indigo-700 font-medium text-xs rounded-full hover:bg-indigo-200 transition-colors">
                            Elections ({{ $organization->elections->count() }})
                        </a>
                        <button
                            type="button"
                            class="p-2 rounded-full text-gray-500 hover:bg-gray-100 transition-colors"
                            onclick="editOrganization({{ $organization->id }}, '{{ addslashes($organization->name) }}', '{{ addslashes($organization->description) }}')"
                        >
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                            </svg>
                        </button>
                        <button type="button" class="p-2 rounded-full text-red-500 hover:bg-red-100 transition-colors" onclick="showDeleteModal({{ $organization->id }})">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                </div>
            @empty
                <p class="text-center text-gray-500 py-4">You have not created any organizations yet.</p>
            @endforelse
        </div>

        <!-- Add/Edit Organization Form -->
        <div class="bg-white shadow-xl rounded-lg p-6">
            <h3 class="text-xl font-bold text-gray-900 mb-4" id="form-title">Create New Organization</h3>
            <form id="organization-form" action="{{ route('admin.organizations.store') }}" method="POST" class="space-y-6">
                @csrf
                <input type="hidden" name="_method" id="form-method" value="POST">
                <input type="hidden" name="organization_id" id="organization_id">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" name="name" id="name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" id="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                </div>
                <div class="flex flex-col space-y-2">
                    <button type="submit" id="form-submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                        Create Organization
                    </button>
                    <button type="button" id="form-cancel" class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors hidden" onclick="resetForm()">
                        Cancel Edit
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Deletion Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-gray-900 bg-opacity-75 z-50 hidden flex justify-center items-center">
    <div class="bg-white rounded-xl shadow-2xl p-8 m-4 max-w-sm w-full transform transition-all duration-300 scale-95 opacity-0">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-bold text-gray-900">Confirm Deletion</h3>
            <button type="button" class="text-gray-400 hover:text-gray-600 transition" onclick="hideDeleteModal()">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <div class="text-gray-700 text-center">
            <p class="font-semibold mb-4">Are you sure you want to delete this organization?</p>
            <p>This action cannot be undone.</p>
        </div>
        <div class="mt-6 flex justify-center space-x-4">
            <button type="button" class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 transition-colors" onclick="hideDeleteModal()">
                Cancel
            </button>
            <button type="button" id="confirmDeleteBtn" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 transition-colors">
                Delete
            </button>
        </div>
    </div>
</div>

<form id="delete-form" method="POST" class="hidden">
    @csrf
    @method('DELETE')
</form>

<script>
    function editOrganization(id, name, description) {
        document.getElementById('form-title').innerText = 'Edit Organization';
        document.getElementById('name').value = name;
        document.getElementById('description').value = description;
        document.getElementById('form-submit').innerText = 'Update Organization';
        document.getElementById('form-cancel').classList.remove('hidden');
        document.getElementById('form-method').value = 'PUT';
        document.getElementById('organization_id').value = id;

        // Change form action to update route
        document.getElementById('organization-form').action = `{{ route('admin.organizations.update', 'ORGANIZATION_ID') }}`.replace('ORGANIZATION_ID', id);
    }

    function resetForm() {
        document.getElementById('form-title').innerText = 'Create New Organization';
        document.getElementById('name').value = '';
        document.getElementById('description').value = '';
        document.getElementById('form-submit').innerText = 'Create Organization';
        document.getElementById('form-cancel').classList.add('hidden');
        document.getElementById('form-method').value = 'POST';
        document.getElementById('organization_id').value = '';

        // Reset form action to store route
        document.getElementById('organization-form').action = "{{ route('admin.organizations.store') }}";
    }

    function showDeleteModal(orgId) {
        const modal = document.getElementById('deleteModal');
        const modalContent = modal.querySelector('div');
        const deleteForm = document.getElementById('delete-form');
        deleteForm.action = `{{ route('admin.organizations.destroy', 'ORG_ID') }}`.replace('ORG_ID', orgId);

        document.getElementById('confirmDeleteBtn').onclick = () => {
            deleteForm.submit();
        };

        modal.classList.remove('hidden');
        setTimeout(() => {
            modalContent.classList.remove('scale-95', 'opacity-0');
            modalContent.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function hideDeleteModal() {
        const modal = document.getElementById('deleteModal');
        const modalContent = modal.querySelector('div');

        modalContent.classList.remove('scale-100', 'opacity-100');
        modalContent.classList.add('scale-95', 'opacity-0');

        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }
</script>
@endsection
