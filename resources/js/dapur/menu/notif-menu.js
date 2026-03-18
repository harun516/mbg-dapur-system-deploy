import Swal from 'sweetalert2'; // Tambahkan baris ini di paling atas

document.addEventListener('DOMContentLoaded', function() {
    // Fungsi pembantu untuk decode HTML Entities
    const decodeHTML = (html) => {
        const txt = document.createElement("textarea");
        txt.innerHTML = html;
        return txt.value;
    };

    let successMessage = window.successMessage;
    let errorMessage = window.errorMessage;

    if (successMessage && successMessage !== "null") {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: decodeHTML(successMessage), // Decode di sini
            timer: 3000
        });
        window.successMessage = null;
    }

    if (errorMessage && errorMessage !== "null") {
        Swal.fire({
            icon: 'error',
            title: 'Stok Tidak Cukup',
            text: decodeHTML(errorMessage), // Decode di sini
            footer: '<a href="/dapur/request">Ajukan permintaan barang ke gudang?</a>'
        });
        window.errorMessage = null;
    }
});