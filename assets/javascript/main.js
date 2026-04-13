const accountImage = document.querySelector('.account img');
if (accountImage) {
    accountImage.addEventListener('click', function () {
        const account = this.closest('.account');
        account.classList.toggle('active');
    });

    document.addEventListener('click', function (e) {
        const account = document.querySelector('.account');
        if (account && !account.contains(e.target)) {
            account.classList.remove('active');
        }
    });
}

const startButton = document.querySelector('.js-login-trigger');
if (startButton) {
    startButton.addEventListener('click', function(e) {
        e.preventDefault();
        const modal = document.getElementById('loginModal');
        if (modal) modal.classList.remove('hidden');
    });
}

const modalClose = document.querySelector('.modal-close');
if (modalClose) {
    modalClose.addEventListener('click', function () {
        const modal = document.getElementById('loginModal');
        if (modal) modal.classList.add('hidden');
    });
}

const modal = document.getElementById('loginModal');
if (modal) {
    modal.addEventListener('click', function (e) {
        if (e.target === this) {
            this.classList.add('hidden');
        }
    });
}

const dateInputs = document.querySelectorAll('input[type="date"]');
dateInputs.forEach(function (input) {
    const openNativePicker = function () {
        if (typeof input.showPicker === 'function') {
            try {
                input.showPicker();
            } catch (error) {
                // Ignore browsers that block showPicker outside trusted contexts.
            }
        }
    };

    input.addEventListener('focus', openNativePicker);
    input.addEventListener('click', openNativePicker);
});
