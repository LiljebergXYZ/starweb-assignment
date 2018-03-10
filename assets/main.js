window.onload = function() {
    // Get the forms in the dom
    var forms = document.getElementsByTagName('form');
    // Ensure there are actually forms
    // Otherwise abort
    if(forms.length === 0) {
        return;
    }

    // Get the first form and inputs
    var form = forms[0];
    var title = document.getElementById('title');
    var description = document.getElementById('description');

    form.onsubmit = function(e) {
        var valid = true;
        // Title can't be empty or contain digits
        if(title.value == '' || /\d+/.test(title.value)) {
            // Make sure that we don't add the error class multiple times
            if(!title.classList.contains('error')) {
                title.classList.add('error');
            }
            valid = false;
        } else {
            // Remove error class if it exists
            if(title.classList.contains('error')) {
                title.classList.remove('error');
            }
        }
        // HTML is not allowed in the description
        if(/<[a-z][\s\S]*>/i.test(description.value)) {
            // Make sure that we don't add the error class multiple times
            if(!description.classList.contains('error')) {
                description.classList.add('error');
            }
            valid = false;
        }  else {
            // Remove error class if it exists
            if(description.classList.contains('error')) {
                description.classList.remove('error');
            }
        }
        return valid;
    };
};