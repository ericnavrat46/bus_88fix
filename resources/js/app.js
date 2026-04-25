import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

// Real-time Promo Notification
if (window.Echo) {
    window.Echo.channel('promos')
        .listen('.flash-sale.created', (data) => {
            console.log('Flash Sale Event Received:', data);
            
            if (window.Swal) {
                Swal.fire({
                    title: '🎉 Promo Baru!',
                    text: data.message,
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonText: 'Lihat Promo',
                    cancelButtonText: 'Nanti saja',
                    confirmButtonColor: '#d33',
                    borderRadius: '1rem',
                    toast: true,
                    position: 'top-end',
                    timer: 10000,
                    timerProgressBar: true,
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = data.url;
                    }
                });
            }
        });
}

