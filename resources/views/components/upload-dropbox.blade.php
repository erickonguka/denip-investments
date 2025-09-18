@props(['name', 'accept' => 'image/*,application/pdf,.doc,.docx,.pptx,.txt,.csv,.xls,.xlsx', 'multiple' => false, 'maxSize' => '10', 'text' => null, 'existingMedia' => null])

<div class="upload-dropbox" style="border: 2px dashed var(--gray-300); border-radius: 8px; padding: 2rem; text-align: center; background: var(--gray-50); transition: all 0.3s ease; cursor: pointer;">
    <input type="file" name="{{ $name }}" id="{{ $name }}" accept="{{ $accept }}" {{ $multiple ? 'multiple' : '' }} style="display: none;">
    
    <div class="upload-content">
        <i class="fas fa-cloud-upload-alt" style="font-size: 3rem; color: var(--gray-400); margin-bottom: 1rem;"></i>
        <h4 style="color: var(--deep-blue); margin-bottom: 0.5rem;">
            {{ $text ?? 'Drop files here or click to upload' }}
        </h4>
        <p style="color: var(--gray-600); font-size: 0.9rem;">
            @if($accept === 'image/*')
                Supported formats: Images (JPG, PNG, GIF, WEBP)<br>
            @else
                Supported formats: Images (JPG, PNG, GIF, WEBP, AVIF), Documents (PDF, DOC, DOCX, PPTX, TXT, CSV, XLS, XLSX)<br>
            @endif
            Maximum file size: {{ $maxSize }}MB {{ $multiple ? '(Multiple files allowed)' : '' }}
        </p>
    </div>
    
    @if($existingMedia)
    <div class="existing-media" style="margin-top: 1rem;">
        <h5 style="color: var(--deep-blue); margin-bottom: 0.5rem;">Current Files:</h5>
        <div class="existing-file-list" style="display: flex; flex-wrap: wrap; gap: 0.5rem; justify-content: center;">
            @if(is_array($existingMedia))
                @foreach($existingMedia as $index => $media)
                <div class="existing-file-item">
                    @if(is_array($media) && isset($media['type']) && str_starts_with($media['type'], 'image/'))
                        <img src="{{ asset('storage/' . $media['path']) }}" alt="{{ $media['name'] }}" style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;">
                    @else
                        <i class="fas fa-file" style="color: var(--dark-yellow); width: 40px; text-align: center;"></i>
                    @endif
                    <span style="flex: 1;">{{ is_array($media) ? $media['name'] : basename($media) }}</span>
                    <button type="button" class="remove-existing-file" data-index="{{ $index }}" style="background: var(--error); color: white; border: none; border-radius: 50%; width: 20px; height: 20px; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 0.7rem;">×</button>
                </div>
                @endforeach
            @else
                <div class="existing-file-item">
                    @if($accept === 'image/*' && $existingMedia)
                        <img src="{{ asset('storage/' . $existingMedia) }}" alt="Profile Photo" style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;">
                    @else
                        <i class="fas fa-image" style="color: var(--dark-yellow); width: 40px; text-align: center;"></i>
                    @endif
                    <span style="flex: 1;">Current {{ $accept === 'image/*' ? 'Photo' : 'File' }}</span>
                    <button type="button" class="remove-existing-file" style="background: var(--error); color: white; border: none; border-radius: 50%; width: 20px; height: 20px; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 0.7rem;">×</button>
                </div>
            @endif
        </div>
    </div>
    @endif
    
    <div class="upload-preview" style="margin-top: 1rem; display: none;">
        <div class="file-list" style="display: flex; flex-wrap: wrap; gap: 0.5rem; justify-content: center;"></div>
    </div>
</div>

@push('styles')
<style>
.upload-dropbox:hover {
    border-color: var(--primary-blue);
    background: var(--light-blue);
    background-opacity: 0.1;
}

.upload-dropbox.dragover {
    border-color: var(--primary-blue);
    background: rgba(30, 64, 175, 0.1);
}

.file-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem;
    background: var(--white);
    border: 1px solid var(--gray-200);
    border-radius: 6px;
    font-size: 0.8rem;
    min-width: 200px;
}

.existing-file-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem;
    background: var(--light-yellow);
    border: 1px solid var(--yellow);
    border-radius: 6px;
    font-size: 0.8rem;
    min-width: 200px;
}

.file-item .remove-file {
    background: var(--error);
    color: white;
    border: none;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.7rem;
}

.upload-error {
    color: var(--error);
    font-size: 0.8rem;
    margin-top: 0.5rem;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.upload-dropbox:not([data-initialized])').forEach(function(dropbox) {
        dropbox.setAttribute('data-initialized', 'true');
        const fileInput = dropbox.querySelector('input[type="file"]');
        const fileList = dropbox.querySelector('.file-list');
        const uploadPreview = dropbox.querySelector('.upload-preview');
        const maxSize = {{ $maxSize }} * 1024 * 1024;
        const isMultiple = {{ $multiple ? 'true' : 'false' }};
        
        let selectedFiles = [];
    
    // Click to upload - only on dropbox area, not on buttons or existing items
    dropbox.addEventListener('click', (e) => {
        if (e.target.closest('.remove-file') || e.target.closest('.remove-existing-file') || e.target.closest('.existing-file-item') || e.target.closest('.file-item')) return;
        fileInput.click();
    });
    
    // Drag and drop events
    dropbox.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropbox.classList.add('dragover');
    });
    
    dropbox.addEventListener('dragleave', () => {
        dropbox.classList.remove('dragover');
    });
    
    dropbox.addEventListener('drop', (e) => {
        e.preventDefault();
        dropbox.classList.remove('dragover');
        handleFiles(e.dataTransfer.files);
    });
    
    // File input change
    fileInput.addEventListener('change', (e) => {
        if (e.target.files.length > 0) {
            handleFiles(e.target.files);
        }
    });
    
    function handleFiles(files) {
        const allowedTypes = [
            'image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp', 'image/avif',
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-powerpoint',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'text/plain', 'text/csv',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        ];
        
        Array.from(files).forEach(file => {
            // Validate file type
            if (!allowedTypes.includes(file.type)) {
                showError(`File "${file.name}" is not supported. Please upload images, PDF, or Office documents.`);
                return;
            }
            
            // Validate file size
            if (file.size > maxSize) {
                showError(`File "${file.name}" is too large. Maximum size is {{ $maxSize }}MB.`);
                return;
            }
            
            // Add to selected files
            if (!isMultiple) {
                selectedFiles = [];
                fileList.innerHTML = '';
            }
            
            // Check if file already exists
            if (selectedFiles.some(f => f.name === file.name && f.size === file.size)) {
                showError(`File "${file.name}" is already selected.`);
                return;
            }
            
            selectedFiles.push(file);
            addFileToPreview(file);
        });
        
        updateFileInput();
    }
    
    function addFileToPreview(file) {
        const fileItem = document.createElement('div');
        fileItem.className = 'file-item';
        
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                fileItem.innerHTML = `
                    <img src="${e.target.result}" alt="${file.name}" style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;">
                    <span style="flex: 1; margin-left: 0.5rem;">${file.name}</span>
                    <button type="button" class="remove-file">×</button>
                `;
                fileItem.querySelector('.remove-file').addEventListener('click', function(e) {
                    e.stopPropagation();
                    removeFile(file.name);
                });
            };
            reader.readAsDataURL(file);
        } else {
            const icon = getFileIcon(file.type);
            fileItem.innerHTML = `
                <i class="${icon}" style="color: var(--primary-blue);"></i>
                <span style="flex: 1; margin-left: 0.5rem;">${file.name}</span>
                <button type="button" class="remove-file">×</button>
            `;
            fileItem.querySelector('.remove-file').addEventListener('click', function(e) {
                e.stopPropagation();
                removeFile(file.name);
            });
        }
        
        fileList.appendChild(fileItem);
        uploadPreview.style.display = 'block';
    }
    
    function getFileIcon(type) {
        if (type.startsWith('image/')) return 'fas fa-image';
        if (type === 'application/pdf') return 'fas fa-file-pdf';
        if (type.includes('word')) return 'fas fa-file-word';
        if (type.includes('powerpoint') || type.includes('presentation')) return 'fas fa-file-powerpoint';
        if (type.includes('excel') || type.includes('sheet')) return 'fas fa-file-excel';
        if (type === 'text/plain') return 'fas fa-file-alt';
        if (type === 'text/csv') return 'fas fa-file-csv';
        return 'fas fa-file';
    }
    
    function removeFile(fileName) {
        selectedFiles = selectedFiles.filter(file => file.name !== fileName);
        
        const fileItems = fileList.querySelectorAll('.file-item');
        fileItems.forEach(item => {
            if (item.querySelector('span').textContent === fileName) {
                item.remove();
            }
        });
        
        if (selectedFiles.length === 0) {
            uploadPreview.style.display = 'none';
        }
        
        updateFileInput();
    }
    
    // Handle existing media removal
    dropbox.querySelectorAll('.remove-existing-file').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const index = this.getAttribute('data-index');
            const existingItem = this.closest('.existing-file-item');
            
            // Track removed media
            let removedMedia = JSON.parse(document.querySelector('input[name="removed_media"]')?.value || '[]');
            removedMedia.push(parseInt(index));
            
            // Update or create hidden input
            let hiddenInput = document.querySelector('input[name="removed_media"]');
            if (!hiddenInput) {
                hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'removed_media';
                dropbox.closest('form').appendChild(hiddenInput);
            }
            hiddenInput.value = JSON.stringify(removedMedia);
            
            existingItem.remove();
            
            // Hide existing media section if no items left
            const existingMedia = dropbox.querySelector('.existing-media');
            const remainingItems = existingMedia.querySelectorAll('.existing-file-item');
            if (remainingItems.length === 0) {
                existingMedia.style.display = 'none';
            }
        });
    });
    
    function updateFileInput() {
        const dt = new DataTransfer();
        selectedFiles.forEach(file => dt.items.add(file));
        fileInput.files = dt.files;
    }
    
    function showError(message) {
        const existingError = dropbox.querySelector('.upload-error');
        if (existingError) existingError.remove();
        
        const errorDiv = document.createElement('div');
        errorDiv.className = 'upload-error';
        errorDiv.textContent = message;
        dropbox.appendChild(errorDiv);
        
        setTimeout(() => {
            if (errorDiv.parentNode) {
                errorDiv.remove();
            }
        }, 5000);
    }
    });
});
</script>
@endpush