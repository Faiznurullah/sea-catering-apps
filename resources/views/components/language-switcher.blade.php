<div class="language-switcher">
    <div class="dropdown">
        <button class="dropdown-toggle" type="button" id="languageDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            @if(app()->getLocale() == 'id')
                🇮🇩 ID
            @else
                🇺🇸 EN
            @endif
        </button>
        <div class="dropdown-menu" aria-labelledby="languageDropdown">
            <a class="dropdown-item {{ app()->getLocale() == 'en' ? 'active' : '' }}" href="{{ route('language.switch', 'en') }}">
                🇺🇸 English
            </a>
            <a class="dropdown-item {{ app()->getLocale() == 'id' ? 'active' : '' }}" href="{{ route('language.switch', 'id') }}">
                🇮🇩 Bahasa Indonesia
            </a>
        </div>
    </div>
</div>

<style>
.language-switcher {
    position: relative;
    display: inline-block;
}

.language-switcher .dropdown-toggle {
    background: none;
    border: 1px solid #ddd;
    padding: 8px 12px;
    border-radius: 4px;
    cursor: pointer;
    color: #333;
    font-size: 14px;
}

.language-switcher .dropdown-toggle:hover {
    background-color: #f8f9fa;
}

.language-switcher .dropdown-menu {
    display: none;
    position: absolute;
    top: 100%;
    left: 0;
    background: white;
    border: 1px solid #ddd;
    border-radius: 4px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    min-width: 150px;
    z-index: 1000;
}

.language-switcher .dropdown-menu.show {
    display: block;
}

.language-switcher .dropdown-item {
    display: block;
    padding: 8px 16px;
    text-decoration: none;
    color: #333;
    cursor: pointer;
}

.language-switcher .dropdown-item:hover {
    background-color: #f8f9fa;
}

.language-switcher .dropdown-item.active {
    background-color: #007bff;
    color: white;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const dropdown = document.getElementById('languageDropdown');
    const dropdownMenu = dropdown.nextElementSibling;
    
    dropdown.addEventListener('click', function(e) {
        e.preventDefault();
        dropdownMenu.classList.toggle('show');
    });
    
    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!dropdown.contains(e.target)) {
            dropdownMenu.classList.remove('show');
        }
    });
});
</script>
