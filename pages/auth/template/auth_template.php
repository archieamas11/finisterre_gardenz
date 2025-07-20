<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <link rel="shortcut icon" href="<?php echo WEBROOT; ?>assets/images/icon.png" type="image/x-icon">
    <link rel="stylesheet" crossorigin href="<?php echo WEBROOT; ?>assets/compiled/css/app.css">
    <link rel="stylesheet" crossorigin href="<?php echo WEBROOT; ?>assets/compiled/css/app-dark.css">
    <link rel="stylesheet" crossorigin href="<?php echo WEBROOT; ?>assets/compiled/css/auth.css">
</head>
<style>
    .password-requirements {
        display: none;
        font-size: 1rem;
        margin-top: 5px;
        padding: 8px;
        border-radius: 4px;
        background: #f8f9fa;
    }

    .password-requirements.show {
        display: block;
    }

    .requirement {
        display: none;
        color: #dc3545;
        margin: 4px 0;
    }

    .requirement.active {
        display: block;
    }

    .is-valid {
        border-color: #198754 !important;
        background-color: #f8fff9 !important;
    }

    .is-valid~.form-control-icon i {
        color: #198754 !important;
    }
</style>

<body>
    <div class="login-page">
        <?php include($auth_content); ?>
    </div>
</body>
<!--Start of Tawk.to Script-->
<script type="text/javascript">
var Tawk_API = Tawk_API || {},
    Tawk_LoadStart = new Date();
(function() {
    var s1 = document.createElement("script"),
        s0 = document.getElementsByTagName("script")[0];
    s1.async = true;
    s1.src = 'https://embed.tawk.to/68249c3d8984c1190f3abff7/1ir7g4kiv';
    s1.charset = 'UTF-8';
    s1.setAttribute('crossorigin', '*');
    s0.parentNode.insertBefore(s1, s0);
})();
</script>
<!--End of Tawk.to Script-->

<script>
if (window.history.replaceState) {
    window.history.replaceState(null, null, window.location.href);
}
</script>

<script>
document.getElementById('inputConfirmpassword').addEventListener('input', function() {
    const errorSpan = this.parentElement.querySelector('.error-feedback');
    if (errorSpan) {
        errorSpan.setAttribute('hidden', true);
        errorSpan.textContent = '';
    }
});

document.getElementById('inputPassword').addEventListener('input', function() {
    const confirmPasswordInput = document.getElementById('inputConfirmpassword');
    const errorSpan = confirmPasswordInput.parentElement.querySelector('.error-feedback');
    if (errorSpan) {
        errorSpan.setAttribute('hidden', true);
        errorSpan.textContent = '';
    }
});
</script>

<script>
document.getElementById('inputPassword').addEventListener('focus', function() {
    document.querySelector('.password-requirements').classList.add('show');
});

document.getElementById('inputPassword').addEventListener('blur', function() {
    if (!this.value) {
        document.querySelector('.password-requirements').classList.remove('show');
    }
});

document.getElementById('inputPassword').addEventListener('input', function() {
    const errorSpan = this.parentElement.querySelector('.error-feedback');
    const confirmPasswordInput = document.getElementById('inputConfirmpassword');
    if (errorSpan) {
        errorSpan.setAttribute('hidden', true);
        errorSpan.textContent = '';
    }
    const password = this.value;
    const requirements = [{
            regex: /.{6,}/,
            element: document.querySelector('.requirement.length'),
            message: 'At least 6 characters'
        },
        {
            regex: /[A-Z]/,
            element: document.querySelector('.requirement.uppercase'),
            message: 'At least one uppercase letter'
        },
        {
            regex: /[a-z]/,
            element: document.querySelector('.requirement.lowercase'),
            message: 'At least one lowercase letter'
        },
        {
            regex: /[0-9]/,
            element: document.querySelector('.requirement.number'),
            message: 'At least one number'
        },
        {
            regex: /[\W]/,
            element: document.querySelector('.requirement.special'),
            message: 'At least one special character'
        }
    ];

    // Reset classes
    this.classList.remove('is-valid', 'is-invalid');
    confirmPasswordInput.classList.remove('is-valid', 'is-invalid');

    // Hide all requirements first
    requirements.forEach(req => {
        req.element.classList.remove('active');
    });

    // Check requirements
    const allRequirementsMet = requirements.every(req => req.regex.test(password));

    if (allRequirementsMet) {
        this.classList.add('is-valid');
        document.querySelector('.password-requirements').classList.remove('show');

        // Check confirm password
        if (confirmPasswordInput.value && confirmPasswordInput.value === password) {
            confirmPasswordInput.classList.add('is-valid');
        }
    } else {
        // Show first failed requirement
        const failedRequirement = requirements.find(req => !req.regex.test(password));
        if (failedRequirement) {
            failedRequirement.element.classList.add('active');
        }
    }
});

// Update confirm password validation
document.getElementById('inputConfirmpassword').addEventListener('input', function() {
    const passwordInput = document.getElementById('inputPassword');
    const errorSpan = this.parentElement.querySelector('.error-feedback');

    if (errorSpan) {
        errorSpan.setAttribute('hidden', true);
        errorSpan.textContent = '';
    }

    this.classList.remove('is-valid', 'is-invalid');

    if (this.value && this.value === passwordInput.value) {
        this.classList.add('is-valid');
    }
});
</script>
</html>