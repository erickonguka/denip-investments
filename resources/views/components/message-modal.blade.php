<x-modal id="message-modal" title="Send Message" size="large">
    <form id="messageForm">
        <div style="margin-bottom: 1rem;">
            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--deep-blue);">To:</label>
            <div id="message-recipient" style="padding: 0.75rem; background: var(--gray-50); border-radius: 8px; color: var(--gray-700);"></div>
        </div>
        
        <div style="margin-bottom: 1rem;">
            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--deep-blue);">Email Recipients (up to 12):</label>
            <div id="email-inputs" style="display: grid; gap: 0.5rem;">
                <input type="email" name="emails[]" placeholder="Enter email address" style="padding: 0.75rem; border: 1px solid var(--gray-300); border-radius: 8px; outline: none;" required>
            </div>
            <button type="button" onclick="addEmailInput()" style="margin-top: 0.5rem; padding: 0.5rem 1rem; background: transparent; color: var(--primary-blue); border: 1px solid var(--primary-blue); border-radius: 4px; cursor: pointer;">
                <i class="fas fa-plus"></i> Add Email
            </button>
        </div>
        
        <x-form-field label="Subject" name="subject" :required="true" placeholder="Enter message subject" />
        
        <div style="margin-bottom: 1rem;">
            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--deep-blue);">Message *</label>
            <textarea id="message-editor" name="message" style="width: 100%; min-height: 300px;"></textarea>
        </div>
        
        <div id="attachments-section" style="margin-bottom: 1rem; display: none;">
            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--deep-blue);">Available Documents</label>
            <div id="available-documents" style="background: var(--gray-50); border-radius: 8px; padding: 1rem;"></div>
        </div>
        
        <div style="display: flex; gap: 1rem; margin-top: 2rem;">
            <button type="submit" class="btn btn-primary">
                <span class="btn-text">Send Message</span>
            </button>
            <button type="button" class="btn" style="background: var(--gray-300); color: var(--gray-700);" onclick="closeModal('message-modal')">Cancel</button>
        </div>
    </form>
</x-modal>

<script>
window.currentMessageRecipient = null;

document.getElementById('messageForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    if (!window.currentMessageRecipient) {
        showNotification('No recipient selected', 'error');
        return;
    }
    
    const btn = this.querySelector('button[type="submit"]');
    const btnText = btn.querySelector('.btn-text');
    
    btn.disabled = true;
    btnText.textContent = 'Sending...';
    
    const formData = new FormData(this);
    const messageContent = tinymce.get('message-editor') ? tinymce.get('message-editor').getContent() : formData.get('message');
    
    const attachments = Array.from(formData.getAll('attachments[]'));
    const emails = Array.from(formData.getAll('emails[]')).filter(email => email.trim() !== '');
    
    if (emails.length === 0) {
        showNotification('Please enter at least one email address', 'error');
        btn.disabled = false;
        btnText.textContent = 'Send Message';
        return;
    }
    
    const data = {
        recipient_id: window.currentMessageRecipient.id,
        recipient_type: window.currentMessageRecipient.type,
        emails: emails,
        subject: formData.get('subject'),
        message: messageContent,
        attachments: attachments
    };
    
    fetch('/messages', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Message sent successfully!', 'success');
            closeModal('message-modal');
            this.reset();
        } else {
            showNotification('Failed to send message', 'error');
        }
    })
    .catch(() => {
        showNotification('An error occurred', 'error');
    })
    .finally(() => {
        btn.disabled = false;
        btnText.textContent = 'Send Message';
    });
});

// Initialize TinyMCE on page load
document.addEventListener('DOMContentLoaded', function() {
    if (typeof tinymce !== 'undefined') {
        tinymce.init({
            selector: '#message-editor',
            height: 400,
            menubar: true,
            plugins: 'advlist autolink lists link image charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime media table help wordcount paste',
            toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | link image media table | forecolor backcolor | code fullscreen | help',
            paste_data_images: true,
            automatic_uploads: false,
            file_picker_types: 'image',
            content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, sans-serif; font-size: 14px; line-height: 1.6; }',
            setup: function(editor) {
                editor.on('init', function() {
                    const style = document.createElement('style');
                    style.textContent = '.tox-tinymce-aux { z-index: 10001 !important; } .tox-dialog { z-index: 10002 !important; }';
                    document.head.appendChild(style);
                });
            },
            block_formats: 'Paragraph=p; Heading 1=h1; Heading 2=h2; Heading 3=h3; Preformatted=pre',
            fontsize_formats: '8pt 10pt 12pt 14pt 16pt 18pt 24pt 36pt 48pt'
        });
    }
});

// Update recipient when opening message modal
function openMessageModal(clientId, clientName, clientEmail, clientCompany) {
    window.currentMessageRecipient = { id: clientId, name: clientName, email: clientEmail, company: clientCompany, type: 'client' };
    
    // Update display immediately
    const companyName = window.currentMessageRecipient.company || 'N/A';
    document.getElementById('message-recipient').textContent = `${clientName} (${companyName})`;
    
    // Set first email input to client email
    const firstEmailInput = document.querySelector('#email-inputs input[name="emails[]"]');
    if (firstEmailInput && clientEmail) {
        firstEmailInput.value = clientEmail;
    }
    
    // Fetch client documents
    fetchClientDocuments(clientId);
    
    openModal('message-modal');
}

function addEmailInput() {
    const container = document.getElementById('email-inputs');
    const currentInputs = container.querySelectorAll('input[name="emails[]"]');
    
    if (currentInputs.length >= 12) {
        showNotification('Maximum 12 email addresses allowed', 'error');
        return;
    }
    
    const inputHtml = `
        <div style="display: flex; gap: 0.5rem; align-items: center;">
            <input type="email" name="emails[]" placeholder="Enter email address" style="flex: 1; padding: 0.75rem; border: 1px solid var(--gray-300); border-radius: 8px; outline: none;">
            <button type="button" onclick="removeEmailInput(this)" style="padding: 0.75rem; background: var(--error); color: white; border: none; border-radius: 4px; cursor: pointer;">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', inputHtml);
}

function removeEmailInput(button) {
    const container = document.getElementById('email-inputs');
    const inputs = container.querySelectorAll('input[name="emails[]"]');
    
    if (inputs.length > 1) {
        button.closest('div').remove();
    }
}

function fetchClientDocuments(clientId) {
    fetch(`/clients/${clientId}/documents`, {
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        const section = document.getElementById('attachments-section');
        const container = document.getElementById('available-documents');
        
        if (data.documents && data.documents.length > 0) {
            let html = '<div style="margin-bottom: 0.5rem; font-weight: 600;">Select documents to attach:</div>';
            
            data.documents.forEach(doc => {
                html += `
                    <label style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem; cursor: pointer;">
                        <input type="checkbox" name="attachments[]" value="${doc.type}-${doc.id}" style="transform: scale(1.2);">
                        <i class="fas fa-${doc.icon}" style="color: var(--primary-blue);"></i>
                        <span>${doc.title}</span>
                        <span style="color: var(--gray-600); font-size: 0.9rem;">(${doc.date})</span>
                    </label>
                `;
            });
            
            container.innerHTML = html;
            section.style.display = 'block';
        } else {
            section.style.display = 'none';
        }
    })
    .catch(() => {
        document.getElementById('attachments-section').style.display = 'none';
    });
}
</script>