@extends('layouts.template')
@section('title', 'Contact Messages - Admin')
@section('css')
<link rel="stylesheet" href="{{ asset('css/styles.css') }}">
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
<style>
.contacts-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

.contacts-table th,
.contacts-table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

.contacts-table th {
    background-color: #f8f9fa;
    font-weight: 600;
}

.status-badge {
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 0.875rem;
}

.status-unread {
    background-color: #fff3cd;
    color: #856404;
}

.status-read {
    background-color: #d4edda;
    color: #155724;
}

.message-preview {
    max-width: 200px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.contact-actions {
    display: flex;
    gap: 8px;
}

.btn-small {
    padding: 4px 8px;
    font-size: 0.875rem;
    border-radius: 4px;
    text-decoration: none;
    border: none;
    cursor: pointer;
}

.btn-primary-small {
    background-color: #007bff;
    color: white;
}

.btn-success-small {
    background-color: #28a745;
    color: white;
}

.contact-detail-modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 1000;
}

.modal-content {
    background-color: white;
    margin: 5% auto;
    padding: 20px;
    width: 80%;
    max-width: 600px;
    border-radius: 8px;
    max-height: 80vh;
    overflow-y: auto;
}

.close-modal {
    float: right;
    font-size: 24px;
    cursor: pointer;
}
</style>
@endsection

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <h1>Contact Messages</h1>
            
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            
            <div class="card">
                <div class="card-body">
                    <table class="contacts-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Subject</th>
                                <th>Message</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($contacts as $contact)
                            <tr>
                                <td>{{ $contact->created_at->format('M d, Y H:i') }}</td>
                                <td>{{ $contact->name }}</td>
                                <td>{{ $contact->email }}</td>
                                <td>{{ ucfirst($contact->subject) }}</td>
                                <td class="message-preview">{{ $contact->message }}</td>
                                <td>
                                    <span class="status-badge {{ $contact->isRead() ? 'status-read' : 'status-unread' }}">
                                        {{ $contact->isRead() ? 'Read' : 'Unread' }}
                                    </span>
                                </td>
                                <td class="contact-actions">
                                    <button class="btn-small btn-primary-small view-contact" 
                                            data-contact="{{ json_encode($contact) }}">
                                        View
                                    </button>
                                    @if(!$contact->isRead())
                                    <form method="POST" action="{{ route('contact.mark-read', $contact->id) }}" style="display: inline;">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn-small btn-success-small">
                                            Mark Read
                                        </button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">No contact messages found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    
                    <div class="mt-3">
                        {{ $contacts->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Contact Detail Modal -->
<div id="contactModal" class="contact-detail-modal">
    <div class="modal-content">
        <span class="close-modal">&times;</span>
        <h3>Contact Message Details</h3>
        <div id="contactDetails">
            <!-- Contact details will be populated here -->
        </div>
    </div>
</div>
@endsection

@section('javascript')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('contactModal');
    const closeModal = document.querySelector('.close-modal');
    const viewButtons = document.querySelectorAll('.view-contact');
    
    viewButtons.forEach(button => {
        button.addEventListener('click', function() {
            const contact = JSON.parse(this.getAttribute('data-contact'));
            showContactDetails(contact);
        });
    });
    
    closeModal.addEventListener('click', function() {
        modal.style.display = 'none';
    });
    
    window.addEventListener('click', function(event) {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });
    
    function showContactDetails(contact) {
        const detailsHtml = `
            <div class="contact-info">
                <p><strong>Name:</strong> ${contact.name}</p>
                <p><strong>Email:</strong> ${contact.email}</p>
                <p><strong>Phone:</strong> ${contact.phone || 'Not provided'}</p>
                <p><strong>Subject:</strong> ${contact.subject}</p>
                <p><strong>Date:</strong> ${new Date(contact.created_at).toLocaleString()}</p>
                <p><strong>Status:</strong> ${contact.read_at ? 'Read' : 'Unread'}</p>
            </div>
            <div class="contact-message">
                <h4>Message:</h4>
                <p style="background-color: #f8f9fa; padding: 15px; border-radius: 5px; white-space: pre-wrap;">${contact.message}</p>
            </div>
        `;
        
        document.getElementById('contactDetails').innerHTML = detailsHtml;
        modal.style.display = 'block';
    }
});
</script>
@endsection
