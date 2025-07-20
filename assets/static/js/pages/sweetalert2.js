const Toast = Swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 3000,
  timerProgressBar: true,
  didOpen: (toast) => {
    toast.addEventListener('mouseenter', Swal.stopTimer)
    toast.addEventListener('mouseleave', Swal.resumeTimer)
  }
});

// Make Toast available globally for use in admin_template.php
window.Toast = Toast;


// Toasts
document.getElementById('toast-success').addEventListener('click', () => {
  Toast.fire({
    icon: 'success',
    title: 'Signed in successfully'
  })
})
document.getElementById('toast-warning').addEventListener('click', () => {
  Toast.fire({
    icon: 'warning',
    title: 'Please input your email'
  })
})
document.getElementById('toast-failed').addEventListener('click', () => {
  Toast.fire({
    icon: 'error',
    title: 'Transaction error. Please try again later'
  })
})