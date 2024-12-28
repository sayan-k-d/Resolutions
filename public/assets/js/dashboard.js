const handleRedirect = (event) => {
    event.preventDefault();
    if (!event.target.value) {
        window.location.href = "/";
    }
};

// const search = document.getElementById('search');
// search.addEventListener('input', (event) => {
//     event.preventDefault();
//     if (!event.target.value) {
//         window.location.href = "{{ route('dashboard') }}"
//     }

// });
