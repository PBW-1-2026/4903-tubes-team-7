function muat() {
    var q = document.querySelector('input[name="q"]');
    var kategori = document.querySelector('input[name="kategori"]:checked');
    var kondisi = document.querySelectorAll('input[name="kondisi"]:checked');
    var sort = document.querySelector('select[name="sort"]');

    var params = [];
    if (q && q.value) params.push('q=' + encodeURIComponent(q.value));
    if (kategori && kategori.value) params.push('kategori=' + encodeURIComponent(kategori.value));
    if (kondisi.length) {
        kondisi.forEach(function(el) { params.push('kondisi[]=' + encodeURIComponent(el.value)); });
    }
    if (sort && sort.value) params.push('sort=' + encodeURIComponent(sort.value));

    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            document.getElementById('hasilProduk').innerHTML = xhr.responseText;
        }
    };
    xhr.open('GET', 'get_produk.php?' + params.join('&'), true);
    xhr.send();
}

function toggleSidebar() {
    document.getElementById('samping').classList.toggle('buka');
}

// Toggle wishlist via AJAX
function toggleWishlist(event, produkId, element) {
    event.preventDefault();
    event.stopPropagation();

    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'wishlist.php?aksi=toggle&produk_id=' + produkId + '&ajax=1', true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            try {
                var res = JSON.parse(xhr.responseText);
                if (res.status === 'success') {
                    if (res.is_wishlisted) {
                        element.classList.add('aktif');
                        if (element.classList.contains('tombol-garis')) {
                            element.classList.add('favorit-aktif');
                        }
                        element.innerHTML = '<img src="icon/wishlist.svg" alt="wishlist" style="width: 1em; height: 1em;">';
                    } else {
                        element.classList.remove('aktif');
                        element.classList.remove('favorit-aktif');
                        element.innerHTML = '<img src="icon/wish.svg" alt="wish" style="width: 1em; height: 1em;">';
                    }
                } else if (res.status === 'error' && res.message === 'not_logged_in') {
                    window.location.href = 'login.php';
                }
            } catch(e) {
                console.error('Error parsing JSON:', e);
            }
        }
    };
    xhr.send();
}