// LOGIKA UNTUK PEMBERITAHUAN SUKSES TIDAKNYA PESAN DIMKIRIM PADA HALAMAN KONTAK
document.getElementById('contactForm').addEventListener('submit', function (event) {
  event.preventDefault(); //

  // Mengambil data dari form
  const formData = new FormData(this);

  // Kirim data menggunakan fetch ke koneksi.php
  fetch('koneksi.php', {
    method: 'POST', // Menggunakan metode POST
    body: formData   // Body berisi data form yang sudah diambil sebelumnya
  })
    .then(response => response.json()) // Mengonversi respon menjadi format JSON
    .then(data => {
      // Mengecek status respon
      if (data.status === 'success') {
        alert(data.message); // Tampilkan pesan sukses
        document.getElementById('contactForm').reset(); // Setelah sukses akan mereset form
      } else {
        alert('Terjadi kesalahan: ' + data.message); // Tampilkan pesan error jika ada masalah
      }
    })
    .catch(error => {
      alert('Terjadi kesalahan pada jaringan atau server.'); // Pesan error jaringan/server
      console.error(error); // Menampilkan error di console untuk debugging
    });
});



//LOGIKA SEARCH
function toggleSearch() {
  const query = document.getElementById('searchInput').value.toLowerCase(); // Ambil input pencarian
  const items = document.querySelectorAll('#menu h3'); // Ambil semua elemen <h3> di dalam #menu
  let found = false; // Penanda apakah hasil ditemukan

  items.forEach(item => {
    // Cek apakah isi item mengandung query pencarian
    if (item.textContent.toLowerCase().includes(query)) {
      found = true;
      item.scrollIntoView({ behavior: 'smooth', block: 'center' }); // Scroll ke elemen yang ditemukan
    }
  });

  if (!found) {
    alert('Item tidak ditemukan di menu.'); // Jika tidak ada hasil
  }
}