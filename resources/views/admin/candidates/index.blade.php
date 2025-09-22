@extends('layouts.voting')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    {{-- Include the dynamic breadcrumb component --}}
    @include('admin.components._breadcrumb', ['election' => $election, 'position' => $position])

    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Manage Candidates</h1>
        <p class="mt-2 text-lg text-gray-600">
            For Election: <span class="font-semibold text-gray-800">{{ $election->title }}</span> | Position: <span class="font-semibold text-gray-800">{{ $position->title }}</span>
        </p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Add/Edit New Candidate Form -->
        <div class="lg:col-span-1 bg-white p-8 rounded-xl shadow-lg h-fit">
            <h3 id="form-title" class="text-xl font-semibold text-gray-900 mb-6">Add New Candidate</h3>
            <form id="candidate-form" action="{{ route('admin.elections.positions.candidates.store', [$election, $position]) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <input type="hidden" name="_method" value="POST">
                <input type="hidden" name="candidate_id" id="candidate-id">

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Candidate Name</label>
                    <input type="text" name="name" id="name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div>
                    <label for="photo" class="block text-sm font-medium text-gray-700">Photo (Optional)</label>
                    <input type="file" name="photo" id="photo" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                </div>
                <div>
                    <label for="manifesto" class="block text-sm font-medium text-gray-700">Manifesto (Optional)</label>
                    <textarea name="manifesto" id="manifesto" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                </div>
                <div class="flex items-center space-x-4">
                    <button type="submit" id="submit-button" class="flex-1 py-3 px-6 border border-transparent rounded-md shadow-sm text-lg font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                        Add Candidate
                    </button>
                    <button type="button" id="cancel-button" class="hidden flex-1 py-3 px-6 border border-gray-300 rounded-md shadow-sm text-lg font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                        Cancel
                    </button>
                </div>
            </form>
        </div>

        <!-- Candidates List -->
        <div class="lg:col-span-2 bg-white p-8 rounded-xl shadow-lg">
            <h3 class="text-xl font-semibold text-gray-900 mb-6">Candidates List</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Photo
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Name
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Manifesto
                            </th>
                            <th scope="col" class="relative px-6 py-3">
                                <span class="sr-only">Actions</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($candidates as $candidate)
                            <tr class="hover:bg-gray-50 transition duration-150 ease-in-out">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($candidate->photo_path)
                                        <img src="{{ asset('storage/' . $candidate->photo_path) }}" alt="{{ $candidate->name }}" class="h-12 w-12 rounded-full object-cover shadow-sm">
                                    @else
                                        <div class="h-12 w-12 rounded-full bg-gray-200 flex items-center justify-center text-xs text-gray-600 font-semibold shadow-sm">No Photo</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $candidate->name }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500 max-w-sm truncate">
                                    {{ $candidate->manifesto ? Str::limit($candidate->manifesto, 50) : 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button type="button"
                                        class="edit-button text-indigo-600 hover:text-indigo-900 mr-4"
                                        data-candidate-id="{{ $candidate->id }}"
                                        data-candidate-name="{{ $candidate->name }}"
                                        data-candidate-manifesto="{{ $candidate->manifesto }}"
                                        data-candidate-photo-path="{{ $candidate->photo_path }}"
                                        data-update-action="{{ route('admin.elections.positions.candidates.update', [$election, $position, ':id']) }}">
                                        Edit
                                    </button>
                                    <button type="button" class="text-red-600 hover:text-red-900 delete-button"
                                        data-candidate-name="{{ $candidate->name }}"
                                        data-form-action="{{ route('admin.elections.positions.candidates.destroy', [$election, $position, $candidate]) }}">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                    No candidates found for this position.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="delete-modal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-black/50">
    <div class="flex items-center justify-center min-h-screen px-4 py-8">
        <div class="relative bg-white w-full max-w-md mx-auto rounded-lg shadow-xl p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Confirm Deletion</h3>
            <p class="text-gray-600 mb-6">Are you sure you want to delete <span id="candidate-name-span" class="font-semibold text-gray-800"></span>? This action cannot be undone.</p>
            <div class="flex justify-end gap-3">
                <button type="button" id="cancel-delete-btn" class="px-4 py-2 border rounded-md text-sm font-medium text-gray-700 hover:bg-gray-100 transition">Cancel</button>
                <form id="delete-form" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 rounded-md text-sm font-medium text-white bg-red-600 hover:bg-red-700 transition">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const candidateForm = document.getElementById('candidate-form');
        const formTitle = document.getElementById('form-title');
        const candidateIdInput = document.getElementById('candidate-id');
        const nameInput = document.getElementById('name');
        const manifestoInput = document.getElementById('manifesto');
        const submitButton = document.getElementById('submit-button');
        const cancelButton = document.getElementById('cancel-button');
        const methodInput = candidateForm.querySelector('input[name="_method"]');

        const initialFormAction = candidateForm.action;
        const initialSubmitText = submitButton.textContent;
        const initialFormTitle = formTitle.textContent;

        const editButtons = document.querySelectorAll('.edit-button');
        const deleteButtons = document.querySelectorAll('.delete-button');
        const deleteModal = document.getElementById('delete-modal');
        const candidateNameSpan = document.getElementById('candidate-name-span');
        const deleteForm = document.getElementById('delete-form');
        const cancelDeleteButton = document.getElementById('cancel-delete-btn');

        // Function to set the form for editing
        function setFormToEditMode(candidate) {
            formTitle.textContent = 'Edit Candidate';
            candidateForm.action = candidate.updateAction.replace(':id', candidate.id);
            methodInput.value = 'PATCH';
            candidateIdInput.value = candidate.id;
            nameInput.value = candidate.name;
            manifestoInput.value = candidate.manifesto;
            submitButton.textContent = 'Update Candidate';
            cancelButton.classList.remove('hidden');
        }

        // Function to reset the form to 'Add' mode
        function resetFormToAddMode() {
            formTitle.textContent = initialFormTitle;
            candidateForm.action = initialFormAction;
            methodInput.value = 'POST';
            candidateIdInput.value = '';
            candidateForm.reset();
            submitButton.textContent = initialSubmitText;
            cancelButton.classList.add('hidden');
        }

        // Event listeners for edit buttons
        editButtons.forEach(button => {
            button.addEventListener('click', function() {
                const candidate = {
                    id: this.dataset.candidateId,
                    name: this.dataset.candidateName,
                    manifesto: this.dataset.candidateManifesto,
                    updateAction: this.dataset.updateAction
                };
                setFormToEditMode(candidate);
                nameInput.focus();
                // Scroll to the form on mobile
                window.scrollTo({
                    top: candidateForm.offsetTop,
                    behavior: 'smooth'
                });
            });
        });

        // Event listener for the cancel button
        cancelButton.addEventListener('click', function() {
            resetFormToAddMode();
        });

        // Delete Modal Logic
        function showDeleteModal(name, action) {
            candidateNameSpan.textContent = name;
            deleteForm.action = action;
            deleteModal.classList.remove('hidden');
        }

        function hideDeleteModal() {
            deleteModal.classList.add('hidden');
        }

        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const candidateName = this.dataset.candidateName;
                const formAction = this.dataset.formAction;
                showDeleteModal(candidateName, formAction);
            });
        });

        cancelDeleteButton.addEventListener('click', hideDeleteModal);

        deleteModal.addEventListener('click', function(event) {
            if (event.target === deleteModal) {
                hideDeleteModal();
            }
        });
    });
</script>
@endsection
