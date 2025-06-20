document.addEventListener('DOMContentLoaded', function() {
    console.log('Profile JS loaded');
    
    // CSRF token setup
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) {
        console.error('CSRF token not found');
        return;
    }
    
    // Elements
    const editAccountBtn = document.getElementById('edit-account-btn');
    const cancelAccountBtn = document.getElementById('cancel-account-btn');
    const accountForm = document.getElementById('account-form');
    const accountActions = document.getElementById('account-actions');
    const fotoGroup = document.getElementById('foto-group');
    const changePasswordBtn = document.getElementById('change-password-btn');
    const changePasswordModal = document.getElementById('change-password-modal');
    const changePasswordForm = document.getElementById('change-password-form');
    const closeModals = document.querySelectorAll('.close-modal');
    const cancelPasswordChange = document.getElementById('cancel-password-change');
    const changeAvatarBtn = document.getElementById('change-avatar-btn');
    const avatarUpload = document.getElementById('avatar-upload');

    // Debug: Log found elements
    console.log('Edit button found:', !!editAccountBtn);
    console.log('Account form found:', !!accountForm);
    
    // Form fields
    const formFields = ['name', 'email', 'phone', 'city', 'national'];

    // Store original values for reset
    let originalValues = {};
    formFields.forEach(field => {
        const input = document.getElementById(field);
        if (input) {
            originalValues[field] = input.value;
        }
    });

    // Show success message
    function showMessage(message, type = 'success') {
        // Remove existing alerts
        const existingAlerts = document.querySelectorAll('.alert');
        existingAlerts.forEach(alert => alert.remove());

        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type}`;
        alertDiv.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 20px;
            background: ${type === 'success' ? '#d4edda' : '#f8d7da'};
            color: ${type === 'success' ? '#155724' : '#721c24'};
            border: 1px solid ${type === 'success' ? '#c3e6cb' : '#f5c6cb'};
            border-radius: 5px;
            z-index: 9999;
            max-width: 300px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        `;
        alertDiv.textContent = message;
        document.body.appendChild(alertDiv);

        // Auto remove after 5 seconds
        setTimeout(() => {
            alertDiv.remove();
        }, 5000);
    }

    // Toggle edit mode for account form
    if (editAccountBtn) {
        editAccountBtn.addEventListener('click', function() {
            console.log('Edit button clicked');
            
            formFields.forEach(field => {
                const input = document.getElementById(field);
                if (input) {
                    input.removeAttribute('readonly');
                    input.style.backgroundColor = '#fff';
                    input.style.borderColor = '#ced4da';
                    console.log(`Field ${field} enabled`);
                }
            });
            
            if (fotoGroup) {
                fotoGroup.style.display = 'block';
                console.log('Photo group shown');
            }
            if (accountActions) {
                accountActions.style.display = 'flex';
                console.log('Actions shown');
            }
            editAccountBtn.style.display = 'none';
            
            showMessage('Edit mode enabled. Make your changes and click Save.', 'success');
        });
    } else {
        console.error('Edit account button not found');
    }

    // Cancel edit mode
    if (cancelAccountBtn) {
        cancelAccountBtn.addEventListener('click', function() {
            console.log('Cancel button clicked');
            
            // Reset form to original values
            formFields.forEach(field => {
                const input = document.getElementById(field);
                if (input) {
                    input.value = originalValues[field] || '';
                    input.setAttribute('readonly', true);
                    input.style.backgroundColor = '#f8f9fa';
                    input.style.borderColor = '#ced4da';
                }
            });
            
            if (fotoGroup) fotoGroup.style.display = 'none';
            if (accountActions) accountActions.style.display = 'none';
            if (editAccountBtn) editAccountBtn.style.display = 'inline-block';
            
            showMessage('Changes cancelled', 'success');
        });
    }

    // Handle account form submission
    if (accountForm) {
        accountForm.addEventListener('submit', function(e) {
            e.preventDefault();
            console.log('Form submitted');
            
            const formData = new FormData(accountForm);
            
            // Show loading state
            const submitBtn = accountForm.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            submitBtn.textContent = 'Saving...';
            submitBtn.disabled = true;

            fetch('/profile/update', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': csrfToken.getAttribute('content')
                }
            })
            .then(response => {
                console.log('Response status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                if (data.success) {
                    showMessage(data.message, 'success');
                    
                    // Update UI with new data
                    if (data.user) {
                        // Update profile header
                        const profileName = document.querySelector('.profile-info h1');
                        const profileEmail = document.querySelector('.profile-email');
                        if (profileName) profileName.textContent = data.user.name;
                        if (profileEmail) profileEmail.textContent = data.user.email;
                        
                        // Update form values with new data
                        formFields.forEach(field => {
                            const input = document.getElementById(field);
                            if (input && data.user[field]) {
                                input.value = data.user[field];
                                originalValues[field] = data.user[field];
                            }
                        });
                        
                        // Update avatar if new photo was uploaded
                        if (data.user.foto) {
                            const avatar = document.querySelector('.profile-avatar');
                            if (avatar) {
                                avatar.innerHTML = `<img src="/storage/${data.user.foto}" alt="Profile Photo" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">`;
                            }
                        }
                    }
                    
                    // Exit edit mode
                    if (cancelAccountBtn) cancelAccountBtn.click();
                } else {
                    showMessage(data.message || 'Error updating profile', 'error');
                    if (data.errors) {
                        let errorMessage = '';
                        Object.values(data.errors).forEach(error => {
                            errorMessage += error.join(', ') + ' ';
                        });
                        showMessage(errorMessage, 'error');
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showMessage('An error occurred while updating profile', 'error');
            })
            .finally(() => {
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
            });
        });
    }

    // Handle change password modal
    if (changePasswordBtn) {
        changePasswordBtn.addEventListener('click', function() {
            if (changePasswordModal) changePasswordModal.style.display = 'block';
        });
    }

    // Handle password form submission
    if (changePasswordForm) {
        changePasswordForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(changePasswordForm);
            
            // Show loading state
            const submitBtn = changePasswordForm.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            submitBtn.textContent = 'Changing...';
            submitBtn.disabled = true;

            fetch('/profile/change-password', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': csrfToken.getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showMessage(data.message, 'success');
                    if (changePasswordModal) changePasswordModal.style.display = 'none';
                    changePasswordForm.reset();
                } else {
                    showMessage(data.message || 'Error changing password', 'error');
                    if (data.errors) {
                        let errorMessage = '';
                        Object.values(data.errors).forEach(error => {
                            errorMessage += error.join(', ') + ' ';
                        });
                        showMessage(errorMessage, 'error');
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showMessage('An error occurred while changing password', 'error');
            })
            .finally(() => {
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
            });
        });
    }

    // Close modals
    closeModals.forEach(closeBtn => {
        closeBtn.addEventListener('click', function() {
            const modal = this.closest('.modal');
            if (modal) modal.style.display = 'none';
        });
    });

    if (cancelPasswordChange) {
        cancelPasswordChange.addEventListener('click', function() {
            if (changePasswordModal) changePasswordModal.style.display = 'none';
            if (changePasswordForm) changePasswordForm.reset();
        });
    }

    // Handle avatar upload via camera button
    if (changeAvatarBtn && avatarUpload) {
        changeAvatarBtn.addEventListener('click', function() {
            avatarUpload.click();
        });

        avatarUpload.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                // Trigger edit mode and set the file
                if (editAccountBtn) editAccountBtn.click();
                const fotoInput = document.getElementById('foto');
                if (fotoInput) fotoInput.files = this.files;
                
                // Preview the image
                const reader = new FileReader();
                reader.onload = function(e) {
                    const avatar = document.querySelector('.profile-avatar');
                    if (avatar) {
                        avatar.innerHTML = `<img src="${e.target.result}" alt="Profile Photo" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">`;
                    }
                };
                reader.readAsDataURL(this.files[0]);
            }
        });
    }

    // Toggle password visibility
    document.querySelectorAll('.toggle-password').forEach(button => {
        button.addEventListener('click', function() {
            const passwordInput = this.previousElementSibling;
            if (passwordInput) {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                this.textContent = type === 'password' ? '👁️' : '🙈';
            }
        });
    });

    // Close modal when clicking outside
    window.addEventListener('click', function(e) {
        if (e.target.classList.contains('modal')) {
            e.target.style.display = 'none';
        }
    });
});
