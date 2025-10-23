<?php
// Language selector include - supports all 13 Indian languages
// This file provides multi-language support via Google Translate API
?>

<style>
.lang-selector {
    display: inline-flex;
    gap: 10px;
    align-items: center;
    background: rgba(255,255,255,0.15);
    padding: 5px 10px;
    border-radius: 6px;
    border: 2px solid rgba(255,255,255,0.4);
    margin-right: 20px;
}

.lang-selector label {
    font-size: 18px;
    color: white;
    font-weight: 600;
    cursor: default;
    margin: 0;
}

.lang-selector select {
    border: 2px solid rgba(255,255,255,0.5);
    background: rgba(255,255,255,0.2);
    color: white;
    font-size: 14px;
    padding: 2px 4px;
    border-radius: 4px;
    cursor: pointer;
    font-weight: 500;
    outline: none;
    transition: all 0.3s ease;
    min-width: 140px;
}

.lang-selector select:hover {
    background: rgba(255,255,255,0.3);
    border-color: rgba(255,255,255,0.7);
}

.lang-selector select:focus {
    background: rgba(255,255,255,0.35);
    border-color: white;
    box-shadow: 0 0 10px rgba(255,255,255,0.3);
}

.lang-selector select option {
    background: #2e7d32;
    color: white;
    padding: 8px;
}

.lang-selector select option:hover {
    background: #388e3c;
}

/* Mobile responsive adjustments */
@media (max-width: 768px) {
    .lang-selector {
        padding: 8px 12px;
        gap: 8px;
        margin-right: 10px;
    }
    
    .lang-selector label {
        font-size: 16px;
    }
    
    .lang-selector select {
        font-size: 13px;
        padding: 5px 10px;
        min-width: 120px;
    }
}

@media (max-width: 480px) {
    .lang-selector {
        padding: 6px 8px;
        gap: 6px;
        margin-right: 5px;
    }
    
    .lang-selector label {
        font-size: 14px;
    }
    
    .lang-selector select {
        font-size: 12px;
        padding: 4px 8px;
        min-width: 100px;
    }
}

@media (max-width: 320px) {
    .lang-selector {
        padding: 5px 6px;
        gap: 4px;
        margin-right: 0;
    }
    
    .lang-selector label {
        font-size: 12px;
    }
    
    .lang-selector select {
        font-size: 11px;
        padding: 3px 6px;
        min-width: 80px;
    }
}
</style>

<!-- Language Selector Component -->
<div class="lang-selector" id="lang-selector-container">
    <label for="site-lang">🌐</label>
    <select id="site-lang" onchange="setLanguage(this.value)" title="Select Language">
        <option value="en" selected>English</option>
        <option value="hi">हिन्दी (Hindi)</option>
        <option value="mr">मराठी (Marathi)</option>
        <option value="gu">ગુજરાતી (Gujarati)</option>
        <option value="ta">தமிழ் (Tamil)</option>
        <option value="te">తెలుగు (Telugu)</option>
        <option value="kn">ಕನ್ನಡ (Kannada)</option>
        <option value="ml">മലയാളം (Malayalam)</option>
        <option value="bn">বাংলা (Bengali)</option>
        <option value="pa">ਪੰਜਾਬੀ (Punjabi)</option>
        <option value="ur">اردو (Urdu)</option>
        <option value="or">ଓଡ଼ିଆ (Odia)</option>
        <option value="as">অসমীয়া (Assamese)</option>
    </select>
</div>

<!-- Hidden Google Translate Element -->
<div id="google_translate_element" style="display:none;"></div>

<script type="text/javascript">
// Initialize Google Translate
function googleTranslateElementInit() {
    try {
        if (typeof google !== 'undefined' && typeof google.translate !== 'undefined') {
            new google.translate.TranslateElement({
                pageLanguage: 'en',
                includedLanguages: 'en,hi,mr,gu,ta,te,kn,ml,bn,pa,ur,or,as',
                layout: google.translate.TranslateElement.InlineLayout.SIMPLE,
                autoDisplay: false
            }, 'google_translate_element');
        }
    } catch(e) {
        console.log('Google Translate not available:', e);
    }
}

// Set cookie function
function setCookie(name, value, days) {
    var expires = '';
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = '; expires=' + date.toUTCString();
    }
    document.cookie = name + '=' + (value || '') + expires + '; path=/; SameSite=Lax';
}

// Get cookie function
function getCookie(name) {
    var nameEQ = name + '=';
    var cookies = document.cookie.split(';');
    for (var i = 0; i < cookies.length; i++) {
        var cookie = cookies[i].trim();
        if (cookie.indexOf(nameEQ) === 0) {
            return cookie.substring(nameEQ.length);
        }
    }
    return '';
}

// Main language setter function
function setLanguage(lang) {
    if (!lang || lang === 'en') {
        // Reset to English
        setCookie('googtrans', '', -1);
        sessionStorage.removeItem('selectedLang');
        location.reload();
        return;
    }
    
    // Set the translation cookie
    var gt = '/en/' + lang;
    setCookie('googtrans', gt, 7);
    sessionStorage.setItem('selectedLang', lang);
    
    // Reload page to apply translation
    location.reload();
}

// Restore selected language on page load
document.addEventListener('DOMContentLoaded', function() {
    try {
        // Check if Google Translate API is loaded
        if (typeof google !== 'undefined' && typeof google.translate !== 'undefined') {
            googleTranslateElementInit();
        }
        
        // Restore language selection
        var googtrans = getCookie('googtrans');
        var langSelect = document.getElementById('site-lang');
        
        if (googtrans && langSelect) {
            var langMatch = googtrans.match(/\/en\/(\w+)/);
            if (langMatch && langMatch[1]) {
                langSelect.value = langMatch[1];
            }
        }
    } catch(e) {
        console.log('Error initializing language selector:', e);
    }
});

// Wait a moment for page load, then initialize
setTimeout(function() {
    try {
        if (typeof google === 'undefined') {
            // Try to manually trigger initialization if Google Translate is now available
            googleTranslateElementInit();
        }
    } catch(e) {
        console.log('Google Translate initialization failed');
    }
}, 500);
</script>

<!-- Load Google Translate API -->
<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
